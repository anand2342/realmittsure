<?php

namespace App\Services;

use App\Models\erpSync\SyncLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserRole;
use App\Models\Schools;
use App\Models\SchoolAssignedClass;
use App\Models\UserAdditionalDetail;
use App\Models\StudentDetails;
use Carbon\Carbon;

class DataSyncServiceOld
{
    public function syncUsers()
    {
        $dataSyncFrom = '2020-11-01 00:01:00';
        // $erpUsers = DB::connection('erp')->table('all_user')->where('status', 'active')->where('update_time', '>', $dataSyncFrom)->get();
        $erpUsers = DB::connection('erp')
            ->table('all_user')
            ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
            ->where('all_user.status', 'active')
            ->where('all_user.update_time', '>', $dataSyncFrom)
            ->limit(5)->get();
        dd($erpUsers);
        if ($erpUsers) {
            foreach ($erpUsers as $erpUser) {
                try {
                    // Determine the role based on user_type
                    $role = match ($erpUser->user_type) {
                        'admin' => 'school_admin',
                        'teacher' => 'school_teacher',
                        'student' => 'school_student',
                        default => null
                    };

                    if (!$role) continue;

                    // Create a request object with the ERP data
                    $request = new \Illuminate\Http\Request();

                    // Common fields for all user types
                    $request->merge([
                        'id' => $erpUser->id,
                        'name' => $erpUser->name,
                        'password' => $erpUser->password, // Note: You might want to decrypt this first
                        'validate_string' => $erpUser->password,
                    ]);

                    switch ($role) {
                        case 'school_admin':
                            $request->merge([
                                'email' => $erpUser->username . '@mittsure.com', // or use actual email if available
                                'mobile_no' => $erpUser->mobile,
                                'school_type' => $erpUser->type ?? 'foster',
                                'school_board' => 0,
                                'decision_maker_mobile_no' => $erpUser->contactNo,
                                'state' => $erpUser->state,
                                'district' => $erpUser->district,
                                'pincode' => '302001', // Default or extract from address
                                'name' => $erpUser->schoolName,
                                'address_1' => $erpUser->branch_name,
                                'class' => [1, 2, 3, 4, 5], // Default classes or extract from sch_grade
                                'school_medium' => str_contains($erpUser->board, 'EM') ? 'english' : 'hindi',
                                'academic_session_id' => 3,
                                'batch_id' => 3, // Default batch
                                'decision_maker' => $erpUser->contactName,
                                'decision_maker_role' => '',
                                'strength' => $erpUser->strength,
                                'assign_to' => 'admin', // Default
                                'lead' => 'erp_migration',
                                'username' => $erpUser->username,
                                'uniqueId' => $erpUser->schid,
                            ]);
                            break;

                        case 'school_teacher':
                            $request->merge([
                                'school_id' => $erpUser->schid,
                                'email' => $erpUser->username . '@mittsure.com',
                                'mobile_no' => $erpUser->mobile,
                                'gender' => 'other', // Default or extract from name prefix
                                'city' => $erpUser->district,
                                'state' => $erpUser->state,
                                'qualification' => 'graduate', // Default
                                'experience' => '1-3 years', // Default
                                'age' => 30, // Default or calculate from add_time
                                'classes_assigned' => 1,
                                'class' => [1, 2, 3], // Default or extract from sch_grade
                                'subject' => ['english', 'hindi', 'maths'], // Default
                                'dob' => $erpUser->add_time, // Using add_time as DOB if not available
                            ]);
                            break;

                        case 'school_student':
                            $request->merge([
                                'school_id' => $erpUser->schid,
                                'email' => $erpUser->username . '@mittsure.com',
                                'parent_mobile_no' => $erpUser->mobile,
                                'admission_no' => $erpUser->username,
                                'class' => 1, // Default or extract from sch_grade
                                'parent_name' => 'Parent of ' . $erpUser->name,
                                'admission_date' => $erpUser->add_time,
                                'dob' => $erpUser->add_time, // Using add_time as DOB if not available
                                'section' => 'A', // Default
                            ]);
                            break;
                    }

                    // Call your existing switch case logic
                    $this->createOrUpdateUser($request, $role);

                    // Log sync success
                    SyncLog::create([
                        'table'     => 'user',
                        'table_id'  => $erpUser->id,
                        'data'      => json_encode($erpUser),
                        'status'    => 'synced',
                        'synced_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // Log sync failure
                    SyncLog::create([
                        'table'     => 'user',
                        'table_id'  => $erpUser->id,
                        'data'      => json_encode($erpUser),
                        'status'    => 'synced',
                        'synced_at' => now(),
                    ]);
                }
            }
        }
    }

    public function createOrUpdateUser($request, $role_selected)
    {
        DB::beginTransaction();

        try {
            switch ($role_selected) {
                case 'school_admin':
                    $validated = $request->validate([
                        'assign_to'              => 'required',
                        'name'                   => 'required',
                        'school_board'           => 'required',
                        'email'                  => "required|email|unique:users,email,{$request->id}",
                        'school_medium'          => 'required',
                        'decision_maker_mobile_no' => "required|unique:users,mobile_no,{$request->id}",
                        'academic_session_id'    => 'required|exists:academic_sessions,id',
                        'batch_id'               => 'required|exists:batches,id',
                        'school_type'            => 'required',
                        'class'                  => 'required|array',
                        'pincode'                => ['required', 'regex:/^[1-9]{1}[0-9]{5}$/'],
                        'state'                  => 'required',
                        'district'               => 'required',
                        'password'               => 'required|min:8',
                    ]);

                    $user = User::updateOrCreate(
                        ['id' => $request->id],
                        [
                            'name'               => $validated['name'],
                            'username'           => $request->username ?? strtolower(str_replace(' ', '_', $validated['name'])),
                            'email'              => $validated['email'],
                            'mobile_no'          => $validated['decision_maker_mobile_no'],
                            'password'           => Hash::make($validated['password']),
                            'validate_string'    => $validated['password'],
                            'is_email_verified'  => 1,
                            'is_mobile_verified' => 1,
                            'created_by'         => auth()->id() ?? null,
                        ]
                    );

                    UserRole::updateOrCreate(
                        ['user_id' => $user->id],
                        ['role_slug' => $role_selected]
                    );

                    $school = Schools::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'unique_id'            => $request->uniqueId ?? 'SCH' . time(),
                            'school_type'          => $validated['school_type'],
                            'is_verified_by_admin' => 1,
                            'is_varified_by'       => auth()->id(),
                            'name'                => $validated['name'],
                            'address'             => $request->address_1 ?? '',
                            'city'                => $validated['district'],
                            'state'               => $validated['state'],
                            'postal_code'         => $validated['pincode'],
                            'academic_session_id' => $validated['academic_session_id'],
                            'batch_id'            => $validated['batch_id'],
                        ]
                    );

                    // Handle assigned classes
                    SchoolAssignedClass::where('school_id', $user->id)->delete();
                    foreach ($validated['class'] as $classId) {
                        SchoolAssignedClass::create([
                            'school_id' => $user->id,
                            'class_id'  => $classId,
                        ]);
                    }

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'role'                     => $role_selected,
                            'assign_to'                => $validated['assign_to'],
                            'school_id'                => $user->id,
                            'school_board'             => 0,
                            'school_medium'            => 0,
                            'decision_maker'           => $request->decision_maker ?? $validated['contactName'],
                            'decision_maker_mobile_no' => $validated['decision_maker_mobile_no'],
                            'strength'                 => $request->strength ?? 0,
                            'assign_distributor'       => $request->assign_distributor ?? null,
                        ]
                    );
                    break;

                case 'school_teacher':
                    $validated = $request->validate([
                        'school_id'     => 'required|exists:schools,user_id',
                        'name'          => 'required',
                        'gender'        => 'required|in:male,female,other',
                        'mobile_no'     => "required|numeric|digits:10|unique:users,mobile_no,{$request->id}",
                        'email'         => "required|email|unique:users,email,{$request->id}",
                        'city'          => 'required',
                        'state'         => 'required',
                        'qualification' => 'required',
                        'experience'    => 'required',
                        'age'          => 'required|numeric|max:100',
                        'password'      => 'required|min:8',
                        'subject'       => 'required_if:classes_assigned,1|array',
                        'class'         => 'required_if:classes_assigned,1|array',
                        'dob'           => 'required_if:classes_assigned,1|date',
                    ]);

                    $user = User::updateOrCreate(
                        ['id' => $request->id],
                        [
                            'name'              => $validated['name'],
                            'mobile_no'         => $validated['mobile_no'],
                            'email'             => $validated['email'],
                            'password'          => Hash::make($validated['password']),
                            'validate_string'   => $validated['password'],
                            'is_email_verified' => 1,
                            'is_mobile_verified' => 1,
                            'created_by'        => auth()->id() ?? $validated['school_id'],
                        ]
                    );

                    UserRole::updateOrCreate(
                        ['user_id' => $user->id],
                        ['role_slug' => $role_selected]
                    );

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'role'              => $role_selected,
                            'school_id'         => $validated['school_id'],
                            'gender'            => $validated['gender'],
                            'age'               => $validated['age'],
                            'city'              => $validated['city'],
                            'state'             => $validated['state'],
                            'qualification'     => $validated['qualification'],
                            'experience'        => $validated['experience'],
                            'dob'               => $validated['dob'] ?? null,
                            'assigned_classes'  => isset($validated['class']) ? implode(',', $validated['class']) : null,
                            'assigned_subjects' => isset($validated['subject']) ? implode(',', $validated['subject']) : null,
                        ]
                    );
                    break;

                case 'school_student':
                    $validated = $request->validate([
                        'school_id'        => 'required|exists:schools,user_id',
                        'name'             => 'required',
                        'admission_no'     => 'required',
                        'parent_mobile_no' => "required|numeric|unique:users,mobile_no,{$request->id}",
                        'email'            => "nullable|email|unique:users,email,{$request->id}",
                        'class'            => 'required|exists:classes,id',
                        'parent_name'      => 'required',
                        'admission_date'   => 'required|date',
                        'dob'             => 'required|date',
                        'password'         => 'required|min:8',
                    ]);

                    $user = User::updateOrCreate(
                        ['id' => $request->id],
                        [
                            'name'              => $validated['name'],
                            'mobile_no'         => $validated['parent_mobile_no'],
                            'email'             => $validated['email'] ?? null,
                            'password'          => Hash::make($validated['password']),
                            'validate_string'   => $validated['password'],
                            'is_email_verified' => $validated['email'] ? 1 : 0,
                            'is_mobile_verified' => 1,
                            'created_by'        => auth()->id() ?? $validated['school_id'],
                        ]
                    );

                    UserRole::updateOrCreate(
                        ['user_id' => $user->id],
                        ['role_slug' => $role_selected]
                    );

                    StudentDetails::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'school_id'   => $validated['school_id'],
                            'doj'         => Carbon::parse($validated['admission_date'])->format('Y-m-d'),
                            'dob'         => Carbon::parse($validated['dob'])->format('Y-m-d'),
                            'class'       => $validated['class'],
                            'parent_name' => $validated['parent_name'],
                            'section'     => $request->section ?? 'A',
                        ]
                    );

                    UserAdditionalDetail::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'role'         => $role_selected,
                            'school_id'    => $validated['school_id'],
                            'admission_no' => $validated['admission_no'],
                        ]
                    );
                    break;

                default:
                    throw new \InvalidArgumentException("Invalid role specified");
            }

            DB::commit();
            return ['success' => true, 'user' => $user];
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return ['success' => false, 'errors' => $e->validator->errors()];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
