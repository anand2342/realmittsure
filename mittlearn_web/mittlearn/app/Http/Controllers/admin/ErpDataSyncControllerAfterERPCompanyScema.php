<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ErpAliasHelper;
use App\Models\AcademicSession;
use App\Models\Section;
use App\Models\Board;
use App\Models\City;
use App\Models\erpSync\SyncLog;
use App\Models\Grade;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ErpDataSyncController extends Controller
{
    public $data = [];
    public function schoolsIndex(Request $request)
    {
        // B4 EMAGIX series aliases shared by Chandra Sir in excel sheet but now not in use
        $aliasNames = ErpAliasHelper::getAliasNames();
        // BASE DATE - data sync starts from 1st Dec 2023 for fetching active schools
        $dataSyncFrom = '2023-12-01 00:00:01';

        // Start query builder
        $query = DB::connection('erp')->table('all_user')
            ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
            ->where('all_user.status', 'active')
            // ->whereIn('add_school.aliasName', $aliasNames)
            ->where('user_type', 'admin')
            ->where('all_user.update_time', '>', $dataSyncFrom)
            ->groupBy('all_user.schid');

        // Apply search filter by name if present
        if ($request->has('name') && !empty($request->name)) {
            $query->where('add_school.schoolName', 'like', '%' . $request->name . '%');
        }
        if ($request->has('username') && !empty($request->username)) {
            $query->where('all_user.name', 'like', '%' . $request->username . '%');
        }

        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
        $schools = $query->paginate($perPageRecords);

        $schoolIds = $schools->pluck('schid')->toArray();

        $classes = DB::connection('erp')->table('class')
            ->whereIn('schid', $schoolIds)
            ->select('id', 'name', 'schid')
            ->get()
            ->groupBy('schid');

        $this->data['datalist'] = $schools;
        $this->data['schoolClasses'] = $classes;

        return view('admin.erpData.index', $this->data);
    }
    public function addSchools($id)
    {
        $this->data['erpData'] = DB::connection('erp')->table('all_user')
            ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
            ->where('add_school.id', $id)->first();
        $this->data['roles']     = Role::where('is_active', 1)->pluck('role_name', 'role_slug');
        $this->data['users']     = User::where('status', 1)->pluck('name', 'id');
        $this->data['salesman']  = User::where('status', 1)->whereHas('userRole', function ($query) {
            $query->where('role_slug', 'salesman');
        })->pluck('name', 'id');
        $this->data['distributors'] = User::whereHas('userRole', function ($query) {
            $query->where('role_slug', 'distributors');
        })->pluck('name', 'id');
        $this->data['boards']     = Board::where('is_active', 1)->pluck('name', 'id');
        $this->data['mediums']    = Medium::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes']    = SchoolClass::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
        $this->data['subjects']   = Subject::where('is_active', 1)->pluck('name', 'id');
        $this->data['cities']     = City::pluck('city', 'id');
        $this->data['states']     = State::pluck('name', 'id');
        $this->data['schoolList'] = Schools::where('school_role', 'parent')->pluck('name', 'user_id');
        $this->data['schools'] = Schools::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->pluck('name', 'user_id');

        $this->data['academicSessions'] =  AcademicSession::select('id', 'name')
            ->where('is_active', 1)
            ->get()
            ->unique('name')
            ->pluck('name', 'id');
        $this->data['grades']     = Grade::pluck('name', 'id')->toArray();

        $this->data['sections'] = Section::where('is_active', 1)->pluck('section_name', 'id');
        return view('admin.erpData.add-schools', $this->data);
    }
    public function saveSchool(Request $request)
    {
        // Priority 1: Match by ERP School ID
        if (!empty($request->schid)) {
            $userId = User::where('erp_schid', $request->schid)->value('id');
        }

        // Priority 2: If not found, match by mobile number
        if (!$userId && !empty($request->decision_maker_mobile_no)) {
            $userId = User::where('mobile_no', $request->decision_maker_mobile_no)->value('id');
        }

        // Priority 3: If still not found, match by email
        if (!$userId && !empty($request->email)) {
            $userId = User::where('email', $request->email)->value('id');
        }

        // Set flash messages based on result
        if ($userId) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }

        $role          = $request->input('role');
        $request->validate([
            'assign_to'              => 'required',
            'name'                   => 'required',
            'school_board'           => 'required',
            'email'                  => "required|email|unique:users,email,$userId",
            'school_medium'          => 'required',
            // 'grade'                  => 'required',
            // 'school_affiliation'     => 'required',
            // 'school_registration_no' => 'required',
            'decision_maker_mobile_no'     => "required|unique:users,mobile_no,$userId",
            'academic_session_id'     => 'required',
            'batch_id'     => 'required',
            'school_type'     => 'required',
            'class'                  => 'required',
            'pincode'                => ['required', 'regex:/^[1-9]{1}[0-9]{5}$/'],
            'state'                  => 'required',
            'district'               => 'required',
            'password' => 'required|min:8',
            // 'address_1' => 'required',
            // 'address_2' => 'required',
            // 'landmark' => 'required',
            // 'bank_name' => 'required',
            // 'acc_holder_name' => 'required',
            // 'branch_name' => 'required',
            // 'acc_no' => 'required|numeric',
            // 'ifsc_code' => 'required',
        ]);

        $user = User::updateOrCreate(
            [
                'id' => $userId,
            ],
            [
                'name'               => $request->name,
                'username'               => $request->username,
                'email'              => $request->email,
                'mobile_no'          => $request->decision_maker_mobile_no ?? null,
                'created_by'         => Auth::id(),
                'password'           => Hash::make($request->password) ?? Hash::make('Mitt@123'),
                'validate_string'    => $request->password ?? 'Mitt@123',
                'is_from_erp'    => '1',
                'erp_schid'    => $request->schid ?? '',
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
            ['role_slug' => 'school_admin']
        );
        if (! $userrole) {
            return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
        }

        $school = Schools::updateOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'user_id'              => $user->id,
                'unique_id'              => $request->uniqueId,
                'school_type'       => $request->school_type,
                'school_role'             => $request->school_role,
                'is_verified_by_admin' => 1,
                'is_varified_by'       => Auth::id(),
                'name'                 => $request->name,
                'address'              => $request->address_1,
                'city'                 => $request->district,
                'state'                => $request->state,
                'postal_code'          => $request->pincode,
                'academic_session_id'          => $request->academic_session_id,
                'batch_id'          => $request->batch_id,

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
                'user_id' => $userId,
            ],
            [
                'role'                     => 'school_admin',
                'user_id'                  => $user->id,
                'school_id'              => $user->id,
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
                'branch_name'              => $request->bank_branch_name,
                'acc_no'                   => $request->acc_no,
                'ifsc_code'                => $request->ifsc_code,
                'customer_type'            => 'exisiting',
            ]
        );

        if (! $user_addtional_detail) {
            return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
        }

        SyncLog::create([
            'table'     => 'user',
            'table_id'  => $user->id,
            'data'      => json_encode($user),
            'status'    => 'first-synced',
            'synced_at' => now(),
        ]);
        return redirect()->route('erp-data.schools.index')->with(['success' => $success]);
    }
    // Teacher related functions
    public function teachersIndex(Request $request)
    {
        // Populate school dropdown
        $this->data['schools'] = User::whereNotNull('erp_schid')
            ->select('erp_schid', 'name')
            ->get();

        $selectedSchool = $request->schid;
        $searchName = $request->name;

        if ($selectedSchool) {
            $teachers = DB::connection('erp')->table('all_user')
                ->where('user_type', 'Teacher')
                ->where('status', 'active')
                ->where('schid', $selectedSchool)
                ->when($searchName, function ($query, $searchName) {
                    $query->where('name', 'like', '%' . $searchName . '%');
                })
                ->get()
                ->map(function ($teacher) {
                    // Get employee admission record
                    $employee = DB::connection('erp')->table('employeeaddmission')
                        ->where('empusername', $teacher->name)
                        ->first();

                    $classes = [];
                    $subjects = [];

                    if ($employee) {
                        // Get class names
                        $classIds = DB::connection('erp')->table('class_teacher')
                            ->where('teacher_id', $employee->id)
                            ->pluck('class_id');

                        $classes = DB::connection('erp')->table('class')
                            ->whereIn('id', $classIds)
                            ->pluck('name')
                            ->toArray();

                        // Get subject allocations
                        $subjectAllocations = DB::connection('erp')->table('subject_allocate')
                            ->where('teacher_id', $employee->id)
                            ->get();

                        foreach ($subjectAllocations as $allocation) {
                            $subjectIds = explode(',', $allocation->subject_id);
                            $activeSubjects = DB::connection('erp')->table('subjects')
                                ->whereIn('idsubjects', $subjectIds)
                                ->where('status', 'ACTIVE')
                                ->pluck('subjectName')
                                ->toArray();

                            $subjects = array_merge($subjects, $activeSubjects);
                        }
                    }

                    $teacher->assigned_classes = implode(', ', $classes);
                    // $teacher->assigned_subjects = implode(', ', array_unique($subjects));
                    $teacher->assigned_subjects = !empty($classes)
                        ? implode(', ', array_unique($subjects))
                        : '';
                    return $teacher;
                });

            $this->data['datalist'] = $teachers;
        } else {
            $this->data['datalist'] = collect();
        }

        return view('admin.erpData.index', $this->data);
    }
    public function saveTeacher(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->schid) {
                $schoolId = User::where('erp_schid', $request->schid)->value('id') ?? null;
                $userId = User::where('erp_db_id', $request->erp_id)->value('id') ?? null;
            }
            $request->validate([
                'name' => 'required|string',
                'password' => 'required|string',
                'mobile' => "required|numeric|digits:10|unique:users,mobile_no," . $userId,
            ]);

            if ($request->has('classes') || $request->has('subjects')) {
                $request->validate([
                    'subjects' => 'required|array',
                    'classes' => 'required|array',
                ]);
            }

            DB::beginTransaction();

            $user = User::updateOrCreate(
                ['id' => $userId],
                [
                    'name' => $request->name,
                    'mobile_no' => $request->mobile,
                    'email' => $request->email ?? null,
                    'created_by' => Auth::id(),
                    'username' => $request->name,
                    'password' => Hash::make($request->password),
                    'validate_string' => $request->password,
                    'is_from_erp'    => '1',
                    'erp_db_id'    =>  $request->erp_id,
                    'is_email_verified' => 1,
                    'is_mobile_verified' => 1,
                ]
            );

            if (!$user) {
                DB::rollBack();
                return redirect()->back()->with(['error' => 'Failed to save user']);
            }

            $userrole = UserRole::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' =>  $user->id,
                    'role_slug' =>  'school_teacher'
                ],
            );

            if (!$userrole) {
                DB::rollBack();
                return redirect()->back()->with(['error' => 'Failed to assign role']);
            }

            $userAdditionalDetail = UserAdditionalDetail::updateOrCreate(
                ['user_id' => $userId],
                [
                    'role' => 'school_teacher',
                    'school_id' => $schoolId ?? null,
                    'user_id' => $user->id,
                    'assigned_classes' => $request->classes ? implode(',', $request->classes) : null,
                    'assigned_subjects' => $request->subjects ? implode(',', $request->subjects) : null,
                ]
            );

            if (!$userAdditionalDetail) {
                DB::rollBack();
                return redirect()->back()->with(['error' => 'Failed to save additional details.']);
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Teacher saved successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with(['error' => 'An error occurred.', 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'An error occurred.', 'error' => $e->getMessage()]);
        }
    }

    // Student related Routes
    public function studentsIndex(Request $request)
    {
        $this->data['schools'] = User::where('is_from_erp', 1)->whereNotNull('erp_schid')->select('erp_schid', 'name')->get();
        $this->data['classes'] = SchoolClass::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');

        $selectedSchool = $request->schid;
        $searchName = $request->name;
        if ($selectedSchool) {
         $students = DB::connection('erp')->table('registration1')
                ->where('status', 'ACTIVE')
                ->where('schid', $selectedSchool)
                ->where('session', '2025-2026')
                ->when($searchName, function ($query, $searchName) {
                    $query->where('fname', 'like', '%' . $searchName . '%');
                })
                ->get()
                ->map(function ($student) {
                    $className = DB::connection('erp')->table('class')
                        ->where('id', $student->classid)
                        ->value('name');

                    $student->class_name = $className ?? 'N/A';
                    return $student;
                });
            // dd($students);
            $this->data['datalist'] = $students;
        } else {
            $this->data['datalist'] = collect();
        }
// dd($this->data['datalist']);

        return view('admin.erpData.index', $this->data);
    }
}
