<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\Schools;
use App\Models\Setting;
use App\Models\SmsLog;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CrmApiController extends BaseController
{
    // // After SKU add function base on sku find othervise rollback userSaveFromApi
    public function userSaveFromApi(Request $request)
    {
        try {
            $role_selected = $request->role ?? 'school_admin';

            // ── Basic shared validation ───────────────────────────────────────────
            $validator = Validator::make($request->all(), [
                'soid' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $mobile = $request->decision_maker_mobile_no ?? $request->mobile_no;
            if ($mobile === 'NA' || $mobile === 'N/A' || $mobile === '' || $mobile === null) {
                $mobile = null;
            }

            if (!empty($mobile)) {
                if (!$this->isValidMobile($mobile)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid mobile number. It must be a valid 10-digit Indian number.',
                    ], 422);
                }
            } else {
                $mobile = null;
            }

            $na = fn($v) => ($v === null || $v === '') ? null : $v;

            // ── Raw API log ──────────────────────────────────────────────────────
            DB::table('crm_api_incoming_logs')->updateOrInsert(
                ['soid' => $request->soid],
                [
                    'role'       => $request->role ?? 'school_admin',
                    'payload'    => json_encode($request->all()),
                    'ip_address' => $request->ip(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // ── PRE-CHECK: If content_assignment has skuCode, validate at least one SKU exists ──
            $isSkuMode = false;
            $resolvedSkuContent = []; // keyed by class_id|series_id → merged subject_ids

            if (is_array($request->content_assignment)) {

                foreach ($request->content_assignment as $content) {
                    foreach ($content['series'] ?? [] as $seriesData) {
                        if (!empty($seriesData['skuCode']) && is_array($seriesData['skuCode'])) {
                            $isSkuMode = true;
                            break 2;
                        }
                    }
                }

                if ($isSkuMode) {

                    foreach ($request->content_assignment as $content) {
                        foreach ($content['series'] ?? [] as $seriesData) {

                            $skuCodes = $seriesData['skuCode'] ?? [];
                            if (empty($skuCodes) || !is_array($skuCodes)) {
                                continue;
                            }

                            foreach ($skuCodes as $skuName) {
                                $skuName = trim($skuName);
                                if (empty($skuName)) {
                                    continue;
                                }

                                // $skuMeta = \App\Models\CourseMetadataValue::where('field_name', 'sku')
                                //     ->where('field_value', $skuName)
                                //     ->first();

                                // if (!$skuMeta) {
                                //     continue;
                                // }

                                // $courseId   = $skuMeta->course_id;
                                // $courseMeta = \App\Models\CourseMetadataValue::where('course_id', $courseId)
                                //     ->pluck('field_value', 'field_name');

                                // // Resolve class
                                // $classId = $courseMeta['class'] ?? null;
                                // if (!$classId) continue;
                                // $class = Classes::where('id', $classId)->where('is_active', 1)->first();
                                // if (!$class) continue;

                                // // Resolve series
                                // $seriesId = $courseMeta['series'] ?? null;
                                // if (!$seriesId) continue;
                                // $series = BookSeries::where('id', $seriesId)->where('is_active', 1)->first();
                                // if (!$series) continue;

                                // // Resolve subjects
                                // $subjectRaw = $courseMeta['subject'] ?? null;
                                // $subjectIds = [];
                                // if ($subjectRaw) {
                                //     $decoded    = json_decode($subjectRaw, true);
                                //     $subjectIds = is_array($decoded) ? $decoded : [$subjectRaw];
                                // }
                                // if (empty($subjectIds)) continue;

                                $resolvedFromBookSet = false;

                                $skuMeta = \App\Models\CourseMetadataValue::where('field_name', 'sku')
                                    ->where('field_value', $skuName)
                                    ->first();

                                /* Fallback : If not found in course metadata check BookSet */
                                if (!$skuMeta) {

                                    $bookSet = \App\Models\BookSet::where('sku_code', $skuName)
                                        ->where('is_active', 1)
                                        ->first();

                                    if (!$bookSet) {
                                        continue;
                                    }

                                    $resolvedFromBookSet = true;

                                    $classId   = $bookSet->class_id;
                                    $seriesId  = $bookSet->series_id;

                                    $class = Classes::where('id', $classId)
                                        ->where('is_active', 1)
                                        ->first();

                                    if (!$class) {
                                        continue;
                                    }

                                    $series = BookSeries::where('id', $seriesId)
                                        ->where('is_active', 1)
                                        ->first();

                                    if (!$series) {
                                        continue;
                                    }

                                    // book_sets.subject_id stored comma separated
                                    $subjectIds = array_filter(
                                        array_map('trim', explode(',', $bookSet->subject_id))
                                    );
                                } else {

                                    /* Existing current CourseMetadataValue logic unchanged */
                                    $courseId   = $skuMeta->course_id;

                                    $courseMeta = \App\Models\CourseMetadataValue::where('course_id', $courseId)
                                        ->pluck('field_value', 'field_name');

                                    $classId = $courseMeta['class'] ?? null;
                                    if (!$classId) continue;

                                    $class = Classes::where('id', $classId)
                                        ->where('is_active', 1)
                                        ->first();

                                    if (!$class) continue;

                                    $seriesId = $courseMeta['series'] ?? null;
                                    if (!$seriesId) continue;

                                    $series = BookSeries::where('id', $seriesId)
                                        ->where('is_active', 1)
                                        ->first();

                                    if (!$series) continue;

                                    $subjectRaw = $courseMeta['subject'] ?? null;

                                    $subjectIds = [];
                                    if ($subjectRaw) {
                                        $decoded = json_decode($subjectRaw, true);
                                        $subjectIds = is_array($decoded)
                                            ? $decoded
                                            : [$subjectRaw];
                                    }

                                    if (empty($subjectIds)) {
                                        continue;
                                    }
                                }

                                // ── Group by class_id|series_id — merge subject IDs ──────
                                $groupKey = $classId . '|' . $seriesId;

                                if (!isset($resolvedSkuContent[$groupKey])) {
                                    $isThinkTrail = strtolower(trim($series->name)) === 'think trail';
                                    $addOns       = is_array($seriesData['add_ons'] ?? null)
                                        ? array_values(array_filter($seriesData['add_ons']))
                                        : [];

                                    $resolvedSkuContent[$groupKey] = [
                                        'class'          => $class,
                                        'series'         => $series,
                                        'subject_ids'    => $subjectIds, // start with this SKU's subjects
                                        'add_ons'        => $addOns,
                                        'is_think_trail' => $isThinkTrail,
                                        'mittlens'       => isset($seriesData['mittlens']) ? (int)$seriesData['mittlens'] : null,
                                        'techlite'       => isset($seriesData['techlite']) ? (int)$seriesData['techlite'] : null,
                                        'jaaduipitarakit2' => isset($seriesData['jaaduipitarakit2']) ? (int)$seriesData['jaaduipitarakit2'] : null,
                                    ];
                                } else {
                                    // Same class+series from another SKU — merge subject IDs, no duplicates
                                    $resolvedSkuContent[$groupKey]['subject_ids'] = array_unique(
                                        array_merge($resolvedSkuContent[$groupKey]['subject_ids'], $subjectIds)
                                    );
                                }
                            }
                        }
                    }

                    if (empty($resolvedSkuContent)) {
                        return response()->json([
                            'status'  => false,
                            'message' => 'No valid SKU found in LMS. School was not created.',
                        ], 422);
                    }
                }
            }

            // STEP 1 – RM (salesman)
            $rmId = null;

            if ($role_selected === 'school_admin' && ($request->rm_email || $request->rm_mobile)) {

                $rmKey = $request->rm_email
                    ? ['email' => $request->rm_email]
                    : ['mobile_no' => $request->rm_mobile];

                $rm = User::updateOrCreate(
                    $rmKey,
                    [
                        'name'               => $na($request->rm_name) ?? 'RM User',
                        'email'              => $na($request->rm_email),
                        'mobile_no'          => $na($request->rm_mobile),
                        'password'           => Hash::make('Mitt@123'),
                        'validate_string'    => 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'source'             => 'crm',
                        'is_from_external'   => 1,
                    ]
                );

                if ($rm) {
                    UserRole::updateOrCreate(
                        ['user_id' => $rm->id],
                        ['role_slug' => 'salesman']
                    );

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $rm->id],
                        [
                            'role'        => 'salesman',
                            'user_id'     => $rm->id,
                            'employee_id' => $na($request->rm_employee_id),
                            'city'        => $na($request->district),
                            'state'       => $na($request->state),
                        ]
                    );

                    $rmId = $rm->id;
                }
            }

            // STEP 2 – Distributor
            $distributorUserId = null;

            if ($role_selected === 'school_admin' && ($request->distributor_email || $request->distributor_mobile)) {

                $distKey = $request->distributor_email
                    ? ['email' => $request->distributor_email]
                    : ['mobile_no' => $request->distributor_mobile];

                $distributor = User::updateOrCreate(
                    $distKey,
                    [
                        'name'               => $na($request->distributor_name) ?? 'Distributor',
                        'email'              => $na($request->distributor_email),
                        'mobile_no'          => $na($request->distributor_mobile),
                        'password'           => Hash::make('Mitt@123'),
                        'validate_string'    => 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'source'             => 'crm',
                        'is_from_external'   => 1,
                    ]
                );

                if ($distributor) {
                    UserRole::updateOrCreate(
                        ['user_id' => $distributor->id],
                        ['role_slug' => 'distributors']
                    );

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $distributor->id],
                        [
                            'role'           => 'distributors',
                            'user_id'        => $distributor->id,
                            'distributor_id' => $na($request->distributor_id),
                            'city'           => $na($request->district),
                            'state'          => $na($request->state),
                            'address'        => $na($request->address_1),
                        ]
                    );

                    $distributorUserId = $distributor->id;
                }
            }

            // STEP 3 – Main user record
            $existingSchoolOrder = false;

            // First check by school_id
            $user = null;

            if (!empty($request->school_id)) {

                $user = User::where('school_id', $request->school_id)->first();

                if ($user && $user->soid != $request->soid) {
                    // Same school, new SOID (repeat order)
                    $existingSchoolOrder = true;
                }
            }

            if (!$user) {

                // ORIGINAL EXISTING LOGIC KEPT SAME
                $user = User::updateOrCreate(
                    ['soid' => $request->soid],
                    [
                        'name'               => $na($request->name),
                        'username'           => $na($request->username),
                        'email'              => $na($request->email),
                        'mobile_no'          => $mobile,
                        'password'           => Hash::make($request->password ?? 'Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'source'             => 'crm',
                        'is_from_external'   => 1,
                        'is_verified'        => '0',
                        'status'             => '0',
                        'school_id'          => $na($request->school_id),
                        'boid'               => $na($request->soid),
                    ]
                );
            }

            // STEP 4 – Role assignment
            UserRole::updateOrCreate(
                ['user_id' => $user->id],
                ['role_slug' => $role_selected]
            );

            // STEP 5 – School Admin specific records

            // 5a. Schools record
            $stateName = trim($request->state ?? '');
            $cityName  = trim($request->district ?? '');

            $state = \DB::table('states')
                ->whereRaw('LOWER(name) = ?', [strtolower($stateName)])
                ->first();
            $stateId = $state->id ?? null;

            $city = \DB::table('cities')
                ->whereRaw('LOWER(city) = ?', [strtolower($cityName)])
                ->first();
            $cityId = $city->id ?? null;

            $school = Schools::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id'              => $user->id,
                    'unique_id'            => $na($request->uniqueId),
                    'school_type'          => 'individual',
                    'school_role'          => $na($request->school_role),
                    'is_verified_by_admin' => 0,
                    'is_varified_by'       => 0,
                    'name'                 => $na($request->name),
                    'address'              => $na($request->address_1),
                    'city'                 => $cityId,
                    'state'                => $stateId,
                    'postal_code'          => $na($request->pincode),
                    'academic_session_id'  => '5',
                    'batch_id'             => '6',
                    'is_from_crm'          => 1,
                ]
            );

            // 5b. Class assignments (by ID array, portal-style)
            if (is_array($request->class) && count($request->class)) {

                $currentClasses  = SchoolAssignedClass::where('school_id', $user->id)->pluck('class_id')->toArray();
                $updatedClasses  = $request->class;
                $classesToDelete = array_diff($currentClasses, $updatedClasses);

                if ($classesToDelete) {
                    SchoolAssignedClass::whereIn('class_id', $classesToDelete)
                        ->where('school_id', $user->id)
                        ->delete();
                }

                foreach ($updatedClasses as $classId) {
                    SchoolAssignedClass::updateOrCreate(
                        ['school_id' => $user->id, 'class_id' => $classId],
                        ['school_id' => $user->id, 'class_id' => $classId]
                    );
                }
            }

            // 5c. Class assignments from content_assignment
            if (is_array($request->content_assignment) && !is_array($request->class)) {

                if (!$existingSchoolOrder) {
                    SchoolAssignedClass::where('school_id', $user->id)->delete();
                }
                if ($isSkuMode) {
                    // SKU mode: assign classes resolved from SKU lookup
                    $assignedClassIds = [];
                    foreach ($resolvedSkuContent as $resolved) {
                        $classId = $resolved['class']->id;
                        if (!in_array($classId, $assignedClassIds)) {
                            SchoolAssignedClass::updateOrCreate(
                                ['school_id' => $user->id, 'class_id' => $classId],
                                ['school_id' => $user->id, 'class_id' => $classId]
                            );
                            $assignedClassIds[] = $classId;
                        }
                    }
                } else {
                    // Name-based mode: assign classes from class_name in payload
                    foreach ($request->content_assignment as $content) {
                        if (empty($content['class_name'])) {
                            continue;
                        }

                        $class = Classes::where('name', $content['class_name'])
                            ->where('is_active', 1)
                            ->first();

                        if (!$class) {
                            continue;
                        }

                        SchoolAssignedClass::updateOrCreate(
                            ['school_id' => $user->id, 'class_id' => $class->id],
                            ['school_id' => $user->id, 'class_id' => $class->id]
                        );
                    }
                }
            }

            // 5d. UserAdditionalDetail
            UserAdditionalDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'role'                     => $role_selected,
                    'user_id'                  => $user->id,
                    'school_id'                => $user->id,
                    'assign_to'                => $rmId ?? $na($request->assign_to),
                    'assign_distributor'       => $distributorUserId ?? $na($request->assign_distributor),
                    'lead'                     => $na($request->lead),
                    'parent_school_name'       => $na($request->parent_school_name),
                    'city'                     => $cityId,
                    'state'                    => $stateId,
                    'website'                  => $na($request->website),
                    'decision_maker'           => $na($request->decision_maker),
                    'decision_maker_mobile_no' => $mobile,
                    'decision_maker_role'      => $na($request->decision_maker_role),
                    'school_board'             => '0',
                    'school_medium'            => '0',
                    'strength'                 => $na($request->strength),
                    'grade'                    => $na($request->grade),
                    'school_affiliation_no'    => $na($request->school_affiliation),
                    'school_registration_no'   => $na($request->school_registration_no),
                    'incorporation_date'       => $na($request->incorporation_date),
                    'gst_no'                   => $na($request->gst_no),
                    'board_erp'                => $na($request->onboardERP),
                    'address'                  => $na($request->address_2),
                    'landmark'                 => $na($request->landmark),
                    'bank_name'                => $na($request->bank_name),
                    'acc_holder_name'          => $na($request->acc_holder_name),
                    'branch_name'              => $na($request->branch_name),
                    'acc_no'                   => $na($request->acc_no),
                    'ifsc_code'                => $na($request->ifsc_code),
                    'customer_type'            => 'new',
                ]
            );

            // 5e. Digital content assignment + add-ons
            if (is_array($request->content_assignment)) {

                $addonLogEntries = [];

                DB::beginTransaction();

                try {
                    // SchoolAssignedDigitalContent::where('school_id', $user->id)->delete();
                    // For repeat orders keep old content also
                    if (!$existingSchoolOrder) {
                        SchoolAssignedDigitalContent::where('school_id', $user->id)->delete();
                    }
                    if ($isSkuMode) {
                        // ── SKU MODE: Use pre-resolved course data ────────────────
                        foreach ($resolvedSkuContent as $skuName => $resolved) {

                            // SchoolAssignedDigitalContent::create([
                            //     'school_id'  => $user->id,
                            //     'class_id'   => $resolved['class']->id,
                            //     'series_id'  => $resolved['series']->id,
                            //     'subject_id' => implode(',', $resolved['subject_ids']),
                            //     'created_by' => Auth::id() ?? 612,
                            // ]);
                            $existingContent = SchoolAssignedDigitalContent::where([
                                'school_id' => $user->id,
                                'class_id' => $resolved['class']->id,
                                'series_id' => $resolved['series']->id
                            ])->first();

                            $newSubjects = $resolved['subject_ids'];

                            if ($existingContent) {
                                $old = explode(',', $existingContent->subject_id);

                                $newSubjects = array_unique(
                                    array_merge($old, $newSubjects)
                                );
                            }

                            SchoolAssignedDigitalContent::updateOrCreate(
                                [
                                    'school_id' => $user->id,
                                    'class_id' => $resolved['class']->id,
                                    'series_id' => $resolved['series']->id
                                ],
                                [
                                    'subject_id' => implode(',', $newSubjects),
                                    'created_by' => Auth::id() ?? 612
                                ]
                            );
                            // Build add-on log entry
                            $hasAddons = !empty($resolved['add_ons']);

                            if ($hasAddons || $resolved['is_think_trail']) {
                                $logEntry = [
                                    'logged_at'  => now()->toIso8601String(),
                                    'user_id'    => $user->id,
                                    'class_name' => strtolower($resolved['class']->name),
                                    'series'     => strtolower($resolved['series']->name),
                                    'add_ons'    => $resolved['add_ons'],
                                ];

                                if ($resolved['is_think_trail']) {
                                    $logEntry['mittleance'] = $resolved['mittlens'];
                                    $logEntry['techlite']   = $resolved['techlite'];
                                }

                                $addonLogEntries[] = $logEntry;
                            }
                        }
                    }
                    // else {
                    //     // ── NAME-BASED MODE: Original logic ──────────────────────
                    //     foreach ($request->content_assignment as $content) {

                    //         $className = trim(strtolower($content['class_name'] ?? ''));

                    //         $class = Classes::whereRaw('LOWER(TRIM(name)) = ?', [$className])
                    //             ->where('is_active', 1)
                    //             ->first();

                    //         if (!$class) {
                    //             continue;
                    //         }

                    //         foreach ($content['series'] ?? [] as $seriesData) {
                    //             $seriesName = trim(strtolower($seriesData['series_id'] ?? ''));

                    //             $series = BookSeries::whereRaw('LOWER(TRIM(name)) = ?', [$seriesName])
                    //                 ->where('is_active', 1)
                    //                 ->first();

                    //             if (!$series) {
                    //                 continue;
                    //             }

                    //             $subjects = array_map(
                    //                 fn($item) => trim(strtolower($item)),
                    //                 $seriesData['subjects'] ?? []
                    //             );

                    //             $subjectIds = Subject::whereIn(
                    //                 DB::raw('LOWER(TRIM(name))'),
                    //                 $subjects
                    //             )
                    //                 ->where('is_active', 1)
                    //                 ->pluck('id')
                    //                 ->toArray();

                    //             if (empty($subjectIds)) {
                    //                 continue;
                    //             }

                    //             SchoolAssignedDigitalContent::create([
                    //                 'school_id'  => $user->id,
                    //                 'class_id'   => $class->id,
                    //                 'series_id'  => $series->id,
                    //                 'subject_id' => implode(',', $subjectIds),
                    //                 'created_by' => Auth::id() ?? 612,
                    //             ]);

                    //             $hasAddons    = array_key_exists('add_ons', $seriesData);
                    //             $isThinkTrail = strtolower(trim($seriesName)) == 'think trail';

                    //             if ($hasAddons || $isThinkTrail) {

                    //                 $addOns = is_array($seriesData['add_ons'] ?? null)
                    //                     ? array_values(array_filter($seriesData['add_ons']))
                    //                     : [];

                    //                 $logEntry = [
                    //                     'logged_at'  => now()->toIso8601String(),
                    //                     'user_id'    => $user->id,
                    //                     'class_name' => $className,
                    //                     'series'     => $seriesName,
                    //                     'add_ons'    => $addOns,
                    //                 ];

                    //                 if ($isThinkTrail) {
                    //                     $logEntry['mittleance'] = isset($seriesData['mittlens'])
                    //                         ? (int) $seriesData['mittlens']
                    //                         : null;
                    //                     $logEntry['techlite'] = isset($seriesData['techlite'])
                    //                         ? (int) $seriesData['techlite']
                    //                         : null;
                    //                 }

                    //                 $addonLogEntries[] = $logEntry;
                    //             }
                    //         }
                    //     }
                    // }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }

                // ── Save addon entries to DB ──────────────────────────────────────
                if (!empty($addonLogEntries)) {
                    \App\Models\CrmSchoolAddon::where('user_id', $user->id)->delete();

                    foreach ($addonLogEntries as $entry) {
                        \App\Models\CrmSchoolAddon::create([
                            'user_id'     => $entry['user_id'],
                            'class_name'  => $entry['class_name'],
                            'series_name' => $entry['series'],
                            'add_ons'     => $entry['add_ons'],
                            'mittleance'  => $entry['mittleance'] ?? null,
                            'techlite'    => $entry['techlite'] ?? null,
                            'created_by'  => Auth::id() ?? 612,
                        ]);
                    }
                }
            }

            // STEP 6 – Auto SMS to RM if email or mobile is missing
            $emailMissing  = in_array(trim((string) ($request->email ?? '')), ['', 'null', 'N/A', 'n/a', 'NA', 'na'], true);
            $mobileMissing = in_array(trim((string) ($mobile ?? '')), ['', 'null', 'N/A', 'n/a', 'NA', 'na'], true);

            // if (($emailMissing || $mobileMissing) && $rmId) {
            if (($mobileMissing) && $rmId) {

                $rm = User::find($rmId);

                if ($rm && !empty($rm->mobile_no)) {

                    $schoolName      = $na($request->name) ?? 'N/A';
                    $mobileNo        = $rm->mobile_no;
                    $supportMobileNo = Setting::where('field_name', 'rm_support_mobile_number')->value('field_value') ?? '8696259964';
                    $templateKey     = 'School Missing Detail';
                    $message         = "Dear RM, please provide Email and Mobile no. of {$schoolName} for ERP/ Digital Content onboarding immediately. Share Email and Mobile Number of the party at {$supportMobileNo} ASAP. Mittsure";

                    try {
                        sendSms($mobileNo, null, null, $templateKey, $message);

                        $this->logSms(
                            sentTo: $mobileNo,
                            templateKey: $templateKey,
                            message: $message,
                            triggeredBy: 'autoSmsToRM',
                            status: 'sent',
                            senderUserId: auth()->id() ?? 612,
                            relatedSchoolId: $school->id ?? null,
                            relatedRmId: $rm->id,
                        );
                    } catch (\Exception $smsEx) {
                        $this->logSms(
                            sentTo: $mobileNo,
                            templateKey: $templateKey,
                            message: $message,
                            triggeredBy: 'autoSmsToRM',
                            status: 'failed',
                            senderUserId: auth()->id() ?? 612,
                            relatedSchoolId: $school->id ?? null,
                            relatedRmId: $rm->id,
                            errorMessage: $smsEx->getMessage(),
                        );

                        \Log::error('userSaveFromApi auto SMS to RM failed: ' . $smsEx->getMessage());
                    }
                }
            }

            return response()->json([
                'status'         => true,
                'message'        => 'User saved successfully',
                'user_id'        => $user->id,
                'rm_id'          => $rmId,
                'distributor_id' => $distributorUserId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function userSaveFromApiBeforeSkuGroup(Request $request)
    {
        try {
            $role_selected = $request->role ?? 'school_admin';

            // ── Basic shared validation ───────────────────────────────────────────
            $validator = Validator::make($request->all(), [
                'soid' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $mobile = $request->decision_maker_mobile_no ?? $request->mobile_no;
            if ($mobile === 'NA' || $mobile === 'N/A' || $mobile === '' || $mobile === null) {
                $mobile = null;
            }

            if (!empty($mobile)) {
                if (!$this->isValidMobile($mobile)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid mobile number. It must be a valid 10-digit Indian number.',
                    ], 422);
                }
            } else {
                $mobile = null;
            }

            $na = fn($v) => ($v === null || $v === '') ? null : $v;

            // ── Raw API log ──────────────────────────────────────────────────────
            DB::table('crm_api_incoming_logs')->updateOrInsert(
                ['soid' => $request->soid],
                [
                    'role'       => $request->role ?? 'school_admin',
                    'payload'    => json_encode($request->all()),
                    'ip_address' => $request->ip(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // ── PRE-CHECK: If content_assignment has skuCode, validate at least one SKU exists ──
            $isSkuMode = false;
            $resolvedSkuContent = []; // keyed by class_id|series_id → merged subject_ids

            if (is_array($request->content_assignment)) {

                foreach ($request->content_assignment as $content) {
                    foreach ($content['series'] ?? [] as $seriesData) {
                        if (!empty($seriesData['skuCode']) && is_array($seriesData['skuCode'])) {
                            $isSkuMode = true;
                            break 2;
                        }
                    }
                }

                if ($isSkuMode) {

                    foreach ($request->content_assignment as $content) {
                        foreach ($content['series'] ?? [] as $seriesData) {

                            $skuCodes = $seriesData['skuCode'] ?? [];
                            if (empty($skuCodes) || !is_array($skuCodes)) {
                                continue;
                            }

                            foreach ($skuCodes as $skuName) {
                                $skuName = trim($skuName);
                                if (empty($skuName)) {
                                    continue;
                                }

                                $skuMeta = \App\Models\CourseMetadataValue::where('field_name', 'sku')
                                    ->where('field_value', $skuName)
                                    ->first();

                                if (!$skuMeta) {
                                    continue;
                                }

                                $courseId   = $skuMeta->course_id;
                                $courseMeta = \App\Models\CourseMetadataValue::where('course_id', $courseId)
                                    ->pluck('field_value', 'field_name');

                                // Resolve class
                                $classId = $courseMeta['class'] ?? null;
                                if (!$classId) continue;
                                $class = Classes::where('id', $classId)->where('is_active', 1)->first();
                                if (!$class) continue;

                                // Resolve series
                                $seriesId = $courseMeta['series'] ?? null;
                                if (!$seriesId) continue;
                                $series = BookSeries::where('id', $seriesId)->where('is_active', 1)->first();
                                if (!$series) continue;

                                // Resolve subjects
                                $subjectRaw = $courseMeta['subject'] ?? null;
                                $subjectIds = [];
                                if ($subjectRaw) {
                                    $decoded    = json_decode($subjectRaw, true);
                                    $subjectIds = is_array($decoded) ? $decoded : [$subjectRaw];
                                }
                                if (empty($subjectIds)) continue;

                                // ── Group by class_id|series_id — merge subject IDs ──────
                                $groupKey = $classId . '|' . $seriesId;

                                if (!isset($resolvedSkuContent[$groupKey])) {
                                    $isThinkTrail = strtolower(trim($series->name)) === 'think trail';
                                    $addOns       = is_array($seriesData['add_ons'] ?? null)
                                        ? array_values(array_filter($seriesData['add_ons']))
                                        : [];

                                    $resolvedSkuContent[$groupKey] = [
                                        'class'          => $class,
                                        'series'         => $series,
                                        'subject_ids'    => $subjectIds, // start with this SKU's subjects
                                        'add_ons'        => $addOns,
                                        'is_think_trail' => $isThinkTrail,
                                        'mittlens'       => isset($seriesData['mittlens']) ? (int)$seriesData['mittlens'] : null,
                                        'techlite'       => isset($seriesData['techlite']) ? (int)$seriesData['techlite'] : null,
                                        'jaaduipitarakit2' => isset($seriesData['jaaduipitarakit2']) ? (int)$seriesData['jaaduipitarakit2'] : null,
                                    ];
                                } else {
                                    // Same class+series from another SKU — merge subject IDs, no duplicates
                                    $resolvedSkuContent[$groupKey]['subject_ids'] = array_unique(
                                        array_merge($resolvedSkuContent[$groupKey]['subject_ids'], $subjectIds)
                                    );
                                }
                            }
                        }
                    }

                    if (empty($resolvedSkuContent)) {
                        return response()->json([
                            'status'  => false,
                            'message' => 'No valid SKU found in LMS. School was not created.',
                        ], 422);
                    }
                }
            }

            // STEP 1 – RM (salesman)
            $rmId = null;

            if ($role_selected === 'school_admin' && ($request->rm_email || $request->rm_mobile)) {

                $rmKey = $request->rm_email
                    ? ['email' => $request->rm_email]
                    : ['mobile_no' => $request->rm_mobile];

                $rm = User::updateOrCreate(
                    $rmKey,
                    [
                        'name'               => $na($request->rm_name) ?? 'RM User',
                        'email'              => $na($request->rm_email),
                        'mobile_no'          => $na($request->rm_mobile),
                        'password'           => Hash::make('Mitt@123'),
                        'validate_string'    => 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'source'             => 'crm',
                        'is_from_external'   => 1,
                    ]
                );

                if ($rm) {
                    UserRole::updateOrCreate(
                        ['user_id' => $rm->id],
                        ['role_slug' => 'salesman']
                    );

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $rm->id],
                        [
                            'role'        => 'salesman',
                            'user_id'     => $rm->id,
                            'employee_id' => $na($request->rm_employee_id),
                            'city'        => $na($request->district),
                            'state'       => $na($request->state),
                        ]
                    );

                    $rmId = $rm->id;
                }
            }

            // STEP 2 – Distributor
            $distributorUserId = null;

            if ($role_selected === 'school_admin' && ($request->distributor_email || $request->distributor_mobile)) {

                $distKey = $request->distributor_email
                    ? ['email' => $request->distributor_email]
                    : ['mobile_no' => $request->distributor_mobile];

                $distributor = User::updateOrCreate(
                    $distKey,
                    [
                        'name'               => $na($request->distributor_name) ?? 'Distributor',
                        'email'              => $na($request->distributor_email),
                        'mobile_no'          => $na($request->distributor_mobile),
                        'password'           => Hash::make('Mitt@123'),
                        'validate_string'    => 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'source'             => 'crm',
                        'is_from_external'   => 1,
                    ]
                );

                if ($distributor) {
                    UserRole::updateOrCreate(
                        ['user_id' => $distributor->id],
                        ['role_slug' => 'distributors']
                    );

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $distributor->id],
                        [
                            'role'           => 'distributors',
                            'user_id'        => $distributor->id,
                            'distributor_id' => $na($request->distributor_id),
                            'city'           => $na($request->district),
                            'state'          => $na($request->state),
                            'address'        => $na($request->address_1),
                        ]
                    );

                    $distributorUserId = $distributor->id;
                }
            }

            // STEP 3 – Main user record
            $existingSchoolOrder = false;

            // First check by school_id
            $user = null;

            if (!empty($request->school_id)) {

                $user = User::where('school_id', $request->school_id)->first();

                if ($user && $user->soid != $request->soid) {
                    // Same school, new SOID (repeat order)
                    $existingSchoolOrder = true;
                }
            }

            if (!$user) {

                // ORIGINAL EXISTING LOGIC KEPT SAME
                $user = User::updateOrCreate(
                    ['soid' => $request->soid],
                    [
                        'name'               => $na($request->name),
                        'username'           => $na($request->username),
                        'email'              => $na($request->email),
                        'mobile_no'          => $mobile,
                        'password'           => Hash::make($request->password ?? 'Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'source'             => 'crm',
                        'is_from_external'   => 1,
                        'is_verified'        => '0',
                        'status'             => '0',
                        'school_id'          => $na($request->school_id),
                        'boid'               => $na($request->soid),
                    ]
                );
            }

            // STEP 4 – Role assignment
            UserRole::updateOrCreate(
                ['user_id' => $user->id],
                ['role_slug' => $role_selected]
            );

            // STEP 5 – School Admin specific records

            // 5a. Schools record
            $stateName = trim($request->state ?? '');
            $cityName  = trim($request->district ?? '');

            $state = \DB::table('states')
                ->whereRaw('LOWER(name) = ?', [strtolower($stateName)])
                ->first();
            $stateId = $state->id ?? null;

            $city = \DB::table('cities')
                ->whereRaw('LOWER(city) = ?', [strtolower($cityName)])
                ->first();
            $cityId = $city->id ?? null;

            $school = Schools::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id'              => $user->id,
                    'unique_id'            => $na($request->uniqueId),
                    'school_type'          => 'individual',
                    'school_role'          => $na($request->school_role),
                    'is_verified_by_admin' => 0,
                    'is_varified_by'       => 0,
                    'name'                 => $na($request->name),
                    'address'              => $na($request->address_1),
                    'city'                 => $cityId,
                    'state'                => $stateId,
                    'postal_code'          => $na($request->pincode),
                    'academic_session_id'  => '5',
                    'batch_id'             => '6',
                    'is_from_crm'          => 1,
                ]
            );

            // 5b. Class assignments (by ID array, portal-style)
            if (is_array($request->class) && count($request->class)) {

                $currentClasses  = SchoolAssignedClass::where('school_id', $user->id)->pluck('class_id')->toArray();
                $updatedClasses  = $request->class;
                $classesToDelete = array_diff($currentClasses, $updatedClasses);

                if ($classesToDelete) {
                    SchoolAssignedClass::whereIn('class_id', $classesToDelete)
                        ->where('school_id', $user->id)
                        ->delete();
                }

                foreach ($updatedClasses as $classId) {
                    SchoolAssignedClass::updateOrCreate(
                        ['school_id' => $user->id, 'class_id' => $classId],
                        ['school_id' => $user->id, 'class_id' => $classId]
                    );
                }
            }

            // 5c. Class assignments from content_assignment
            if (is_array($request->content_assignment) && !is_array($request->class)) {

                if (!$existingSchoolOrder) {
                    SchoolAssignedClass::where('school_id', $user->id)->delete();
                }

                if ($isSkuMode) {
                    // SKU mode: assign classes resolved from SKU lookup
                    $assignedClassIds = [];
                    foreach ($resolvedSkuContent as $resolved) {
                        $classId = $resolved['class']->id;
                        if (!in_array($classId, $assignedClassIds)) {
                            SchoolAssignedClass::updateOrCreate(
                                ['school_id' => $user->id, 'class_id' => $classId],
                                ['school_id' => $user->id, 'class_id' => $classId]
                            );
                            $assignedClassIds[] = $classId;
                        }
                    }
                } else {
                    // Name-based mode: assign classes from class_name in payload
                    foreach ($request->content_assignment as $content) {
                        if (empty($content['class_name'])) {
                            continue;
                        }

                        $class = Classes::where('name', $content['class_name'])
                            ->where('is_active', 1)
                            ->first();

                        if (!$class) {
                            continue;
                        }

                        SchoolAssignedClass::updateOrCreate(
                            ['school_id' => $user->id, 'class_id' => $class->id],
                            ['school_id' => $user->id, 'class_id' => $class->id]
                        );
                    }
                }
            }

            // 5d. UserAdditionalDetail
            UserAdditionalDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'role'                     => $role_selected,
                    'user_id'                  => $user->id,
                    'school_id'                => $user->id,
                    'assign_to'                => $rmId ?? $na($request->assign_to),
                    'assign_distributor'       => $distributorUserId ?? $na($request->assign_distributor),
                    'lead'                     => $na($request->lead),
                    'parent_school_name'       => $na($request->parent_school_name),
                    'city'                     => $cityId,
                    'state'                    => $stateId,
                    'website'                  => $na($request->website),
                    'decision_maker'           => $na($request->decision_maker),
                    'decision_maker_mobile_no' => $mobile,
                    'decision_maker_role'      => $na($request->decision_maker_role),
                    'school_board'             => '0',
                    'school_medium'            => '0',
                    'strength'                 => $na($request->strength),
                    'grade'                    => $na($request->grade),
                    'school_affiliation_no'    => $na($request->school_affiliation),
                    'school_registration_no'   => $na($request->school_registration_no),
                    'incorporation_date'       => $na($request->incorporation_date),
                    'gst_no'                   => $na($request->gst_no),
                    'board_erp'                => $na($request->onboardERP),
                    'address'                  => $na($request->address_2),
                    'landmark'                 => $na($request->landmark),
                    'bank_name'                => $na($request->bank_name),
                    'acc_holder_name'          => $na($request->acc_holder_name),
                    'branch_name'              => $na($request->branch_name),
                    'acc_no'                   => $na($request->acc_no),
                    'ifsc_code'                => $na($request->ifsc_code),
                    'customer_type'            => 'new',
                ]
            );

            // 5e. Digital content assignment + add-ons
            if (is_array($request->content_assignment)) {

                $addonLogEntries = [];

                DB::beginTransaction();

                try {
                    // SchoolAssignedDigitalContent::where('school_id', $user->id)->delete();
                    // For repeat orders keep old content also
                    if (!$existingSchoolOrder) {
                        SchoolAssignedDigitalContent::where('school_id', $user->id)->delete();
                    }
                    if ($isSkuMode) {
                        // ── SKU MODE: Use pre-resolved course data ────────────────
                        foreach ($resolvedSkuContent as $skuName => $resolved) {

                            // SchoolAssignedDigitalContent::create([
                            //     'school_id'  => $user->id,
                            //     'class_id'   => $resolved['class']->id,
                            //     'series_id'  => $resolved['series']->id,
                            //     'subject_id' => implode(',', $resolved['subject_ids']),
                            //     'created_by' => Auth::id() ?? 612,
                            // ]);
                            $existingContent = SchoolAssignedDigitalContent::where([
                                'school_id' => $user->id,
                                'class_id' => $resolved['class']->id,
                                'series_id' => $resolved['series']->id
                            ])->first();

                            $newSubjects = $resolved['subject_ids'];

                            if ($existingContent) {
                                $old = explode(',', $existingContent->subject_id);

                                $newSubjects = array_unique(
                                    array_merge($old, $newSubjects)
                                );
                            }

                            SchoolAssignedDigitalContent::updateOrCreate(
                                [
                                    'school_id' => $user->id,
                                    'class_id' => $resolved['class']->id,
                                    'series_id' => $resolved['series']->id
                                ],
                                [
                                    'subject_id' => implode(',', $newSubjects),
                                    'created_by' => Auth::id() ?? 612
                                ]
                            );
                            // Build add-on log entry
                            $hasAddons = !empty($resolved['add_ons']);

                            if ($hasAddons || $resolved['is_think_trail']) {
                                $logEntry = [
                                    'logged_at'  => now()->toIso8601String(),
                                    'user_id'    => $user->id,
                                    'class_name' => strtolower($resolved['class']->name),
                                    'series'     => strtolower($resolved['series']->name),
                                    'add_ons'    => $resolved['add_ons'],
                                ];

                                if ($resolved['is_think_trail']) {
                                    $logEntry['mittleance'] = $resolved['mittlens'];
                                    $logEntry['techlite']   = $resolved['techlite'];
                                }

                                $addonLogEntries[] = $logEntry;
                            }
                        }
                    }
                    // else {
                    //     // ── NAME-BASED MODE: Original logic ──────────────────────
                    //     foreach ($request->content_assignment as $content) {

                    //         $className = trim(strtolower($content['class_name'] ?? ''));

                    //         $class = Classes::whereRaw('LOWER(TRIM(name)) = ?', [$className])
                    //             ->where('is_active', 1)
                    //             ->first();

                    //         if (!$class) {
                    //             continue;
                    //         }

                    //         foreach ($content['series'] ?? [] as $seriesData) {
                    //             $seriesName = trim(strtolower($seriesData['series_id'] ?? ''));

                    //             $series = BookSeries::whereRaw('LOWER(TRIM(name)) = ?', [$seriesName])
                    //                 ->where('is_active', 1)
                    //                 ->first();

                    //             if (!$series) {
                    //                 continue;
                    //             }

                    //             $subjects = array_map(
                    //                 fn($item) => trim(strtolower($item)),
                    //                 $seriesData['subjects'] ?? []
                    //             );

                    //             $subjectIds = Subject::whereIn(
                    //                 DB::raw('LOWER(TRIM(name))'),
                    //                 $subjects
                    //             )
                    //                 ->where('is_active', 1)
                    //                 ->pluck('id')
                    //                 ->toArray();

                    //             if (empty($subjectIds)) {
                    //                 continue;
                    //             }

                    //             SchoolAssignedDigitalContent::create([
                    //                 'school_id'  => $user->id,
                    //                 'class_id'   => $class->id,
                    //                 'series_id'  => $series->id,
                    //                 'subject_id' => implode(',', $subjectIds),
                    //                 'created_by' => Auth::id() ?? 612,
                    //             ]);

                    //             $hasAddons    = array_key_exists('add_ons', $seriesData);
                    //             $isThinkTrail = strtolower(trim($seriesName)) == 'think trail';

                    //             if ($hasAddons || $isThinkTrail) {

                    //                 $addOns = is_array($seriesData['add_ons'] ?? null)
                    //                     ? array_values(array_filter($seriesData['add_ons']))
                    //                     : [];

                    //                 $logEntry = [
                    //                     'logged_at'  => now()->toIso8601String(),
                    //                     'user_id'    => $user->id,
                    //                     'class_name' => $className,
                    //                     'series'     => $seriesName,
                    //                     'add_ons'    => $addOns,
                    //                 ];

                    //                 if ($isThinkTrail) {
                    //                     $logEntry['mittleance'] = isset($seriesData['mittlens'])
                    //                         ? (int) $seriesData['mittlens']
                    //                         : null;
                    //                     $logEntry['techlite'] = isset($seriesData['techlite'])
                    //                         ? (int) $seriesData['techlite']
                    //                         : null;
                    //                 }

                    //                 $addonLogEntries[] = $logEntry;
                    //             }
                    //         }
                    //     }
                    // }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }

                // ── Save addon entries to DB ──────────────────────────────────────
                if (!empty($addonLogEntries)) {
                    \App\Models\CrmSchoolAddon::where('user_id', $user->id)->delete();

                    foreach ($addonLogEntries as $entry) {
                        \App\Models\CrmSchoolAddon::create([
                            'user_id'     => $entry['user_id'],
                            'class_name'  => $entry['class_name'],
                            'series_name' => $entry['series'],
                            'add_ons'     => $entry['add_ons'],
                            'mittleance'  => $entry['mittleance'] ?? null,
                            'techlite'    => $entry['techlite'] ?? null,
                            'created_by'  => Auth::id() ?? 612,
                        ]);
                    }
                }
            }

            // STEP 6 – Auto SMS to RM if email or mobile is missing
            $emailMissing  = in_array(trim((string) ($request->email ?? '')), ['', 'null', 'N/A', 'n/a', 'NA', 'na'], true);
            $mobileMissing = in_array(trim((string) ($mobile ?? '')), ['', 'null', 'N/A', 'n/a', 'NA', 'na'], true);

            // if (($emailMissing || $mobileMissing) && $rmId) {
            if (($mobileMissing) && $rmId) {

                $rm = User::find($rmId);

                if ($rm && !empty($rm->mobile_no)) {

                    $schoolName      = $na($request->name) ?? 'N/A';
                    $mobileNo        = $rm->mobile_no;
                    $supportMobileNo = Setting::where('field_name', 'rm_support_mobile_number')->value('field_value') ?? '8696259964';
                    $templateKey     = 'School Missing Detail';
                    $message         = "Dear RM, please provide Email and Mobile no. of {$schoolName} for ERP/ Digital Content onboarding immediately. Share Email and Mobile Number of the party at {$supportMobileNo} ASAP. Mittsure";

                    try {
                        sendSms($mobileNo, null, null, $templateKey, $message);

                        $this->logSms(
                            sentTo: $mobileNo,
                            templateKey: $templateKey,
                            message: $message,
                            triggeredBy: 'autoSmsToRM',
                            status: 'sent',
                            senderUserId: auth()->id() ?? 612,
                            relatedSchoolId: $school->id ?? null,
                            relatedRmId: $rm->id,
                        );
                    } catch (\Exception $smsEx) {
                        $this->logSms(
                            sentTo: $mobileNo,
                            templateKey: $templateKey,
                            message: $message,
                            triggeredBy: 'autoSmsToRM',
                            status: 'failed',
                            senderUserId: auth()->id() ?? 612,
                            relatedSchoolId: $school->id ?? null,
                            relatedRmId: $rm->id,
                            errorMessage: $smsEx->getMessage(),
                        );

                        \Log::error('userSaveFromApi auto SMS to RM failed: ' . $smsEx->getMessage());
                    }
                }
            }

            return response()->json([
                'status'         => true,
                'message'        => 'User saved successfully',
                'user_id'        => $user->id,
                'rm_id'          => $rmId,
                'distributor_id' => $distributorUserId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }



    private function isValidMobile(&$mobile)
    {
        if (empty($mobile)) {
            return false;
        }

        // Normalize
        $mobile = preg_replace('/[\s\-\(\)]+/', '', $mobile);
        $mobile = preg_replace('/^(?:\+91|91)/', '', $mobile);
        $mobile = preg_replace('/^0+/', '', $mobile);

        // Must be exactly 10 digits
        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            return false;
        }

        // Must start with 6-9
        if (!preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
            return false;
        }

        // Reject all same digits
        if (preg_match('/^(\d)\1{9}$/', $mobile)) {
            return false;
        }

        // Reject long repeating sequences
        if (preg_match('/(\d)\1{5,}/', $mobile)) {
            return false;
        }

        return true;
    }

    public function userUpdateFromApi(Request $request)
    {
        try {
            // ── Validation ──────────────────────────────────────────────────────
            $validator = Validator::make($request->all(), [
                'soid'      => 'required',
                'name'      => 'nullable|string|max:255',
                'email'     => 'nullable|email|max:255',
                'mobile_no' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $na = fn($v) => ($v === null || $v === '') ? null : $v;

            // ── Find user by SOID ────────────────────────────────────────────────
            $user = User::where('soid', $request->soid)->first();

            if (! $user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No user found with this SOID.',
                ], 404);
            }

            // ── Find school and check NOT yet activated ──────────────────────────
            $school = Schools::where('user_id', $user->id)->first();

            if (! $school) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No school record found for this SOID.',
                ], 404);
            }

            if ($school->is_verified_by_admin == 1) {
                return response()->json([
                    'status'  => false,
                    'message' => 'School is already activated. Details cannot be updated via API.',
                ], 403);
            }

            // ── Update users table (only fields sent in request) ─────────────────
            $userUpdates = [];

            if ($request->filled('name')) {
                $userUpdates['name'] = $na($request->name);
            }

            if ($request->filled('email')) {
                $userUpdates['email'] = $na($request->email);
            }

            if ($request->filled('email')) {
                $userUpdates['boid'] = $na($request->email);
            }

            if ($request->filled('mobile_no')) {
                $userUpdates['mobile_no'] = $na($request->mobile_no);
            }

            if (! empty($userUpdates)) {
                $user->update($userUpdates);
            }

            // ── Update schools table ─────────────────────────────────────────────
            if ($request->filled('name')) {
                $school->update(['name' => $na($request->name)]);
            }

            // ── Update user_additional_details table ─────────────────────────────
            if ($request->filled('mobile_no')) {
                UserAdditionalDetail::where('user_id', $user->id)
                    ->update(['decision_maker_mobile_no' => $na($request->mobile_no)]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Details updated successfully.',
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('userUpdateFromApi error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function logSms($sentTo, $templateKey, $message, $triggeredBy, $status = 'sent', $senderUserId = null, $relatedSchoolId = null, $relatedRmId = null, $errorMessage = null)
    {
        try {
            SmsLog::create([
                'sent_to'           => $sentTo,
                'template_key'      => $templateKey,
                'message'           => $message,
                'triggered_by'      => $triggeredBy,
                'status'            => $status,
                'sender_user_id'    => $senderUserId,
                'related_school_id' => $relatedSchoolId,
                'related_rm_id'     => $relatedRmId,
                'error_message'     => $errorMessage,
            ]);
        } catch (\Exception $e) {
            // Never let logging break the main flow
            \Log::error('logSms() failed: ' . $e->getMessage());
        }
    }
}
