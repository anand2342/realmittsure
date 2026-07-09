<?php

namespace App\Http\Controllers\admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\AccessCodeEmbibe;
use App\Models\Board;
use App\Models\Category;
use App\Models\City;
use App\Models\Course;
use App\Models\CrmSchoolAddon;
use App\Models\Grade;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\Section;
use App\Models\SmsLog;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\SubscriptionPurchase;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use App\Models\UserLog;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public $data = [];
    public function userCreate()
    {
        // $bulkRole                = ['b2c_student', 'school_teacher', 'school_admin', 'school_student', 'salesman'];
        // $this->data['bulkRoles'] = Role::where('is_active', 1)->whereIn('role_slug', $bulkRole)->pluck('role_name', 'role_slug');
        $this->data['roles']    = Role::where('is_active', 1)->pluck('role_name', 'role_slug');
        $this->data['users']    = User::where('status', 1)->pluck('name', 'id');
        $this->data['salesman'] = User::with('additionalDetails')
            ->where('status', 1)
            ->whereHas('userRole', function ($query) {
                $query->where('role_slug', 'salesman');
            })
            ->get()
            ->mapWithKeys(function ($user) {
                $employeeId = $user->additionalDetails->employee_id ?? 'N/A';
                return [$user->id => $employeeId . ' - ' . $user->name];
            });

        $this->data['distributors'] = User::with('additionalDetails')
            ->whereHas('userRole', function ($query) {
                $query->where('role_slug', 'distributors');
            })
            ->get()
            ->mapWithKeys(function ($user) {
                $employeeId = $user->additionalDetails->distributor_id ?? 'N/A';
                return [$user->id => $employeeId . ' - ' . $user->name];
            });
        $this->data['boards']     = Board::where('is_active', 1)->pluck('name', 'id');
        $this->data['mediums']    = Medium::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes']    = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $this->data['subjects']   = Subject::where('is_active', 1)->pluck('name', 'id');
        $this->data['cities']     = City::pluck('city', 'id');
        $this->data['states']     = State::pluck('name', 'id');
        $this->data['schoolList'] = Schools::where('school_role', 'parent')->pluck('name', 'user_id');
        $this->data['categories'] = Category::whereIn('id', ['35', '36', '37'])->pluck('name', 'id');
        $this->data['schools']    = Schools::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->pluck('name', 'user_id');
        $this->data['courseData'] = collect();

        $this->data['sections'] = Section::where('is_active', 1)->pluck('section_name', 'id');
        return view('admin.user.add', $this->data);
    }
    public function changeUserRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|string',
        ]);

        // Find main user
        $user = User::findOrFail($request->user_id);

        // Find related models
        $userDetail  = UserAdditionalDetail::where('user_id', $request->user_id)->first();
        $userRole    = UserRole::where('user_id', $request->user_id)->first();
        $studentData = StudentDetails::where('user_id', $request->user_id)->first();

        // Update role in related tables
        if ($userDetail) {
            $userDetail->role = $request->role;
            $userDetail->save();
        }

        if ($userRole) {
            $userRole->role_slug = $request->role;
            $userRole->save();
        }
        $userClassData = [
            'user_id'     => $request->user_id,
            'class_id'    => $studentData->class,
            'category_id' => '35', // category is olympiad by default
            'user_role'   => 'd2c_user',
        ];

        UserClass::create($userClassData);

        return response()->json(['message' => 'User role updated successfully.']);
    }

    public function userSave(Request $request)
    {
        // dd($request->all());
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }

        $role          = $request->input('role');
        $role_selected = $role ?? getUserRoles($request->id);
        switch ($role_selected) {
            case 'super_admin':
                $request->validate([]);

            case 'school_admin':
                // Base validation rules
                $rules = [
                    'assign_to'           => 'required',
                    'name'                => 'required',
                    'school_board'        => 'required',
                    'customer_type'       => 'required',
                    'email'               => "required|email|unique:users,email,$request->id",
                    'school_medium'       => 'required',
                    'academic_session_id' => 'required',
                    'batch_id'            => 'required',
                    'school_type'         => 'required',
                    'class'               => 'required',
                    'pincode'             => ['required', 'regex:/^[1-9]{1}[0-9]{5}$/'],
                    'state'               => 'required',
                    'district'            => 'required',
                    'password'            => 'required|min:8',
                ];

                // Conditional validation for mobile
                if ($request->school_type == 'demo') {
                    // No unique check
                    $rules['decision_maker_mobile_no'] = 'required';
                } else {
                    // Unique must apply
                    $rules['decision_maker_mobile_no'] = "required|unique:users,mobile_no,$request->id";
                }

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'username'           => $request->username,
                        'email'              => $request->email,
                        'mobile_no'          => $request->decision_maker_mobile_no ?? null,
                        'created_by'         => Auth::id(),
                        'password'           => Hash::make($request->password) ?? Hash::make('Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );
                // dd($user );
                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );
                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $school = Schools::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'user_id'              => $user->id,
                        'unique_id'            => $request->uniqueId,
                        'school_type'          => $request->school_type,
                        'school_role'          => $request->school_role,
                        'is_verified_by_admin' => 1,
                        'is_varified_by'       => Auth::id(),
                        'name'                 => $request->name,
                        'address'              => $request->address_1,
                        'city'                 => $request->district,
                        'state'                => $request->state,
                        'postal_code'          => $request->pincode,
                        'academic_session_id'  => $request->academic_session_id,
                        'batch_id'             => $request->batch_id,

                    ]
                );

                if (! $school) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $currentClasses  = SchoolAssignedClass::where('school_id', $school->user_id)->pluck('class_id')->toArray();
                $updatedClasses  = $request->class;
                $classesToDelete = array_diff($currentClasses, $updatedClasses);
                SchoolAssignedClass::whereIn('class_id', $classesToDelete)
                    ->where('school_id', $school->user_id)
                    ->delete();
                foreach ($updatedClasses as $value) {
                    $school_assigned_class = SchoolAssignedClass::updateOrCreate(
                        ['school_id' => $school->user_id, 'class_id' => $value],
                        [
                            'school_id' => $school->user_id,
                            'class_id'  => $value,
                        ]
                    );
                }

                if (! $school_assigned_class) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                // dd($request->all());
                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'                     => $role_selected,
                        'user_id'                  => $user->id,
                        'school_id'                => $user->id,
                        'assign_to'                => $request->assign_to,
                        'lead'                     => $request->lead,
                        'parent_school_name'       => $request->parent_school_name,
                        'city'                     => $request->district,
                        'state'                    => $request->state,
                        'website'                  => $request->website,
                        'decision_maker'           => $request->decision_maker,
                        'decision_maker_mobile_no' => $request->decision_maker_mobile_no,
                        'decision_maker_role'      => $request->decision_maker_role,
                        'school_board'             => $request->school_board,
                        'school_medium'            => $request->school_medium,
                        'strength'                 => $request->strength,
                        'grade'                    => $request->grade ?? null,
                        'school_affiliation_no'    => $request->school_affiliation ?? null,
                        'school_registration_no'   => $request->school_registration_no ?? null,
                        'incorporation_date'       => $request->incorporation_date,
                        'assign_distributor'       => $request->assign_distributor ?? null,
                        'gst_no'                   => $request->gst_no,
                        'board_erp'                => $request->onboardERP,
                        'address'                  => $request->address_2,
                        'landmark'                 => $request->landmark,
                        'bank_name'                => $request->bank_name,
                        'acc_holder_name'          => $request->acc_holder_name,
                        'branch_name'              => $request->branch_name,
                        'acc_no'                   => $request->acc_no,
                        'ifsc_code'                => $request->ifsc_code,
                        'customer_type'            => $request->customer_type,
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                if (! $request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                if ($request->verify == 'verifySchool') {
                    return redirect()->route('school.list')->with(['success' => config('constants.SCHOOL_VERIFY')]);
                }
                return redirect()->back()->with(['success' => $success]);

            case 'school_student':
                $request->validate([
                    'school_id'        => 'required',
                    'name'             => 'required',
                    // 'admission_no'     => 'required',
                    'parent_mobile_no' => "required|numeric|unique:users,mobile_no,$request->id",
                    'email'            => "nullable|email|unique:users,email,$request->id",
                    'class'            => 'required',
                    // 'parent_name'      => 'required',
                    // 'admission_date'   => 'required|date',
                    // 'dob'              => 'required|date',
                    'password'         => 'required|min:8',

                ]);
                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'email'              => $request->email ?? null,
                        'mobile_no'          => $request->parent_mobile_no,
                        'created_by'         => Auth::id(),
                        'password'           => Hash::make($request->password) ?? Hash::make('Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $admissionDate = Carbon::hasFormat($request->admission_date, 'm/d/Y') ? Carbon::createFromFormat('m/d/Y', $request->admission_date)->format('Y-m-d') : $request->admission_date;
                $dob           = Carbon::hasFormat($request->dob, 'm/d/Y') ? Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d') : $request->dob;

                $studentdetail = StudentDetails::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'user_id'     => $user->id,
                        'parent_id'   => Auth::id(),
                        'school_id'   => $request->school_id,
                        'doj'         => $admissionDate ?? null,
                        'dob'         => $dob ?? null,
                        'class'       => $request->class,
                        'parent_name' => $request->parent_name ?? null,
                        'section'     => $request->section ?? null,
                    ]
                );

                if (! $studentdetail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'         => $role_selected,
                        'school_id'    => $request->school_id,
                        'user_id'      => $user->id,
                        'admission_no' => $request->admission_no ?? '-',
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                if (! $request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                return redirect()->back()->with(['success' => $success]);

            case 'parent':
                $request->validate([]);

            case 'school_teacher':
                $request->validate([
                    'school_id' => 'required',
                    'name'      => 'required',
                    // 'gender'        => 'required',
                    'mobile_no' => "required|numeric|digits:10|unique:users,mobile_no,$request->id",
                    'email'     => "required|email|unique:users,email,$request->id",
                    // 'city'          => 'required',
                    // 'state'         => 'required',
                    // 'country'       => 'required',
                    // 'qualification' => 'required',
                    // 'experience'    => 'required',
                    // 'age'           => 'required|numeric|max:100',
                    'password'  => 'required|min:8',
                ]);

                if ($request->subject || $request->class) {
                    $request->validate([
                        'subject' => 'required',
                        'class'   => 'required',
                        // 'dob'     => 'required|date',
                    ]);
                } else {
                    $request->validate([
                        'classes_assigned' => 'required|boolean',
                    ]);
                }

                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'created_by'         => Auth::id(),
                        'password'           => Hash::make($request->password) ?? Hash::make('Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                if ($request->dob) {
                    $dob = Carbon::parse($request->dob)->format('Y-m-d');
                }

                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'              => $role_selected,
                        'school_id'         => $request->school_id,
                        'user_id'           => $user->id,
                        'gender'            => $request->gender ?? null,
                        'age'               => $request->age ?? null,
                        'address'           => $request->address ?? null,
                        'city'              => $request->city ?? null,
                        'state'             => $request->state ?? null,
                        // 'country'           => $request->country,
                        'qualification'     => $request->qualification ?? null,
                        'class_assigned'    => $request->classes_assigned,
                        'experience'        => $request->experience ?? null,
                        'dob'               => $dob ?? null,
                        'assigned_classes'  => $request->class ? implode(',', $request->class) : 'null',
                        'assigned_subjects' => $request->subject ? implode(',', $request->subject) : 'null',
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                if (! $request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                return redirect()->back()->with(['success' => $success]);

            case 'instructor':
                $request->validate([
                    'name'      => 'required',
                    'image'     => 'required|mimes:jpeg,jpg,png,gif',

                    'mobile_no' => "nullable|numeric|unique:users,mobile_no,$request->id",
                    'email'     => "nullable|email|unique:users,email,$request->id",
                    // 'address' => 'required',

                ]);
                if ($request->hasFile('image')) {
                    $existingImage = User::where('id', $request->id)->first();
                    if ($existingImage && Storage::disk('public')->exists('uploads/user/profile_image/' . $existingImage->image)) {
                        Storage::disk('public')->delete('uploads/user/profile_image/' . $existingImage->image);
                    }
                    $profileImage = $request->file('image');
                    $extension    = $profileImage->getClientOriginalExtension();
                    $fileName     = time() . '.' . $extension;
                    Storage::disk('public')->put('uploads/user/profile_image/' . $fileName, file_get_contents($profileImage));
                }
                $additionalImage = User::where('id', $request->id)->first();
                $user            = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'created_by'         => Auth::id(),
                        'image'              => $fileName !== null ? $fileName : ($additionalImage->image !== null ? $additionalImage->image : null),
                        'password'           => '',
                        'validate_string'    => '',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                if ($request->dob) {
                    // $dob = Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d');
                    $dob = Carbon::parse($request->dob)->format('Y-m-d');
                }
                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'          => $role_selected,
                        'user_id'       => $user->id,
                        'gender'        => $request->gender,
                        'age'           => $request->age,
                        'address'       => $request->address,
                        'city'          => $request->city,
                        'state'         => $request->state,
                        // 'country'       => $request->country,
                        'qualification' => $request->qualification,
                        'experience'    => $request->experience,
                        'designation'   => $request->designation,
                        'about'         => $request->about,
                        'facebook'      => $request->facebook,
                        'instagram'     => $request->instagram,
                        'linkedin'      => $request->linkedin,
                        'twitter'       => $request->twitter,
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                // if (!$request->id) {
                //     $sent = sendSms($user->mobile_no, '', $user);
                // }
                return redirect()->back()->with(['success' => $success]);

            case 'salesman':
                $request->validate([
                    'name'      => 'required',
                    'image'     => 'nullable |mimes:jpeg,jpg,png,gif|max:2048',
                    'mobile_no' => "required|numeric|unique:users,mobile_no,$request->id",
                    'email'     => "required|email|unique:users,email,$request->id",
                ]);
                if ($request->hasFile('image')) {
                    $existingImage = User::where('id', $request->id)->first();
                    if ($existingImage && Storage::disk('public')->exists('uploads/user/profile_image/' . $existingImage->image)) {
                        Storage::disk('public')->delete('uploads/user/profile_image/' . $existingImage->image);
                    }
                    $profileImage = $request->file('image');
                    $extension    = $profileImage->getClientOriginalExtension();
                    $fileName     = time() . '.' . $extension;
                    Storage::disk('public')->put('uploads/user/profile_image/' . $fileName, file_get_contents($profileImage));
                }
                $additionalImage = User::where('id', $request->id)->first();
                $user            = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'created_by'         => Auth::id(),
                        'image'              => $fileName ?? optional($additionalImage)->image ?? null,
                        'password'           => '',
                        'validate_string'    => '',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,

                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                if ($request->dob) {
                    // $dob = Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d');
                    $dob = Carbon::parse($request->dob)->format('Y-m-d');
                }
                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'        => $role_selected,
                        'user_id'     => $user->id,
                        'gender'      => $request->gender,
                        'age'         => $request->age,
                        'employee_id' => $request->employee_id,
                        'address'     => $request->address,
                        'city'        => $request->city,
                        'state'       => $request->state,
                        // 'country'       => $request->country,
                        'about'       => $request->about,
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // // Send SMS only if it's a new record
                // if (!$request->id) {
                //     $sent = sendSms($user->mobile_no, '', $user);
                // }
                return redirect()->back()->with(['success' => $success]);

            case 'distributors':
                $request->validate([
                    'name'      => 'required',
                    'image'     => 'nullable |mimes:jpeg,jpg,png,gif|max:2048',
                    'mobile_no' => "required|numeric|unique:users,mobile_no,$request->id",
                    'email'     => "required|email|unique:users,email,$request->id",
                ]);
                if ($request->hasFile('image')) {
                    $existingImage = User::where('id', $request->id)->first();
                    if ($existingImage && Storage::disk('public')->exists('uploads/user/profile_image/' . $existingImage->image)) {
                        Storage::disk('public')->delete('uploads/user/profile_image/' . $existingImage->image);
                    }
                    $profileImage = $request->file('image');
                    $extension    = $profileImage->getClientOriginalExtension();
                    $fileName     = time() . '.' . $extension;
                    Storage::disk('public')->put('uploads/user/profile_image/' . $fileName, file_get_contents($profileImage));
                }
                $additionalImage = User::where('id', $request->id)->first();
                $user            = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'created_by'         => Auth::id(),
                        'image'              => $fileName ?? optional($additionalImage)->image ?? null,
                        'password'           => '',
                        'validate_string'    => '',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,

                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                if ($request->dob) {
                    // $dob = Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d');
                    $dob = Carbon::parse($request->dob)->format('Y-m-d');
                }
                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'           => $role_selected,
                        'user_id'        => $user->id,
                        'gender'         => $request->gender,
                        'age'            => $request->age,
                        'distributor_id' => $request->distributor_id,
                        'address'        => $request->address,
                        'city'           => $request->city,
                        'state'          => $request->state,
                        // 'country'       => $request->country,
                        'about'          => $request->about,
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                // if (!$request->id) {
                //     $sent = sendSms($user->mobile_no, '', $user);
                // }
                return redirect()->back()->with(['success' => $success]);

            case 'leaders':
                $request->validate([
                    'name'        => 'required',
                    'designation' => 'required',
                    'about'       => 'required',
                    'image'       => 'nullable |mimes:jpeg,jpg,png,gif|max:2048',
                ]);
                if ($request->hasFile('image')) {
                    $existingImage = User::where('id', $request->id)->first();
                    if ($existingImage && Storage::disk('public')->exists('uploads/user/profile_image/' . $existingImage->image)) {
                        Storage::disk('public')->delete('uploads/user/profile_image/' . $existingImage->image);
                    }
                    $profileImage = $request->file('image');
                    $extension    = $profileImage->getClientOriginalExtension();
                    $fileName     = time() . '.' . $extension;
                    Storage::disk('public')->put('uploads/user/profile_image/' . $fileName, file_get_contents($profileImage));
                }
                $additionalImage = User::where('id', $request->id)->first();
                $user            = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'created_by'         => Auth::id(),
                        'image'              => $fileName ?? $additionalImage->image,
                        'password'           => Hash::make('Mitt@123'),
                        'validate_string'    => 'Mitt@123',
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );
                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'role'        => $role_selected,
                        'user_id'     => $user->id,
                        'gender'      => $request->gender,
                        'age'         => $request->age,
                        'designation' => $request->designation,
                        'about'       => $request->about,
                        'facebook'    => $request->facebook,
                        'instagram'   => $request->instagram,
                        'linkedin'    => $request->linkedin,
                        'twitter'     => $request->twitter,
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // // Send SMS only if it's a new record
                // if (!$request->id) {
                //     $sent = sendSms($user->mobile_no, '', $user);
                // }
                return redirect()->back()->with(['success' => $success]);

            case 'b2c_student':
                $request->validate([
                    'name'      => 'required|max:255',
                    'mobile_no' => "required|numeric|digits:10|unique:users,mobile_no,$request->id",
                    'email'     => "nullable|email|unique:users,email,$request->id",
                    'password'  => 'required|min:8',
                ]);

                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'password'           => Hash::make($request->password) ?? Hash::make('Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'created_by'         => Auth::id(),
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $studentdetail = StudentDetails::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'user_id' => $user->id,
                        'class'   => $request->class ?? null,
                    ]
                );
                if (! $studentdetail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(['user_id' => $request->id], ['role' => $role_selected, 'user_id' => $user->id]);

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // After creating $user and other related data like StudentDetails...

                if (! empty($request->course_id) && is_array($request->course_id)) {
                    $existing = SubscriptionPurchase::where('user_id', $user->id)->where('plan_id', 3)->first();

                    $newCourses     = Course::whereIn('id', $request->course_id)->get()->groupBy('category_id');
                    $newAcademic    = $newCourses[1] ?? collect();
                    $newNonAcademic = $newCourses[2] ?? collect();

                    $mergedCourses = [
                        'academic_courses'     => [],
                        'non_academic_courses' => [],
                    ];

                    if ($existing) {
                        $oldCourses                        = json_decode($existing->courses_json, true) ?? [];
                        $mergedCourses['academic_courses'] = collect($oldCourses['academic_courses'] ?? [])
                            ->merge($newAcademic)->unique('id')->values()->toArray();

                        $mergedCourses['non_academic_courses'] = collect($oldCourses['non_academic_courses'] ?? [])
                            ->merge($newNonAcademic)->unique('id')->values()->toArray();

                        $existing->update([
                            'courses_json' => json_encode($mergedCourses),
                            'end_date'     => now()->addYear(), // optional update
                        ]);
                    } else {
                        $mergedCourses['academic_courses']     = $newAcademic->values()->toArray();
                        $mergedCourses['non_academic_courses'] = $newNonAcademic->values()->toArray();

                        $planJson = [
                            'plan_id'     => 3,
                            'name'        => 'Admin Assigned Plan',
                            'plan_type'   => 'custom',
                            'currency'    => 'INR',
                            'description' => 'Courses assigned by admin',
                            'start_date'  => now(),
                            'end_date'    => now()->addYear(),
                        ];

                        SubscriptionPurchase::create([
                            'user_id'        => $user->id,
                            'plan_id'        => 3,
                            'start_date'     => now(),
                            'end_date'       => now()->addYear(),
                            'plan_json'      => json_encode($planJson),
                            'courses_json'   => json_encode($mergedCourses),
                            'transaction_id' => 'assigned_by_admin',
                            'status'         => 'active',
                        ]);
                    }
                }

                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                if (! $request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                return redirect()->back()->with(['success' => $success]);
            case 'd2c_user':
                $request->validate([
                    'name'      => 'required|max:255',
                    'mobile_no' => "required|numeric|digits:10|unique:users,mobile_no,$request->id",
                    'email'     => "nullable|email|unique:users,email,$request->id",
                    'class'     => "required",
                    // 'password' => 'required|min:8',
                ]);
                $selectedOptions = is_array($request->option_field) ? $request->option_field : [$request->option_field];

                if (in_array('A', $selectedOptions)) {
                    $plainPassword = $request->password ?? 'Mitt@123';
                    $password      = Hash::make($plainPassword);
                    $canLogin      = 1;
                } else {
                    $plainPassword = null;
                    $password      = null;
                    $canLogin      = 0;
                }

                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'password'           => $password,
                        'validate_string'    => $plainPassword,
                        'created_by'         => Auth::id(),
                        'category_id'        => $request->category,
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                        'user_type'          => 'd2c_user',
                        'source'             => 'd2c_user_register_from_admin',
                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                $userClassData = [
                    'user_id'     => $user->id,
                    'class_id'    => $request->class,
                    'category_id' => $request->category,
                    'user_role'   => 'd2c_user',
                ];

                UserClass::create($userClassData);

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $optionAData = null;
                $optionBData = null;

                if (in_array('A', $selectedOptions)) {
                    $optionAData = json_encode([
                        'mathematics' => $request->a_checkbox1 ? true : false,
                        'science'     => $request->a_checkbox2 ? true : false,
                    ]);
                }

                if (in_array('B', $selectedOptions)) {
                    $optionBData = json_encode([
                        'mathematics' => $request->b_checkbox1 ? true : false,
                        'science'     => $request->b_checkbox2 ? true : false,
                    ]);
                }
                $schoolSelected = Schools::where('name', $request->schoolName)->first();

                if ($schoolSelected) {
                    $school = $schoolSelected->name;
                } else {
                    $school = $request->schoolName;
                }
                // Save/update student details
                $studentdetail = StudentDetails::updateOrCreate(
                    [
                        'user_id' => $request->id,
                    ],
                    [
                        'user_id'              => $user->id,
                        'school_id'            => null,
                        'parent_id'            => Auth::id(),
                        'class'                => $request->class,
                        'parent_name'          => $request->parent_name,
                        'section'              => $request->section,
                        'roll_number'          => $request->roll_number,
                        'd2c_user_school_name' => $school,
                        'school_pincode'       => $request->school_pincode,
                        'school_state'         => $request->school_state,
                        'school_district'      => $request->school_district,
                        'school_address_1'     => $request->school_address_1,
                        'option_a'             => $optionAData,
                        'option_b'             => $optionBData,
                    ]
                );

                if (! $studentdetail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(['user_id' => $request->id], ['role' => $role_selected, 'user_id' => $user->id, 'school_id' => $request->school_id]);

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                if (! $request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                return redirect()->back()->with(['success' => $success]);

            default:
                $request->validate([
                    'name'      => 'required|max:255',
                    'mobile_no' => "required|numeric|digits:10|unique:users,mobile_no,$request->id",
                    'email'     => "nullable|email|unique:users,email,$request->id",
                    'password'  => 'required|min:8',
                ]);

                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'               => $request->name,
                        'mobile_no'          => $request->mobile_no,
                        'email'              => $request->email,
                        'password'           => Hash::make($request->password) ?? Hash::make('Mitt@123'),
                        'validate_string'    => $request->password ?? 'Mitt@123',
                        'created_by'         => Auth::id(),
                        'is_email_verified'  => 1,
                        'is_mobile_verified' => 1,
                    ]
                );

                if (! $user) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                $userrole = UserRole::updateOrCreate(
                    ['user_id' => $user->id],
                    ['role_slug' => $role_selected]
                );

                if (! $userrole) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                $user_addtional_detail = UserAdditionalDetail::updateOrCreate(['user_id' => $request->id], ['role' => $role_selected, 'user_id' => $user->id]);
                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                // Send SMS only if it's a new record
                if (! $request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                return redirect()->back()->with(['success' => $success]);
        }
    }

    public function userSendmail($user)
    {
        if ($user) {
            $user       = User::find($user->id);
            $templateId = 30;
            $data       = [
                'NAME'          => $user->name,
                'EMAIL'         => $user->email,
                'MOBILE_NUMBER' => $user->mobile_no,
                'USERNAME'      => $user->username,
                'PASSWORD'      => $user->vallidate_string,
            ];
            if ($user) {
                sendEmail($templateId, $user->email, $data);
            }
        }
    }

    public function userShow(Request $request)
    {
        $activeTab                = $request->input('active_tab', 'Active');
        $this->data['categories'] = Category::where('status', 1)->where('parent_id', 1)
            ->whereNotIn('slug', ['academic-digital-content', 'academic_activities'])
            ->pluck('name', 'slug')
            ->toArray();
        $this->data['categories'] = Category::where('status', 1)->where('parent_id', 1)
            ->whereNotIn('slug', ['academic-digital-content', 'academic_activities'])
            ->pluck('name', 'slug')
            ->toArray();
        $this->data['activeTab'] = $activeTab;

        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
        $activeUsers    = User::with(['userRole', 'userClass', 'schoolDetails', 'additionalDetails.school', 'studentDetails', 'category', 'subscriptions'])->where('status', 1)->where(function ($query) {
            $query->whereNull('email')
                ->orWhere('email', 'not like', '%@guest.com');
        })
            ->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->filled('mobile_no'), fn($q) => $q->where('mobile_no', 'like', '%' . $request->mobile_no . '%'))
            ->when($request->filled('email'), fn($q) => $q->where('email', 'like', '%' . $request->email . '%'))
            ->when($request->filled('role'), fn($q) => $q->whereHas('role', fn($q) => $q->where('role_slug', $request->role)))
            ->when($request->filled('school_name'), fn($q) => $q->whereHas('additionalDetails.school', fn($q) => $q->where('name', 'like', '%' . $request->school_name . '%')))
            ->when($request->filled('d2c_user_school_name'), fn($q) => $q->whereHas('studentDetails', fn($q) => $q->where('d2c_user_school_name', 'like', '%' . $request->d2c_school_name . '%')))
            ->when($request->filled('category'), fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'like', '%' . $request->category . '%')))
            ->orderBy('created_at', 'DESC');

        if ($request->input('role') == 'school_admin' || ! $request->filled('role')) {
            $activeUsers->whereHas('schoolDetails', function ($q) use ($request) {
                if (! $request->filled('role') || $request->input('role') == 'school_admin') {
                    $q->where('is_verified_by_admin', 1);
                }
            });
        }
        if ($request->input('role') == 'school_admin' && $request->input('school_type')) {
            $activeUsers->whereHas('schoolDetails', function ($q) use ($request) {
                if (! $request->filled('role') || $request->input('role') == 'school_admin') {
                    $q->where('school_type', $request->input('school_type'));
                }
            });
        }

        $this->data['activeUsers'] = $activeUsers->paginate($perPageRecords);
        $this->data['activeUsers']->getCollection()->transform(function ($user) {
            $user->assigned_courses = $this->getCommaSeparatedCourses($user);
            return $user;
        });

        $inActiveUsers = User::with(['userRole', 'userClass', 'schoolDetails', 'additionalDetails.school', 'studentDetails', 'category', 'subscriptions'])->where('status', 0)
            ->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->filled('mobile_no'), fn($q) => $q->where('mobile_no', 'like', '%' . $request->mobile_no . '%'))
            ->when($request->filled('email'), fn($q) => $q->where('email', 'like', '%' . $request->email . '%'))
            ->when($request->filled('role'), fn($q) => $q->whereHas('role', fn($q) => $q->where('role_slug', $request->role)))
            ->when($request->filled('school_name'), fn($q) => $q->whereHas('additionalDetails.school', fn($q) => $q->where('name', 'like', '%' . $request->school_name . '%')))
            ->when($request->filled('d2c_user_school_name'), fn($q) => $q->whereHas('studentDetails', fn($q) => $q->where('d2c_user_school_name', 'like', '%' . $request->d2c_school_name . '%')))
            ->when($request->filled('category'), fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'like', '%' . $request->category . '%')))
            ->orderBy('created_at', 'DESC');

        if ($request->input('role') == 'school_admin' || ! $request->filled('role')) {
            $inActiveUsers->whereHas('schoolDetails', function ($q) use ($request) {
                if (! $request->filled('role') || $request->input('role') == 'school_admin') {
                    $q->where('is_verified_by_admin', 1);
                }
            });
        }
        if ($request->input('role') == 'school_admin' && $request->input('school_type')) {
            $inActiveUsers->whereHas('schoolDetails', function ($q) use ($request) {
                if (! $request->filled('role') || $request->input('role') == 'school_admin') {
                    $q->where('school_type', $request->input('school_type'));
                }
            });
        }

        $this->data['inActiveUsers'] = $inActiveUsers->paginate($perPageRecords);
        // Append comma separated course names for inactive users
        $this->data['inActiveUsers']->getCollection()->transform(function ($user) {
            $user->assigned_courses = $this->getCommaSeparatedCourses($user);
            return $user;
        });

        $iosGuestUsers = User::with(['userRole', 'userClass', 'schoolDetails', 'additionalDetails.school', 'studentDetails', 'category', 'subscriptions'])->where('status', 1)->whereNotNull('email') // make sure email exists
            ->where('email', 'like', '%@guest.com')                                                                                                                                                       // match guest users
            ->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->filled('mobile_no'), fn($q) => $q->where('mobile_no', 'like', '%' . $request->mobile_no . '%'))
            ->when($request->filled('email'), fn($q) => $q->where('email', 'like', '%' . $request->email . '%'))
            ->when($request->filled('role'), fn($q) => $q->whereHas('role', fn($q) => $q->where('role_slug', $request->role)))
            ->when($request->filled('school_name'), fn($q) => $q->whereHas('additionalDetails.school', fn($q) => $q->where('name', 'like', '%' . $request->school_name . '%')))
            ->when($request->filled('category'), fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'like', '%' . $request->category . '%')))
            ->orderBy('created_at', 'DESC');

        if ($request->input('role') == 'school_admin' || ! $request->filled('role')) {
            $iosGuestUsers->whereHas('schoolDetails', function ($q) use ($request) {
                if (! $request->filled('role') || $request->input('role') == 'school_admin') {
                    $q->where('is_verified_by_admin', 1);
                }
            });
        }
        if ($request->input('role') == 'school_admin' && $request->input('school_type')) {
            $iosGuestUsers->whereHas('schoolDetails', function ($q) use ($request) {
                if (! $request->filled('role') || $request->input('role') == 'school_admin') {
                    $q->where('school_type', $request->input('school_type'));
                }
            });
        }

        $this->data['iosGuestUsers'] = $iosGuestUsers->paginate($perPageRecords);
        // Append comma separated course names for iosGuestUsers users
        $this->data['iosGuestUsers']->getCollection()->transform(function ($user) {
            $user->assigned_courses = $this->getCommaSeparatedCourses($user);
            return $user;
        });
        // Pass role for optional filters
        $this->data['role'] = $request->input('role');

        // From CRM Schools (is_from_crm = 1 AND unverified)

        // Common search filters closure
        $applyFilters = function ($query) use ($request) {
            if ($request->filled('school_name')) {
                $query->where('name', 'like', '%' . $request->school_name . '%');
            }
            if ($request->filled('unique_id')) {
                $query->where('unique_id', 'like', '%' . $request->unique_id . '%');
            }
            if ($request->filled('state_id')) {
                $query->where('state', $request->state_id);
            }
            if ($request->filled('district_id')) {
                $query->where('city', $request->district_id);
            }
        };

        $crmQuery = Schools::with('user', 'userSchool', 'user_additional_details')
            ->where('is_from_crm', 1)
            ->where('is_verified_by_admin', 0)
            ->whereHas('assignedDigitalContents'); // ← only schools WITH series

        $applyFilters($crmQuery);

        $this->data['crmSchools'] = $crmQuery->orderBy('id', 'DESC')
            ->paginate($perPageRecords, ['*'], 'page');

        $appendParams = $request->except(['page']);
        $this->data['crmSchools']->appends($appendParams);

        return view('admin.user.index', $this->data);
    }

    private function getCommaSeparatedCourses(User $user)
    {
        $subscriptions = $user->subscriptions;
        $courseNames   = collect();

        foreach ($subscriptions as $subscription) {
            $coursesJson = json_decode($subscription->courses_json, true);
            if (is_array($coursesJson)) {
                $academic    = $coursesJson['academic_courses'] ?? [];
                $nonAcademic = $coursesJson['non_academic_courses'] ?? [];

                $allCourses = array_merge($academic, $nonAcademic);

                foreach ($allCourses as $course) {
                    $courseNames->push($course['course_name'] ?? null);
                }
            }
        }
        // Filter nulls and unique course names, then join with commas
        return $courseNames->filter()->unique()->implode(', ');
    }

    public function viewUser($id)
    {
        $school_id              = Schools::where('user_id', $id)->pluck('id');
        $this->data['roles']    = Role::where('is_active', 1)->pluck('role_name', 'role_slug');
        $this->data['users']    = User::pluck('name');
        $this->data['salesman'] = User::whereHas('userRole', function ($query) {
            $query->where('role_slug', 'salesman');
        })->pluck('name', 'id');
        $this->data['distributors'] = User::whereHas('userRole', function ($query) {
            $query->where('role_slug', 'distributors');
        })->pluck('name', 'id');
        $this->data['boards']                = Board::where('is_active', 1)->pluck('name');
        $this->data['mediums']               = Medium::where('is_active', 1)->pluck('name');
        $this->data['school_assigned_class'] = SchoolAssignedClass::where('school_id', $school_id[0] ?? null)->pluck('class_id')->toArray();
        $this->data['cities']                = City::all();
        $this->data['states']                = State::pluck('name', 'id');
        $this->data['classes']               = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $this->data['data']                  = User::where('id', $id)->with(['userRole.role', 'userAdditionalDetail', 'studentDetails'])->first();
        $this->data['schoolList']            = Schools::pluck('name', 'user_id');
        $this->data['sections']              = Section::where('is_active', 1)->pluck('section_name', 'id');
        $this->data['courseData']            = collect();

        return view('admin.user.user-profile', $this->data)->with('viewOnly', true);
    }

    public function editUser($id, $verify = null)
    {
        $school_id              = Schools::where('user_id', $id)->pluck('id');
        $user                   = User::find($id);
        $this->data['roles']    = Role::where('is_active', 1)->pluck('role_name', 'role_slug');
        $this->data['users']    = User::pluck('name');
        $this->data['salesman'] = User::with('additionalDetails')
            ->where('status', 1)
            ->whereHas('userRole', function ($query) {
                $query->where('role_slug', 'salesman');
            })
            ->get()
            ->mapWithKeys(function ($user) {
                $employeeId = $user->additionalDetails->employee_id ?? 'N/A';
                return [$user->id => $employeeId . ' - ' . $user->name];
            });

        $this->data['distributors'] = User::with('additionalDetails')
            ->whereHas('userRole', function ($query) {
                $query->where('role_slug', 'distributors');
            })
            ->get()
            ->mapWithKeys(function ($user) {
                $employeeId = $user->additionalDetails->distributor_id ?? 'N/A';
                return [$user->id => $employeeId . ' - ' . $user->name];
            });
        $this->data['boards']                = Board::where('is_active', 1)->pluck('name', 'id');
        $this->data['mediums']               = Medium::where('is_active', 1)->pluck('name', 'id');
        $this->data['school_assigned_class'] = SchoolAssignedClass::where('school_id', $school_id[0] ?? null)->pluck('class_id')->toArray();
        $this->data['cities']                = City::all();
        $this->data['states']                = State::pluck('name', 'id');
        $this->data['classes']               = SchoolClass::pluck('name', 'id');
        $this->data['subjects']              = Subject::where('is_active', 1)->pluck('name', 'id');

        $this->data['data']             = User::where('id', $id)->with(['userRole', 'userClass', 'userAdditionalDetail', 'studentDetails', 'schoolDetails'])->first();
        $this->data['schoolList']       = Schools::pluck('name', 'user_id');
        $this->data['sections']         = Section::where('is_active', 1)->pluck('section_name', 'id');
        $this->data['assigned_courses'] = $subscriptions = $user->subscriptions;
        $courseData                     = collect();

        foreach ($subscriptions as $subscription) {
            $coursesJson = json_decode($subscription->courses_json, true);
            if (is_array($coursesJson)) {
                $academic    = $coursesJson['academic_courses'] ?? [];
                $nonAcademic = $coursesJson['non_academic_courses'] ?? [];

                $allCourses = array_merge($academic, $nonAcademic);

                foreach ($allCourses as $course) {
                    if (isset($course['id'], $course['course_name'])) {
                        $courseData->push([
                            'id'   => $course['id'],
                            'name' => $course['course_name'],
                        ]);
                    }
                }
            }
        }

        $this->data['courseData'] = $courseData;
        $this->data['verify']     = $verify ?? null;

        $this->data['schools'] = Schools::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->pluck('name', 'user_id');
        $this->data['academicSessions'] = AcademicSession::select('id', 'name')
            ->where('is_active', 1)
            ->get()
            ->unique('name')
            ->pluck('name', 'id');

        return view('admin.user.add', $this->data);
    }

    public function deleteCourse($userId, $courseId)
    {
        // Find the subscription record first
        $subscription = SubscriptionPurchase::where('user_id', $userId)->first();

        if (! $subscription) {
            return redirect()->back()->with(['error' => 'Subscription not found']);
        }

        // Decode the courses_json
        $coursesJson = json_decode($subscription->courses_json, true) ?? [];

        // Filter out the course from academic courses
        if (isset($coursesJson['academic_courses'])) {
            $coursesJson['academic_courses'] = array_filter(
                $coursesJson['academic_courses'],
                fn($course) => ($course['id'] ?? null) != $courseId
            );
            $coursesJson['academic_courses'] = array_values($coursesJson['academic_courses']);
        }

        // Filter out the course from non-academic courses
        if (isset($coursesJson['non_academic_courses'])) {
            $coursesJson['non_academic_courses'] = array_filter(
                $coursesJson['non_academic_courses'],
                fn($course) => ($course['id'] ?? null) != $courseId
            );
            $coursesJson['non_academic_courses'] = array_values($coursesJson['non_academic_courses']);
        }

        // Update the subscription record
        $subscription->courses_json = json_encode($coursesJson);
        $subscription->save();

        return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }

    public function userDelete($id)
    {
        $data = User::find($id);

        if ($data) {
            if ($school_data = Schools::where('user_id', $data->id)->first()) {
                $school_data->delete();
            }
            if ($userAdditionalData = UserAdditionalDetail::where('user_id', $data->id)->first()) {
                $userAdditionalData->delete();
            }
            if ($studentData = StudentDetails::where('user_id', $data->id)->first()) {
                $studentData->delete();
            }
            if ($userRoleData = UserRole::where('user_id', $data->id)->first()) {
                $userRoleData->delete();
            }
            $data->delete();
        }
        return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }

    public function userActiveInactive($id, Request $request)
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->back()->with(['error' => 'User not found.']);
        }

        // Determine new status (toggle)
        $newStatus  = ($user->status == 1) ? 0 : 1;
        $inactiveAt = ($newStatus == 0) ? now() : null; // Set inactive timestamp if deactivating

        // Log the update
        $userLog = UserLog::create([
            'user_id'    => $user->id,
            'updated_by' => Auth::id(),
            'title'      => 'User Updated By Super Admin',
            'uri'        => $request->getBaseUrl(),
            'action_as'  => ($newStatus == 0) ? 'user_inactive' : 'user_active',
            'json_data'  => $user->toJson(),
            'log_type'   => 'user_update',
            'log_date'   => now(),
        ]);

        // Update main user status
        $user->status      = $newStatus;
        $user->inactive_at = $inactiveAt;
        $user->save();

        // Fetch related users (school_teacher & school_student) with the same `inactive_at` timestamp
        $relatedUsers = User::whereIn('id', function ($query) use ($user) {
            $query->select('user_id')
                ->from('user_additional_details')
                ->where('user_id', $user->id)
                ->whereIn('role', ['school_teacher', 'school_student']);
        })->where('inactive_at', $user->inactive_at) // Only update users with the same inactive timestamp
            ->get();

        foreach ($relatedUsers as $relatedUser) {
            $relatedUser->status      = $newStatus;
            $relatedUser->inactive_at = $inactiveAt;
            $relatedUser->save();
        }

        return redirect()->back()->with(['success' => config('constants.FLASH_STATUS')]);
    }

    public function downloadSampleFile($roleKey)
    {
        $filePath = public_path("admin/sample-files/{$roleKey}-sample-file.xlsx");

        if (! file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($filePath, "{$roleKey}-sample-file.xlsx");
    }

    public function assignDigitalContent($id)
    {
        $this->data['id'] = $id;
        return view('admin.user.school_assigned_digital_content', $this->data);
    }

    public function assignDigitalContentSave(Request $request)
    {
        // dd($request->all());
        $schoolId     = $request->school_id;
        $existingData = SchoolAssignedDigitalContent::where('school_id', $schoolId)->get();
        if ($existingData->isNotEmpty()) {
            SchoolAssignedDigitalContent::where('school_id', $schoolId)->delete();
        }

        foreach ($request->class_id as $index => $class_id) {
            // if (!empty($request->series_id[$index])) {
            if (! empty(array_filter($request->series_id[$index]))) {
                foreach ($request->series_id[$index] as $seriesIndex => $series_id) {
                    // Ensure subjects exist for the selected series
                    $subjects = isset($request->subject[$index][$seriesIndex])
                        ? implode(',', (array) $request->subject[$index][$seriesIndex])
                        : null;

                    // Save data for each series under the same class
                    SchoolAssignedDigitalContent::updateOrCreate(
                        [
                            'id'        => $request->id[$index][$seriesIndex] ?? null, // Ensure unique per series
                            'school_id' => $schoolId,

                        ],
                        [
                            'class_id'   => $class_id,
                            'series_id'  => $series_id,
                            'subject_id' => $subjects,
                            'created_by' => Auth::id(),
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }

    public function schoolAssignedClassSave(Request $request)
    {
        $schooId        = $request->id;
        $currentClasses = SchoolAssignedClass::where('school_id', $schooId)->pluck('class_id')->toArray();
        $updatedClasses = $request->class ?? [];

        // Classes to delete
        $classesToDelete = array_diff($currentClasses, $updatedClasses);

        // Delete from SchoolAssignedClass table
        SchoolAssignedClass::whereIn('class_id', $classesToDelete)
            ->where('school_id', $schooId)
            ->delete();

        // Delete from SchoolAssignedDigitalContent table (when class is removed)
        SchoolAssignedDigitalContent::where('school_id', $schooId)
            ->whereIn('class_id', $classesToDelete)
            ->delete();

        // Insert or update the remaining classes in SchoolAssignedClass
        foreach ($updatedClasses as $value) {
            SchoolAssignedClass::updateOrCreate(
                ['school_id' => $schooId, 'class_id' => $value],
                [
                    'school_id' => $schooId,
                    'class_id'  => $value,
                ]
            );
        }

        return redirect()->back()->with(['success' => config('constants.FLASH_REC_UPDATE_1')]);
    }

    public function dowanloadUsersData(Request $request)
    {
        $roleSlug = $request->roleSlug;
        $now      = now();
        $filters  = $request->except('roleSlug');
        $fileName = "{$roleSlug}s-{$now}.xlsx";

        // $file = Excel::raw(new UsersExport($roleSlug), ExcelFormat::XLSX);
        $file = Excel::raw(new UsersExport($roleSlug, $filters), ExcelFormat::XLSX);

        return Response::make($file, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
            'Pragma'        => 'public',
        ]);
    }

    public function sendSmsUser(Request $request)
    {
        $userIds = explode(',', $request->ids);
        foreach ($userIds as $item) {
            $user = User::where('id', $item)->first();
            if ($user) {
                // dd('1');
                $otp = null;
                sendSms($user->mobile_no, $otp, $user);
            }
        }
        return redirect()->back()->with(['success' => config('constants.SMS_SUCCESS')]);
    }

    public function crmVerifySchool($id)
    {
        try {
            $school = Schools::with('user')->findOrFail($id);

            // Mark school as verified
            $school->update([
                'is_verified_by_admin' => 1,
                'is_varified_by'       => Auth::id(),
            ]);

            $user = $school->user;

            if ($user) {

                $user->update([
                    'status'      => 1,
                    'is_verified' => 1,
                ]);

                $hasMobile = ! empty($user->mobile_no);

                // if ($hasEmail) {
                //     // Send verification email
                //     try {
                //         \Mail::send('emails.school_verified', [
                //             'user'   => $user,
                //             'school' => $school,
                //         ], function ($message) use ($user) {
                //             $message->to($user->email, $user->name)
                //                 ->subject('Your School Has Been Verified – MittuSre Support');
                //         });
                //     } catch (\Exception $mailEx) {
                //         \Log::error('School verification email failed: ' . $mailEx->getMessage());
                //     }
                // }
                /*
            |--------------------------------------------------------------------------
            | 1. FIRST SMS (LOGIN DETAILS)
            |--------------------------------------------------------------------------
            */
                if ($hasMobile) {
                    try {
                        $templateKey = 'Mittlearn Login OTP';

                        $message = "Welcome to Mittlearn! Your access detail is: User ID : {$user->mobile_no} Password : Mitt@123 https://mittlearn.com/login Thanks, Mittsure";

                        sendSms($user->mobile_no, null, null, $templateKey, $message);

                        $this->logSms(
                            sentTo: $user->mobile_no,
                            templateKey: $templateKey,
                            message: $message,
                            triggeredBy: 'crmVerifySchool',
                            status: 'sent',
                            senderUserId: auth()->id(),
                            relatedSchoolId: $school->user_id,
                        );
                    } catch (\Exception $smsEx) {

                        $this->logSms(
                            sentTo: $user->mobile_no,
                            templateKey: $templateKey,
                            message: $message,
                            triggeredBy: 'crmVerifySchool',
                            status: 'failed',
                            senderUserId: auth()->id(),
                            relatedSchoolId: $school->user_id,
                            errorMessage: $smsEx->getMessage(),
                        );

                        \Log::error('Login SMS failed: ' . $smsEx->getMessage());
                    }
                }

                //  2. AUTO-ASSIGN ACCESS CODES + ADDON SMS

                if ($hasMobile) {
                    try {
                        $addon = CrmSchoolAddon::where('user_id', $school->user_id)
                            ->where(fn($q) => $q->where('mittleance', '>', 0)->orWhere('techlite', '>', 0))
                            ->first();

                        if ($addon) {
                            $mittRequired  = (int) ($addon->mittleance ?? 0);
                            $techlRequired = (int) ($addon->techlite   ?? 0);

                            if ($mittRequired > 0 || $techlRequired > 0) {

                                // ── Auto-assign codes ────────────────────────────────
                                $assignResult = $this->autoAssignLensCodes($school, $user);

                                if ($assignResult['success']) {

                                    // ── Send SMS only after successful assignment ────
                                    $mittAssigned  = $assignResult['mitt_assigned'];
                                    $teachAssigned = $assignResult['teach_assigned'];

                                    if ($mittAssigned > 0 && $teachAssigned > 0) {
                                        $templateId  = '1707177547118656752';
                                        $templateKey = 'Welcome teachlite and mittlens';
                                        $message     = "Congratulations! Your Mittlens and Teachlite licenses are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Mittlens and Teachlite licenses offered with your purchase. Mittsure";
                                    } elseif ($teachAssigned > 0) {
                                        $templateId  = '1707177547112734409';
                                        $templateKey = 'Welcome Teachlite';
                                        $message     = "Congratulations! Your Teachlite licenses are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Teachlite licenses offered with your purchase. Mittsure";
                                    } else {
                                        $templateId  = '1707177547108138952';
                                        $templateKey = 'Welcome Mittlens';
                                        $message     = "Congratulations! Your Mittlens are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Mittlens licenses offered with your purchase. Mittsure";
                                    }

                                    sendSms($user->mobile_no, null, null, $templateKey, $message, $templateId);

                                    $this->logSms(
                                        sentTo: $user->mobile_no,
                                        templateKey: $templateKey,
                                        message: $message,
                                        triggeredBy: 'crmVerifySchoolAddon',
                                        status: 'sent',
                                        senderUserId: auth()->id(),
                                        relatedSchoolId: $school->user_id,
                                    );
                                } else {
                                    // ── Not enough codes — log warning, no SMS ───────
                                    \Log::warning('Auto-assign failed for school ID ' . $school->user_id . ': ' . $assignResult['reason'], [
                                        'mitt_required'   => $assignResult['mitt_required'],
                                        'mitt_available'  => $assignResult['mitt_available'],
                                        'teach_required'  => $assignResult['teach_required'],
                                        'teach_available' => $assignResult['teach_available'],
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $smsEx) {
                        $this->logSms(
                            sentTo: $user->mobile_no,
                            templateKey: $templateKey ?? 'unknown',
                            message: $message ?? '',
                            triggeredBy: 'crmVerifySchoolAddon',
                            status: 'failed',
                            senderUserId: auth()->id(),
                            relatedSchoolId: $school->user_id,
                            errorMessage: $smsEx->getMessage(),
                        );

                        \Log::error('Addon SMS/Assign failed: ' . $smsEx->getMessage());
                    }
                }
            }

            return redirect()->route('school.list', ['active_tab' => 'CrmSchools'])
                ->with(['success' => config('constants.SCHOOL_VERIFY')]);
        } catch (\Exception $e) {

            \Log::error('crmVerifySchool error: ' . $e->getMessage());

            return redirect()->back()
                ->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function crmSendSmsToRM($id)
    {
        try {
            $school = Schools::with(['user', 'user_additional_details'])->findOrFail($id);

            $additionalDetail = UserAdditionalDetail::where('user_id', $school->user_id)->first();

            if (! $additionalDetail || ! $additionalDetail->assign_to) {
                return response()->json([
                    'success' => false,
                    'message' => 'No RM assigned to this school.',
                ]);
            }

            $rm = User::find($additionalDetail->assign_to);

            if (! $rm || empty($rm->mobile_no)) {
                return response()->json([
                    'success' => false,
                    'message' => 'RM not found or RM has no mobile number.',
                ]);
            }

            $schoolUser      = $school->user;
            $schoolName      = $schoolUser ? $schoolUser->name : 'N/A';
            $mobileNo        = $rm->mobile_no; // replace with $rm->mobile_no in production
            $supportMobileNo = '9773367345';   // provided by Chandra Sir
            $templateKey     = 'School Missing Detail';
            $message         = "Dear RM, please provide Email and Mobile no. of {$schoolName} for ERP/ Digital Content onboarding immediately. Share Email and Mobile Number of the party at {$supportMobileNo} ASAP. Mittsure";

            try {
                sendSms($mobileNo, null, null, $templateKey, $message);

                $this->logSms(
                    sentTo: $mobileNo,
                    templateKey: $templateKey,
                    message: $message,
                    triggeredBy: 'crmSendSmsToRM',
                    status: 'sent',
                    senderUserId: auth()->id(),
                    relatedSchoolId: $school->user_id,
                    relatedRmId: $rm->id,
                );
            } catch (\Exception $smsEx) {
                $this->logSms(
                    sentTo: $mobileNo,
                    templateKey: $templateKey,
                    message: $message,
                    triggeredBy: 'crmSendSmsToRM',
                    status: 'failed',
                    senderUserId: auth()->id(),
                    relatedSchoolId: $school->user_id,
                    relatedRmId: $rm->id,
                    errorMessage: $smsEx->getMessage(),
                );

                \Log::error('crmSendSmsToRM sendSms failed: ' . $smsEx->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'SMS could not be sent. Error has been logged.',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'SMS sent to RM successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('crmSendSmsToRM error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => config('constants.FLASH_TRY_CATCH'),
            ]);
        }
    }

    public function crmSchoolRemove(Request $request)
    {
        // ── Validation ────────────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'school_id' => 'required|integer|exists:schools,id',
            'remark'    => ['required', 'string', 'max:500', function ($attribute, $value, $fail) {
                $wordCount = count(array_filter(explode(' ', trim($value)), fn($w) => $w !== ''));
                if ($wordCount < 4) {
                    $fail('The removal remark must contain at least 4 words.');
                }
            }],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // ── Fetch & guard ─────────────────────────────────────────────────────────
        $school = Schools::where('id', $request->school_id)
            ->whereNull('deleted_at') // not already soft-deleted
            ->first();

        if (! $school) {
            return response()->json([
                'status'  => false,
                'message' => 'School already varified.',
            ], 404);
        }

        // ── Soft delete + record who removed it and why ───────────────────────────
        $school->update([
            'removed_by'     => Auth::id(),
            'removal_remark' => trim($request->remark),
        ]);

        $school->delete(); // sets deleted_at via SoftDeletes — data stays in DB

        return response()->json([
            'status'  => true,
            'message' => 'School removed successfully.',
        ]);
    }

    public function smsLogsList(Request $request)
    {
        $query = SmsLog::with(['senderUser', 'school', 'rm'])
            ->orderBy('created_at', 'desc');

        // Filter: search by mobile number
        if ($request->filled('sent_to')) {
            $query->where('sent_to', 'like', '%' . $request->sent_to . '%');
        }

        // Filter: template key
        if ($request->filled('template_key')) {
            $query->where('template_key', $request->template_key);
        }

        // Filter: triggered by (method/feature)
        if ($request->filled('triggered_by')) {
            $query->where('triggered_by', $request->triggered_by);
        }

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter: school id
        if ($request->filled('school_id')) {
            $query->where('related_school_id', $request->school_id);
        }

        $logs = $query->paginate(50)->withQueryString();
        // For filter dropdowns
        $templateKeys = SmsLog::distinct()->pluck('template_key')->filter()->values();
        $triggeredBys = SmsLog::distinct()->pluck('triggered_by')->filter()->values();

        return view('admin.user.crm-sms-logs', compact('logs', 'templateKeys', 'triggeredBys'));
    }

    /**
     * Auto-assign access codes to school based on CrmSchoolAddon counts.
     * Returns array with assignment result details.
     */
    private function autoAssignLensCodes(Schools $school, $user): array
    {
        $result = [
            'mitt_assigned'  => 0,
            'teach_assigned' => 0,
            'mitt_required'  => 0,
            'teach_required' => 0,
            'mitt_available' => 0,
            'teach_available' => 0,
            'success'        => false,
            'reason'         => '',
        ];

        // Get required counts from CrmSchoolAddon
        $addon = CrmSchoolAddon::where('user_id', $user->id)
            ->where(fn($q) => $q->where('mittleance', '>', 0)->orWhere('techlite', '>', 0))
            ->first();
        if (!$addon) {
            $result['reason'] = 'No addon record found';
            return $result;
        }

        $mittRequired  = (int) ($addon->mittleance ?? 0);
        $teachRequired = (int) ($addon->techlite   ?? 0);

        $result['mitt_required']  = $mittRequired;
        $result['teach_required'] = $teachRequired;

        // Count available (unassigned) codes of each type
        $mittAvailable  = AccessCodeEmbibe::where('type', 'mittlense')
            ->whereNull('school_id')
            ->count();

        $teachAvailable = AccessCodeEmbibe::where('type', 'teachlite')
            ->whereNull('school_id')
            ->count();

        $result['mitt_available']  = $mittAvailable;
        $result['teach_available'] = $teachAvailable;

        // Check if enough codes available — ALL or NOTHING per type
        $mittOk  = ($mittRequired  == 0) || ($mittAvailable  >= $mittRequired);
        $teachOk = ($teachRequired == 0) || ($teachAvailable >= $teachRequired);

        if (!$mittOk) {
            $result['reason'] = "Not enough MittLens codes. Required: {$mittRequired}, Available: {$mittAvailable}";
            return $result;
        }

        if (!$teachOk) {
            $result['reason'] = "Not enough TeachLite codes. Required: {$teachRequired}, Available: {$teachAvailable}";
            return $result;
        }

        // Assign MittLens codes
        if ($mittRequired > 0) {

            $mittIds = AccessCodeEmbibe::where('type', 'mittlense')
                ->whereNull('school_id')
                ->limit($mittRequired)
                ->pluck('id');

            AccessCodeEmbibe::whereIn('id', $mittIds)->update([
                'school_id' => $school->user_id,
                'status'    => 1,
            ]);

            $result['mitt_assigned'] = $mittIds->count();
        }

        // Assign TeachLite codes
        if ($teachRequired > 0) {
            $teachIds = AccessCodeEmbibe::where('type', 'teachlite')
                ->whereNull('school_id')
                ->limit($teachRequired)
                ->pluck('id');

            AccessCodeEmbibe::whereIn('id', $teachIds)->update([
                'school_id' => $school->user_id,
                'status'    => 1,
            ]);

            $result['teach_assigned'] = $teachIds->count();
        }

        // Log assignment in DB
        CrmSchoolAddon::where('user_id', $user->id)->update([
            'codes_assigned'    => 1,
            'codes_assigned_at' => now(),
            'assigned_school_id' => $school->user_id,
            'assigned_data' => json_encode([
                'mitt_assigned'   => $result['mitt_assigned'],
                'teach_assigned'  => $result['teach_assigned'],
                'mitt_required'   => $result['mitt_required'],
                'teach_required'  => $result['teach_required'],
                'mitt_available'  => $result['mitt_available'],
                'teach_available' => $result['teach_available'],
                'total_assigned'  => $result['mitt_assigned'] + $result['teach_assigned'],
                'updated_at'      => now()->toDateTimeString(),
            ])
        ]);

        $result['success'] = true;
        return $result;
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
