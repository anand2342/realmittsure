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

class ErpDataSyncController extends Controller
{
    public $data = [];

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
        $this->data['classes']    = SchoolClass::where('is_active', 1)->pluck('name', 'id');
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
        dd($request->all());
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }

        $role          = $request->input('role');
        $role_selected = $role ?? getUserRoles($request->id);
        $request->validate([
            'assign_to'              => 'required',
            'name'                   => 'required',
            'school_board'           => 'required',
            'email'                  => "required|email|unique:users,email,$request->id",
            'school_medium'          => 'required',
            // 'grade'                  => 'required',
            // 'school_affiliation'     => 'required',
            // 'school_registration_no' => 'required',
            'decision_maker_mobile_no'     => "required|unique:users,mobile_no,$request->id",
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
                'id' => $request->id,
            ],
            [
                'name'               => $request->name,
                'username'               => $request->username,
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
                'user_id' => $request->id,
            ],
            [
                'role'                     => $role_selected,
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
            ]
        );

        if (! $user_addtional_detail) {
            return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
        }
        return redirect()->back()->with(['success' => $success]);
    }





    public function schoolsIndex(Request $request)
    {
        $aliasNames = ErpAliasHelper::getAliasNames();
        $dataSyncFrom = '2020-11-01 00:01:00';

        // Get paginated schools
        $schools = DB::connection('erp')->table('all_user')
            ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
            ->where('all_user.status', 'active')
            ->whereIn('add_school.aliasName', $aliasNames)
            ->where('user_type', 'admin')
            ->where('all_user.update_time', '>', $dataSyncFrom)
            ->groupBy('all_user.schid')
            ->paginate(config('constants.PAGINATION.default'));

        // Extract school IDs from paginated data
        $schoolIds = $schools->pluck('schid')->toArray();

        // Get classes grouped by schid
        $classes = DB::connection('erp')->table('class')
            ->whereIn('schid', $schoolIds)
            ->select('id', 'name', 'schid')
            ->get()
            ->groupBy('schid');

        // Add to data
        $this->data['datalist'] = $schools;
        $this->data['schoolClasses'] = $classes;

        return view('admin.erpData.index', $this->data);
    }


    public function teachersIndex(Request $request)
    {
        $aliasNames = ErpAliasHelper::getAliasNames();
        $dataSyncFrom = '2020-11-01 00:01:00';
        $this->data['schools'] = User::where('is_from_erp', 1)->get();
        // $this->data['schools'] = DB::connection('erp')->table('all_user')
        //     ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
        //     ->where('all_user.status', 'active')
        //     ->whereIn('add_school.aliasName', $aliasNames)
        //     ->where('user_type', 'admin')
        //     ->where('all_user.update_time', '>', $dataSyncFrom)
        //     ->groupBy('all_user.schid')
        //     ->select('add_school.id', 'add_school.schoolName')->get();

        $selectedSchool = $request->schid;
        // dd($selectedSchool);
        if ($selectedSchool) {
            $this->data['datalist'] = DB::connection('erp')->table('all_user')
                ->where('user_type', 'Teacher')
                ->where('status', 'active')
                ->where('all_user.schid', $selectedSchool)->get();
        } else {
            $this->data['datalist'] = collect();
        }
        return view('admin.erpData.index', $this->data);
    }
    public function studentsIndex(Request $request)
    {
        $this->data['classes'] = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $aliasNames = ErpAliasHelper::getAliasNames();
        $dataSyncFrom = '2020-11-01 00:01:00';
        $this->data['schools'] = DB::connection('erp')->table('all_user')
            ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
            ->where('all_user.status', 'active')
            ->whereIn('add_school.aliasName', $aliasNames)
            ->where('user_type', 'admin')
            ->where('all_user.update_time', '>', $dataSyncFrom)
            ->groupBy('all_user.schid')
            ->select('add_school.id', 'add_school.schoolName')->get();

        $selectedSchool = $request->schid;
        if ($selectedSchool) {
            $this->data['datalist'] = DB::connection('erp')->table('all_user')
                ->where('user_type', 'student')
                ->where('status', 'active')
                ->where('all_user.schid', $selectedSchool)->get();
        } else {
            $this->data['datalist'] = collect();
        }
        // dd($this->data);
        return view('admin.erpData.index', $this->data);
    }


    public function saveTeacher(Request $request)
    {
        // Return data to check in console (for debugging)
        // dd($request->all());
        dd($request->all());
        try {
            $request->validate([
                'name' => 'required|string',
                'mobile' => 'required|string',
                'password' => 'required|string',
                'school_id' => 'required',
                'mobile_no' => "required|numeric|digits:10|unique:users,mobile_no," . $request->id,
            ]);

            if ($request->has('classes') || $request->has('subjects')) {
                $request->validate([
                    'subjects' => 'required|array',
                    'classes' => 'required|array',
                ]);
            }

            DB::beginTransaction();

            $user = User::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'mobile_no' => $request->mobile,
                    'email' => $request->email ?? null,
                    'created_by' => Auth::id(),
                    'password' => Hash::make($request->password),
                    'validate_string' => $request->password,
                    'is_email_verified' => 1,
                    'is_mobile_verified' => 1,
                ]
            );

            if (!$user) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to save user.'], 500);
            }

            $userrole = UserRole::updateOrCreate(
                ['user_id' => $user->id],
                ['role_slug' =>  'school_teacher']
            );

            if (!$userrole) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to assign role.'], 500);
            }

            $userAdditionalDetail = UserAdditionalDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'role' => 'school_teacher',
                    'school_id' => $request->school_id ?? null,
                    'user_id' => $user->id,
                    'gender' => $request->gender ?? null,
                    'age' => $request->age ?? null,
                    'address' => $request->address ?? null,
                    'city' => $request->city ?? null,
                    'state' => $request->state ?? null,
                    'qualification' => $request->qualification ?? null,
                    'class_assigned' => $request->classes_assigned ?? null,
                    'experience' => $request->experience ?? null,
                    'dob' => $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null,
                    'assigned_classes' => $request->classes ? implode(',', $request->classes) : null,
                    'assigned_subjects' => $request->subjects ? implode(',', $request->subjects) : null,
                ]
            );

            if (!$userAdditionalDetail) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to save additional details.'], 500);
            }

            DB::commit();

            return response()->json(['message' => 'Teacher saved successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}
