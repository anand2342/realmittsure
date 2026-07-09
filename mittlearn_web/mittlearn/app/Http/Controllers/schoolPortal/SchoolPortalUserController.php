<?php

namespace App\Http\Controllers\schoolPortal;

use App\Exports\StudentsExport;
use App\Exports\TeachersExport;
use App\Exports\UserLoginAceessExport;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\LoginAsUserLog;
use App\Models\MediaFiles;
use App\Models\MediaGallery;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\Section;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserLog;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SchoolPortalUserController extends Controller
{
    public $data = [];
    public function studentManager(Request $request)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        $teacherAssignedClasses = [];

        // If the role is "school_teacher", use school_id from UserAdditionalDetail
        if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;

            // Get the teacher's assigned classes
            $teacherAssignedClasses = getTeacherAssignedClasses();
        }

        // $this->data['classes']        = SchoolClass::pluck('name', 'id');
        $this->data['classes']        = getUserSchoolClasses($parentId);
        $this->data['sections'] = Section::where('is_active', 1)->pluck('section_name', 'id');

        $this->data['teacherClasses'] = SchoolClass::whereIn('id', $teacherAssignedClasses)->pluck('name', 'id');

        $query = User::where('is_verified', 1)->with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_student')
                    ->where('school_id', $parentId);
            })
            ->when($role === 'school_teacher', function ($query) use ($teacherAssignedClasses) {
                $query->whereHas('studentDetails', function ($subQuery) use ($teacherAssignedClasses) {
                    $subQuery->whereIn('class', $teacherAssignedClasses);
                });
            });
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }
        if ($request->filled('sort')) {
            $sortOrder = $request->sort === 'asc' ? 'ASC' : 'DESC';
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', 'DESC'); // Default sorting
        }

        $this->data['students'] = $query->paginate(config('constants.PAGINATION.default'));

        $this->data['roles'] = Role::get();

        return view('schoolPortal.user.student_manager', $this->data);
    }

    public function studentAdd(Request $request)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        $teacherAssignedClasses = [];
        if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;

            // Get the teacher's assigned classes
            $teacherAssignedClasses = getTeacherAssignedClasses();
        }
        $this->data['classes']        = getUserSchoolClasses($parentId);
        $this->data['sections'] = Section::where('is_active', 1)->pluck('section_name', 'id');

        $this->data['teacherClasses'] = SchoolClass::whereIn('id', $teacherAssignedClasses)->pluck('name', 'id');

        $this->data['roles'] = Role::get();

        return view('schoolPortal.user.student-add-edit', $this->data);
    }

    public function studentEdit($id)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        $teacherAssignedClasses = [];
        if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;

            // Get the teacher's assigned classes
            $teacherAssignedClasses = getTeacherAssignedClasses();
        }
        $this->data['classes']        = getUserSchoolClasses($parentId);
        $this->data['sections'] = Section::where('is_active', 1)->pluck('section_name', 'id');

        $this->data['teacherClasses'] = SchoolClass::whereIn('id', $teacherAssignedClasses)->pluck('name', 'id');

        $this->data['roles'] = Role::get();
        $this->data['studentData']    = User::with('userAdditionalDetail')->find($id);

        return view('schoolPortal.user.student-add-edit', $this->data);
    }

    public function UnVerfiredStudent(Request $request)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();

        $this->data['classes']        = getUserSchoolClasses($parentId);

        $query = User::where('is_verified', 0)->with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_student')
                    ->where('school_id', $parentId);
            });

        $this->data['students'] = $query->paginate(config('constants.PAGINATION.default'));
        $this->data['roles'] = Role::get();
        return view('schoolPortal.user.unverfied-students', $this->data);
    }

    public function toggleStatus($id)
    {
        try {
            $action_date = \Carbon\Carbon::now()->format('Y-m-d');
            $user        = User::where('id', $id)->first();
            if ($user) {
                $userJson = $user->toJson();
                $userlog  = UserLog::create([
                    'user_id'     => $user->id,
                    'updated_by'  => Auth::id(),
                    'title'       => ($user->status == 1) ? 'User Inactived' : 'User Actived',
                    'uri'         => '--',
                    'action_as'   => ($user->status == 1) ? 'user_inactive' : 'user_active',
                    'action_date' => $action_date,
                    'json_data'   => $userJson,

                    'log_type'    => 'user_update',
                    'log_date'    => now(),
                ]);
                $user->status = ($user->status == 1) ? 0 : 1;
                $user->save(['status']);
                if ($userlog) {
                    return response()->json(['status' => 'success']);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function getUserLogs($userId)
    {
        $logs = UserLog::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }

    public function userSave(Request $request)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();

        // If the role is "school_teacher", use school_id from UserAdditionalDetail
        if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }
        // dd($request->all());
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }

        $role          = $request->input('role');
        $role_selected = $role ?? $request->selectedRole;
        switch ($role_selected) {
            case 'school_student':
                $request->validate([
                    'name'             => 'required',
                    // 'admission_no'     => 'required',
                    'parent_mobile_no' => [
                        'required',
                        'numeric',
                        'regex:/^\d{10}$/',
                        'unique:users,mobile_no,' . $request->id,
                    ],
                    'email'            => [
                        'nullable',
                        'email',
                        'unique:users,email,' . $request->id,
                        'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',
                    ],
                    'class'            => 'required',
                    // 'parent_name'      => 'required',
                    // 'admission_date'   => 'required',
                    // 'dob'              => 'required',
                ]);
                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'             => $request->name,
                        'email'            => $request->email ?? null,
                        'mobile_no'        => $request->parent_mobile_no,
                        'password'         => Hash::make('Mitt@123'),
                        'validate_string' => 'Mitt@123',
                        'created_by'       => Auth::id(),
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

                // $admissionDate = Carbon::hasFormat($request->admission_date, 'm/d/Y') ? Carbon::createFromFormat('m/d/Y', $request->admission_date)->format('Y-m-d') : $request->admission_date;
                // $dob = Carbon::hasFormat($request->dob, 'm/d/Y') ? Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d') : $request->dob;

                $admissionDate = Carbon::parse($request->admission_date)->format('Y-m-d');
                $dob           = Carbon::parse($request->dob)->format('Y-m-d');

                $studentdetail = StudentDetails::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'user_id'     => $user->id,
                        'parent_id'   => $parentId,
                        'school_id'   => $parentId,
                        'doj'         => $admissionDate,
                        'dob'         => $dob,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'role'         => $role_selected,
                        'school_id'    => $parentId,
                        'user_id'      => $user->id,
                        'admission_no' => $request->admission_no ?? '-',
                    ]
                );
                if ($role_selected === 'school_student') {
                    return redirect()->route('sp.student.manager')->with(['success' => $success]);
                }
                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                if (!$request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                return redirect()->back()->with(['success' => $success]);

            case 'school_teacher':
                // dd($request->all());
                $request->validate([
                    'name'          => 'required',
                    // 'last_name'     => 'required',
                    // 'gender'        => 'required',
                    'mobile_no'     => [
                        'required',
                        'numeric',
                        'regex:/^\d{10}$/',
                        'unique:users,mobile_no,' . $request->id,
                    ],
                    'email'         => [
                        'required',
                        'email',
                        'unique:users,email,' . $request->id,
                        'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',
                    ],

                    // 'age'           => 'required|numeric',
                    // 'dob'              => 'required',
                ]);
                $request->validate([
                    'subject' => 'required|array|min:1',
                    'class'   => 'required|array|min:1',
                ], [
                    'class.required' => 'Class is not selected, please select a class by clicking on the class name',
                    'class.min'      => 'Class is not selected, please select a class by clicking on the class name',
                    'subject.required' => 'Please select at least one subject.',
                    'subject.min'      => 'Please select at least one subject.',
                ]);
                // if ($request->subject || $request->class) {
                //     $request->validate([
                //         'subject' => 'required',
                //         'class'   => 'required',
                //         // 'dob'     => 'required|date',
                //     ]);
                // } else {
                //     $request->validate([
                //         'classes_assigned' => 'required|boolean',
                //     ]);
                // }

                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'             => $request->name,
                        'mobile_no'        => $request->mobile_no,
                        'email'            => $request->email,
                        'password'         => Hash::make('Mitt@123'),
                        'validate_string' => 'Mitt@123',
                        'created_by'       => $parentId,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'role'              => $role_selected,
                        'school_id'         => $parentId,
                        'user_id'           => $user->id,
                        // 'last_name'         => $request->last_name,
                        'gender'            => $request->gender,
                        'age'               => $request->age,
                        'address'           => $request->address,
                        'city'              => $request->city,
                        'state'             => $request->state,
                        // 'country'           => $request->country,
                        'qualification'     => $request->qualification,
                        'class_assigned'    => $request->classes_assigned,
                        'experience'        => $request->experience,
                        'dob'               => $dob ?? null,
                        'assigned_classes'  => $request->class ? implode(',', $request->class) : 'null',
                        'assigned_subjects' => $request->subject ? implode(',', $request->subject) : 'null',
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                // $this->userSendmail($user);
                if (!$request->id) {
                    $sent = sendSms($user->mobile_no, '', $user);
                }
                if ($role_selected === 'school_teacher') {
                    return redirect()->route('sp.teacher.manager')->with(['success' => $success]);
                }
                return redirect()->back()->with(['success' => $success]);

            default:
                return redirect()->back()->with(['error' => $error]);
        }
        // return redirect()->back()->with(['error' => $error]);

    }
    public function userSendmail($user)
    {
        if ($user) {
            $user = User::find($user->id);
            $templateId = 30;
            $data = [
                'NAME' => $user->name,
                'EMAIL' => $user->email,
                'MOBILE_NUMBER' => $user->mobile_no,
                'USERNAME' => $user->username,
                'PASSWORD' => $user->vallidate_string,
            ];
            if ($user) {
                sendEmail($templateId, $user->email, $data);
            }
        }
    }
    public function UnVerfiredStudentSave(Request $request)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();

        // If the role is "school_teacher", use school_id from UserAdditionalDetail
        if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }
        // dd($request->all());
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }

        $role          = $request->input('role');
        $role_selected = $role ?? $request->selectedRole;
        switch ($role_selected) {
            case 'school_student':
                $request->validate([
                    'name'             => 'required',
                    'admission_no'     => 'required',
                    'parent_mobile_no' => [
                        'required',
                        'numeric',
                        'regex:/^\d{10}$/',
                        'unique:users,mobile_no,' . $request->id,
                    ],
                    'email'            => [
                        'nullable',
                        'email',
                        'unique:users,email,' . $request->id,
                        'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',
                    ],
                    'class'            => 'required',
                    'parent_name'      => 'required',
                    'admission_date'   => 'required',
                    'dob'              => 'required',
                ]);
                $user = User::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'name'             => $request->name,
                        'email'            => $request->email ?? null,
                        'mobile_no'        => $request->parent_mobile_no,
                        'password'         => Hash::make('Mitt@123'),
                        'validate_string' => 'Mitt@123',
                        'created_by'       => Auth::id(),
                        'is_verified'       => 1,
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

                // $admissionDate = Carbon::hasFormat($request->admission_date, 'm/d/Y') ? Carbon::createFromFormat('m/d/Y', $request->admission_date)->format('Y-m-d') : $request->admission_date;
                // $dob = Carbon::hasFormat($request->dob, 'm/d/Y') ? Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d') : $request->dob;

                $admissionDate = Carbon::parse($request->admission_date)->format('Y-m-d');
                $dob           = Carbon::parse($request->dob)->format('Y-m-d');

                $studentdetail = StudentDetails::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'user_id'     => $user->id,
                        'parent_id'   => $parentId,
                        'school_id'   => $parentId,
                        'doj'         => $admissionDate,
                        'dob'         => $dob,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'role'         => $role_selected,
                        'school_id'    => $parentId,
                        'user_id'      => $user->id,
                        'admission_no' => $request->admission_no,
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }

                return redirect()->back()->with(['success' => $success]);

            case 'school_teacher':
                $request->validate([
                    'name'          => 'required',
                    // 'last_name'     => 'required',
                    'gender'        => 'required',
                    'mobile_no'     => [
                        'required',
                        'numeric',
                        'regex:/^\d{10}$/',
                        'unique:users,mobile_no,' . $request->id,
                    ],
                    'email'         => [
                        'required',
                        'email',
                        'unique:users,email,' . $request->id,
                        'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',
                    ],
                    'address'       => 'required',
                    'city'          => 'required',
                    'state'         => 'required',
                    // 'country'       => 'required',
                    'qualification' => 'required',
                    'experience'    => 'required',
                    'age'           => 'required|numeric',
                ]);

                if ($request->subject || $request->class) {
                    $request->validate([
                        'subject' => 'required',
                        'class'   => 'required',
                        'dob'     => 'required|date',
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
                        'name'             => $request->name,
                        'mobile_no'        => $request->mobile_no,
                        'email'            => $request->email,
                        'password'         => Hash::make('Mitt@123'),
                        'validate_string' => 'Mitt@123',
                        'created_by'       => $parentId,
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
                        'user_id' => $user->id,
                    ],
                    [
                        'role'              => $role_selected,
                        'school_id'         => $parentId,
                        'user_id'           => $user->id,
                        // 'last_name'         => $request->last_name,
                        'gender'            => $request->gender,
                        'age'               => $request->age,
                        'address'           => $request->address,
                        'city'              => $request->city,
                        'state'             => $request->state,
                        // 'country'           => $request->country,
                        'qualification'     => $request->qualification,
                        'class_assigned'    => $request->classes_assigned,
                        'experience'        => $request->experience,
                        'dob'               => $dob ?? null,
                        'assigned_classes'  => $request->class ? implode(',', $request->class) : 'null',
                        'assigned_subjects' => $request->subject ? implode(',', $request->subject) : 'null',
                    ]
                );

                if (! $user_addtional_detail) {
                    return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
                }
                return redirect()->back()->with(['success' => $success]);

            default:
                return redirect()->back()->with(['error' => $error]);
        }
        // return redirect()->back()->with(['error' => $error]);

    }
    public function teacherManager(Request $request)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        // $this->data['subjects'] = Subject::where('is_active', 1)->pluck('name', 'id');
        $subjects        = getSchoolAssignedSubjects($parentId);
        $this->data['subjects']  = Subject::whereIn('id', $subjects)
            ->pluck('name', 'id')
            ->toArray();
        // $this->data['classes']  = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes']        = getUserSchoolClasses(Auth::id());
        $this->data['cities']   = City::all();
        $this->data['states']   = State::pluck('name', 'id');
        $query                  = User::with('userAdditionalDetail')
            ->whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', Auth::id());
            });
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }
        if ($request->filled('sort')) {
            $sortOrder = $request->sort === 'asc' ? 'ASC' : 'DESC';
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', 'DESC');
        }
        $this->data['roles']    = Role::get();
        $this->data['teachers'] = $query->paginate(config('constants.PAGINATION.default'));

        // dd($this->data['teachers']);
        return view('schoolPortal.user.teacher_manager', $this->data);
    }
    public function teacherAddEdit(Request $request)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        $subjects        = getSchoolAssignedSubjects($parentId);
        $this->data['subjects']  = Subject::whereIn('id', $subjects)
            ->pluck('name', 'id')
            ->toArray();
        // $this->data['classes']  = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes']        = getUserSchoolClasses(Auth::id());
        $this->data['cities']   = City::all();
        $this->data['states']   = State::pluck('name', 'id');
        $query                  = User::with('userAdditionalDetail')
            ->whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', Auth::id());
            });
        $this->data['roles']    = Role::get();
        return view('schoolPortal.user.teacher-add-edit', $this->data);
    }
    public function teacherEdit($id)
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        $subjects        = getSchoolAssignedSubjects($parentId);
        $this->data['subjects']  = Subject::whereIn('id', $subjects)
            ->pluck('name', 'id')
            ->toArray();

        // $this->data['classes']  = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes']        = getUserSchoolClasses(Auth::id());
        $this->data['cities']   = City::all();
        $this->data['states']   = State::pluck('name', 'id');
        $query                  = User::with('userAdditionalDetail')
            ->whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', Auth::id());
            });
        $this->data['roles']    = Role::get();
        $this->data['teacherData']    = User::with('userAdditionalDetail')->find($id);
        return view('schoolPortal.user.teacher-add-edit', $this->data);
    }
    public function getCities($state)
    {
        $cities = City::where('state_id', $state)->pluck('city', 'id');
        return response()->json($cities);
    }

    public function exportStudents()
    {
        $role                   = getUserRoles();
        $parentId               = Auth::id();
        $teacherAssignedClasses = [];
        if ($role === "school_teacher") {
            $parentId               = Auth::user()->userAdditionalDetail->school_id;
            $teacherAssignedClasses = getTeacherAssignedClasses();
        }
        $export      = new StudentsExport($role, $parentId, $teacherAssignedClasses);
        $fileContent = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        return response($fileContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="students_list.xlsx"',
        ]);
    }
    public function exportTeachers()
    {
        $export      = new TeachersExport;
        $fileContent = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        return response($fileContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="teacher_list.xlsx"',
        ]);
    }

    public function teacherLoginAceess(Request $request)
    {
        $parentId = Auth::id();

        $query = User::with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', $parentId);
            });
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }
        if ($request->filled('sort')) {
            $sortOrder = $request->sort === 'asc' ? 'ASC' : 'DESC';
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', 'DESC'); // Default sorting
        }
        $this->data['users']    = $query->paginate(config('constants.PAGINATION.default'));
        $this->data['userType'] = 'teachers';
        return view('schoolPortal.user.login-aceess', $this->data);
    }
    public function studentLoginAceess(Request $request)
    {
        $parentId = Auth::id();
        $this->data['classes']        = getUserSchoolClasses($parentId);

        $query = User::with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_student')
                    ->where('school_id', $parentId);
            });
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }
        if ($request->filled('sort')) {
            $sortOrder = $request->sort === 'asc' ? 'ASC' : 'DESC';
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', 'DESC'); // Default sorting
        }
        if ($request->filled('class')) {
            $query->whereHas('studentDetails', function ($query) use ($request) {
                $query->where('class', $request->class);
            });
        }
        $this->data['users']    = $query->paginate(config('constants.PAGINATION.default'));
        $this->data['userType'] = 'students';
        return view('schoolPortal.user.login-aceess', $this->data);
    }
    public function exportLoginAceess($userType)
    {
        $role     = getUserRoles();

        $parentId               = Auth::id();
        $teacherAssignedClasses = [];
        if ($role === "school_teacher") {
            $parentId               = Auth::user()->userAdditionalDetail->school_id;
            $teacherAssignedClasses = getTeacherAssignedClasses();
        }

        if ($userType === 'students') {
            $users = User::with(['userAdditionalDetail', 'studentDetails'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                })
                ->when($role === 'school_teacher', function ($query) use ($teacherAssignedClasses) {
                    $query->whereHas('studentDetails', function ($subQuery) use ($teacherAssignedClasses) {
                        $subQuery->whereIn('class', $teacherAssignedClasses);
                    });
                })
                ->get();
        } elseif ($userType === 'teachers') {
            $users = User::with(['userAdditionalDetail', 'studentDetails'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_teacher')
                        ->where('school_id', $parentId);
                })
                ->get();
        } else {
            // Handle other cases or return an error
            return redirect()->back()->with('error', 'Invalid user type');
        }
        $fileContent = \Maatwebsite\Excel\Facades\Excel::raw(new UserLoginAceessExport($users, $userType), \Maatwebsite\Excel\Excel::XLSX);
        return response($fileContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $userType . '_login_access_list.xlsx"',
        ]);
    }
    public function branchSchoolLogin($id, Request $request)
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->back()->withErrors('User not found.');
        }

        // Save current admin ID in session
        Session::put('parent_school_id', Auth::id());

        // Log in as the user
        Auth::login($user);

        $routeAction  = $request->route()->action;
        $actionMethod = $request->route()->methods[0];
        $params       = null;
        if ($actionMethod == 'POST' || $actionMethod == 'PUT') {
            $params = json_encode($request->all());
        }
        $where = [
            "user_id"   => Auth::user()->id,
            "action_as" => $routeAction['as'],
            "method"    => "GET",
            "log_date"  => date('Y-m-d'),
        ];
        $logData = [
            "user_id"    => Auth::user()->id,
            "uri"        => $request->route()->uri,
            "action_as"  => $routeAction['as'],
            "controller" => $routeAction['controller'],
            "method"     => $actionMethod,
            "json_data"  => $params,
            "log_date"   => date('Y-m-d'),
        ];

        LoginAsUserLog::updateOrCreate($where, $logData);

        return redirect()->route('sp.dashboard')->with('success', 'Logged in as ' . $user->name);
    }

    public function branchSchools(Request $request)
    {
        $parentId = Auth::id();
        $this->data['childSchools'] = UserAdditionalDetail::with('user')->where('parent_school_name', $parentId)->paginate(config('constants.PAGINATION.default'));
        return view('schoolPortal.user.branch-schools', $this->data);
    }

    public function backToParent()
    {
        $adminId = Session::get('parent_school_id');

        if (! $adminId) {
            return redirect()->route('login')->withErrors('Session expired. Please log in again.');
        }

        // Log in as the admin
        $admin = User::find($adminId);

        if (! $admin) {
            return redirect()->route('login')->withErrors('School not found.');
        }

        Auth::login($admin);

        // Remove parent_school_id from session
        Session::forget('parent_school_id');

        // return redirect()->route('dashboard')->with('success', 'Returned to admin dashboard.');
        return redirect('/school-portal/branch-schools')->with('success', 'Returned to admin dashboard.');
    }
}
