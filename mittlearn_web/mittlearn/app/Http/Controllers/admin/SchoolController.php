<?php

namespace App\Http\Controllers\admin;

use App\Exports\AllSchoolExport;
use App\Exports\AllSchoolDetailedExport;
use App\Exports\SchoolAccessCodeExport;
use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\Board;
use App\Models\City;
use App\Models\Classes;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\Schools;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    public $data = [];

    public function schoolList(Request $request)
    {
        try {
            $activeTab      = $request->input('active_tab', 'PartnerSchools');
            $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

            $this->data['activeTab'] = $activeTab;

            // Get states and cities for dropdowns
            $this->data['states'] = State::pluck('name', 'id')->toArray();
            $this->data['cities'] = [];
            if ($request->filled('state_id')) {
                $this->data['cities'] = City::where('state_id', $request->state_id)->pluck('city', 'id')->toArray();
            }

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

            // Partner Schools (verified)
            $partnerQuery = Schools::with('user', 'userSchool')->where('is_verified_by_admin', 1);
            $applyFilters($partnerQuery);
            $this->data['partnerSchools'] = $partnerQuery->orderBy('id', 'DESC')
                ->paginate($perPageRecords, ['*'], 'partner_page');

            // Non-Partner Schools (unverified AND not from CRM)
            $nonPartnerQuery = Schools::with('user', 'userSchool')
                ->where('is_verified_by_admin', 0)
                ->where(function ($q) {
                    $q->where('is_from_crm', 0)->orWhereNull('is_from_crm');
                });
            $applyFilters($nonPartnerQuery);
            $this->data['nonPartnerSchools'] = $nonPartnerQuery->orderBy('id', 'DESC')
                ->paginate($perPageRecords, ['*'], 'non_partner_page');

            // From CRM Schools (is_from_crm = 1 AND unverified)
            $crmQuery = Schools::with('user', 'userSchool', 'user_additional_details')
                ->where('is_from_crm', 1)
                ->where('is_verified_by_admin', 0)
                ->whereHas('user', fn($q) => $q->where('is_from_crm', 1)->where('soid', '!=', ''))
                ->whereHas('assignedDigitalContents'); // ← only schools WITH series
            $applyFilters($crmQuery);
            $this->data['crmSchools'] = $crmQuery->orderBy('id', 'DESC')
                ->paginate($perPageRecords, ['*'], 'crm_page');


            // Append search params to pagination
            $appendParams = $request->except(['partner_page', 'non_partner_page', 'crm_page']);
            $this->data['partnerSchools']->appends($appendParams);
            $this->data['nonPartnerSchools']->appends($appendParams);
            $this->data['crmSchools']->appends($appendParams);

            return view('admin.schoolManagement.school-list', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function schoolVerify($id)
    {
        try {
            $school = Schools::find($id);
            if (! $school) {
                return redirect()->back()->with(['error' => config('constants.FLASH_INVALID_PARAMS')]);
            }
            $school->is_varified_by       = Auth::id();
            $school->is_verified_by_admin = 1;
            $school->save();

            return redirect()->back()->with(['success' => config('constants.FLASH_EMAIL_VERIFY_2')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function schoolEdit($id)
    {
        try {
            $this->data['school']   = Schools::find($id);
            $this->data['users']    = User::pluck('name', 'id')->toArray();
            $this->data['userData'] = User::with('userAdditionalDetail', 'schoolDetails')->first();
            $this->data['roles']    = Role::where('is_active', 1)->pluck('role_name', 'id')->toArray();
            $this->data['boards']   = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['mediums']  = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['classes']  = Classes::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['userId']   = User::where('id', $this->data['school']->id)->first();
            return view('admin.schoolManagement.edit-form', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function schoolUpdate(Request $request)
    {
        $user     = User::find($request->id);
        $schoolId = Schools::where('id', $user->id)->first();
        $request->validate([
            'assign_to'              => 'required',
            'name'                   => 'required',
            'school_board'           => 'required',
            'school_medium'          => 'required',
            'grade'                  => 'required',
            'school_affiliation'     => 'required|numeric',
            'school_registration_no' => 'required|numeric',
            'assign_distributor'     => 'required',
            'onboardERP'             => 'required|nullable|boolean',
            'pincode'                => 'required|numeric',
            'state'                  => 'required',
            'district'               => 'required',
            'address_1'              => 'required',
            'address_2'              => 'required',
            'landmark'               => 'required',
            'bank_name'              => 'required',
            'acc_holder_name'        => 'required',
            'branch_name'            => 'required',
            'acc_no'                 => 'required|numeric',
            'ifsc_code'              => 'required',
        ]);

        try {

            $user = User::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => isset($user->password) ? $user->password : Hash::make('Mitt@123'),
                ]
            );
            if (! $user) {
                return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
            }
            $userrole = UserRole::updateOrCreate(
                ['user_id' => $user->id],
                // ['role_slug' => $role_selected]
            );
            if (! $userrole) {
                return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
            }

            $school = Schools::updateOrCreate(
                [
                    'id' => $schoolId->id,
                ],
                [
                    'name'        => $request->name,
                    'address'     => $request->address_1,
                    'city'        => $request->district,
                    'state'       => $request->state,
                    'postal_code' => $request->pincode,
                ]
            );

            if (! $school) {
                return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
            }

            foreach ($request->class as $value) {
                $school_assigned_class = SchoolAssignedClass::updateOrCreate(
                    ['school_id' => $schoolId->id, 'class_id' => $value],
                    [
                        'school_id' => $school->id,
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
                    'user_id' => $user->id,
                ],
                [
                    // 'role' => $role_selected,
                    'user_id'                  => $user->id,
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
                    'grade'                    => $request->grade,
                    'school_affiliation_no'    => $request->school_affiliation,
                    'school_registration_no'   => $request->school_registration_no,
                    'incorporation_date'       => $request->incorporation_date,
                    'assign_distributor'       => $request->assign_distributor,
                    'gst_no'                   => $request->gst_no,
                    'board_erp'                => $request->onboardERP,
                    'address'                  => $request->address_2,
                    'landmark'                 => $request->landmark,
                    'bank_name'                => $request->bank_name,
                    'acc_holder_name'          => $request->acc_holder_name,
                    'branch_name'              => $request->branch_name,
                    'acc_no'                   => $request->acc_no,
                    'ifsc_code'                => $request->ifsc_code,
                ]
            );

            if (! $user_addtional_detail) {
                return redirect()->back()->with(['error' => config('constants.API_MSG.REC_ADD_FAILED')]);
            }

            return redirect()->back()->with(['success' => config('constants.FLASH_REC_UPDATE_1')]);
        } catch (\Exception $e) {
            // Optionally, add the exception message to the session to display it
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function schoolAccessCode($id)
    {
        $this->data['school']      = Schools::find($id);
        $this->data['accessCodes'] = AccessCode::with(['board', 'class', 'medium', 'usedBy'])
            ->where('school_id', $this->data['school']->id)
            ->get()
            ->groupBy('class_id');

        return view('admin.schoolManagement.school-access-code', $this->data);
    }
    public function exportExcel($classId)
    {
        $accessCodes = AccessCode::with(['board', 'class', 'medium', 'usedBy', 'school'])
            ->where('class_id', $classId)
            ->get();

        $file = Excel::raw(new SchoolAccessCodeExport($accessCodes), \Maatwebsite\Excel\Excel::XLSX);

        return Response::make($file, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="access_codes.xlsx"',
        ]);
    }

    // Export as CSV
    public function exportCSV($classId)
    {
        $accessCodes = AccessCode::with(['board', 'class', 'medium', 'usedBy', 'school'])
            ->where('class_id', $classId)
            ->get();
        // return Excel::download(new SchoolAccessCodeExport($accessCodes), 'access_codes.csv');

        $file = Excel::raw(new SchoolAccessCodeExport($accessCodes), \Maatwebsite\Excel\Excel::CSV);
        // Return the file with custom headers using Response::download()
        return Response::make($file, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="access_codes.csv"',
        ]);
    }

    // Export for Print (view)
    public function exportPrint($classId)
    {
        // Fetch data for the given classId
        $accessCodes = AccessCode::with(['board', 'class', 'medium', 'usedBy', 'school'])
            ->where('class_id', $classId)
            ->get();

        // Return print-friendly view
        return view('admin.schoolManagement.print_access_code', ['accessCodes' => $accessCodes]);
    }
    public function schoolAccessDeleted($id)
    {
        try {
            $accessCodeDelete = AccessCode::find($id);
            $accessCodeDelete->delete();
            return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function schoolUsers(Request $request)
    {
        $this->data['schoolList'] = Schools::pluck('name', 'user_id');
        $this->data['classes']    = Classes::where('is_active', 1)->pluck('name', 'id');

        $id     = $request->query('school_id');
        $school = Schools::where('id', $id)->first();
        if ($school) {
            $this->data['schoolId'] = $school->user_id;
        } else {
            $this->data['schoolId'] = null;
        }

        return view('admin.schoolManagement.school-users', $this->data);
    }

    public function schoolUsersDetails($id)
    {
        $this->data['data']  = User::with('userAdditionalDetail', 'studentDetails')->where('id', $id)->first();
        $assignedClassIds    = explode(',', $this->data['data']->userAdditionalDetail->assigned_classes);
        $assignedSubjectsIds = explode(',', $this->data['data']->userAdditionalDetail->assigned_subjects);
        $classNames          = Classes::whereIn('id', $assignedClassIds)
            ->pluck('name')
            ->implode(', ');
        $subjectNames = Subject::whereIn('id', $assignedSubjectsIds)
            ->pluck('name')
            ->implode(', ');

        $this->data['data']->userAdditionalDetail->class_names   = $classNames ?: 'No Classes assigned';
        $this->data['data']->userAdditionalDetail->subject_names = $subjectNames ?: 'No Subject assigned';
        // dd($this->data['data']);
        return view('admin.schoolManagement.school-users-details', $this->data);
    }

    public function allSchoolsExport(Request $request)
    {
        $now      = now();
        $fileName = "all_schools-{$now}.xlsx";

        // $file = Excel::raw(new UsersExport($roleSlug), ExcelFormat::XLSX);
        $file = Excel::raw(new AllSchoolExport, ExcelFormat::XLSX);

        return Response::make($file, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
            'Pragma'        => 'public',
        ]);
    }
    public function allSchoolsDetailedExport(Request $request)
    {
        $now      = now()->format('Y-m-d_H-i-s');
        $fileName = "schools_detailed_report_{$now}.xlsx";

        $file = Excel::raw(new AllSchoolDetailedExport(), ExcelFormat::XLSX);

        return Response::make($file, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }


    // ────────────────────────────────────────────────────────────────────────────
    // IF YOU HAVE 5 000+ SCHOOLS — use queued export instead:
    // ────────────────────────────────────────────────────────────────────────────
    //
    // 1. Make AllSchoolDetailedExport implement ShouldQueue + WithChunkReading:
    //
    //    class AllSchoolDetailedExport implements ..., ShouldQueue
    //    {
    //        use Exportable;
    //        public function chunkSize(): int { return 500; }
    //    }
    //
    // 2. Dispatch and notify user when ready:
    //
    //    public function allSchoolsDetailedExportQueued(Request $request)
    //    {
    //        $path = 'exports/schools_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    //
    //        Excel::store(
    //            new AllSchoolDetailedExport(),
    //            $path,
    //            'local',
    //            ExcelFormat::XLSX
    //        )->chain([
    //            new \App\Jobs\NotifyAdminExportReady($request->user(), $path),
    //        ]);
    //
    //        return response()->json([
    //            'status'  => true,
    //            'message' => 'Export queued. You will be notified when ready.',
    //        ]);
    //    }

    public function getCities($state)
    {
        $cities = City::where('state_id', $state)->pluck('city', 'id');
        return response()->json($cities);
    }
}
