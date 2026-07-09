<?php

namespace App\Http\Controllers\admin;

use App\Exports\AccessCodeExport;
use App\Exports\AccessCodeEmbibeExport;
use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\AccessCodeEmbibe;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\CrmSchoolAddon;
use App\Models\Schools;
use App\Models\Setting;
use App\Models\SmsLog;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class AccessCodeController extends Controller
{
    public $data = [];

    public function index(Request $request)
    {
        $activeTab = $request->input('active_tab', 'teachlite');
        $this->data['activeTab'] = $activeTab;
        // dd($activeTab);
        $perPage = $request->input(
            'per_page',
            $request->cookie('perPage', config('constants.PAGINATION.default'))
        );

        $teachLitequery = AccessCodeEmbibe::where('type', 'teachlite')->with('schoolName')->orderBy('id', 'DESC');

        if ($request->filled('access_code')) {
            $teachLitequery->where('licence_key', 'like', '%' . $request->access_code . '%');
        }

        if ($request->filled('school_id')) {
            $teachLitequery->where('school_id', $request->school_id);
        }

        $this->data['teachAccessCode'] = $teachLitequery->paginate($perPage);

        $mittLitequery = AccessCodeEmbibe::where('type', 'mittlense')->with('schoolName')->orderBy('id', 'DESC');

        if ($request->filled('access_code')) {
            $mittLitequery->where('licence_key', 'like', '%' . $request->access_code . '%');
        }
        if ($request->filled('school_id')) {
            $mittLitequery->where('school_id', $request->school_id);
        }
        $this->data['mittAccessCode'] = $mittLitequery->paginate($perPage);

        $this->data['states'] = State::pluck('name', 'id');
        $this->data['cities'] = City::pluck('city', 'id');
        $this->data['schools'] = Schools::pluck('name', 'id');
        $this->data['mittCodes'] = AccessCodeEmbibe::where('status', 0)
            ->where('type', 'mittlense')
            ->count();
        $this->data['techCodes'] = AccessCodeEmbibe::where('status', 0)
            ->where('type', 'teachlite')
            ->count();

        $this->data['accessCodeSchools'] = AccessCodeEmbibe::with('schoolName')
            ->whereNotNull('school_id')
            ->get()
            ->groupBy('school_id') // group to make them unique
            ->map(function ($items) {
                return optional($items->first()->schoolName)->name;
            })
            ->filter() // remove nulls in case schoolName is missing
            ->toArray();

        $schoolsWithLensQuery = AccessCodeEmbibe::with([
            'schoolName',
            'schoolName.userAdditionalDetail',
        ])
            ->whereNotNull('school_id')
            ->whereIn('type', ['mittlense', 'teachlite']);

        // ✅ Filters
        if ($request->filled('school_id')) {
            $schoolsWithLensQuery->where('school_id', $request->school_id);
        }

        if ($request->filled('access_code')) {
            $schoolsWithLensQuery->where('licence_key', 'like', '%' . $request->access_code . '%');
        }

        // Get collection
        $schoolsCollection = $schoolsWithLensQuery->get()
            ->groupBy('school_id')
            ->map(function ($items) {

                $schoolUser = optional($items->first()->schoolName);
                $uad        = optional($schoolUser->userAdditionalDetail ?? null);
                $rmId       = $uad->assign_to ?? null;
                $rm         = $rmId ? \App\Models\User::find($rmId) : null;
                $uniqueId   = optional(optional($schoolUser)->schoolDetails)->unique_id ?? '—';

                return [
                    'school_id'   => $items->first()->school_id,
                    'id'          => $uniqueId,
                    'name'        => $schoolUser->name ?? '—',
                    'contact'     => $schoolUser->mobile_no ?? '—',
                    'email'     => $schoolUser->email ?? '—',
                    'rm_name'     => $rm->name ?? '—',
                    'rm_phone'    => $rm->mobile_no ?? '—',
                    'rm_email'    => $rm->email ?? '—',
                    'mitt_count'  => $items->where('type', 'mittlense')->count(),
                    'teach_count' => $items->where('type', 'teachlite')->count(),
                ];
            })
            ->values();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $perPage ?? 10;

        $currentItems = $schoolsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedSchools = new LengthAwarePaginator(
            $currentItems,
            $schoolsCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        $this->data['schoolsWithLens'] = $paginatedSchools;

        // Get school IDs that already received lens SMS (from sms_logs)
        $lensSentSchoolIds = \App\Models\SmsLog::whereIn('template_key', [
            'Welcome teachlite and mittlens',
            'Welcome Teachlite',
            'Welcome Mittlens',
        ])
            ->whereNotNull('related_school_id')
            ->where('status', 'sent')
            ->pluck('related_school_id')
            ->unique()
            ->toArray();

        $this->data['lensSentSchoolIds'] = $lensSentSchoolIds;

        $this->data['freeMittlenseCount'] = AccessCodeEmbibe::where('type', 'mittlense')
            ->whereNull('school_id') //  only free  (available to assign)
            ->count();

        $this->data['freeTeachliteCount'] = AccessCodeEmbibe::where('type', 'teachlite')
            ->whereNull('school_id') //  only free (available to assign)
            ->count();

        if (!$request->hasCookie('perPage')) {
            return response()
                ->view('admin.accessCode.index-embibe', $this->data)
                ->cookie('perPage', $perPage, 30 * 24 * 60); // 30 days
        }
        return view('admin.accessCode.index-embibe', $this->data);
    }
    public function sendLensSms($schoolId)
    {
        try {
            $user   = User::findOrFail($schoolId);

            if (!$user || empty($user->mobile_no)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No mobile number found for this school.',
                ]);
            }

            // Count from access codes to determine what to send
            $mittleance = AccessCodeEmbibe::where('school_id', $schoolId)
                ->where('type', 'mittlense')->count();

            $techlite = AccessCodeEmbibe::where('school_id', $schoolId)
                ->where('type', 'teachlite')->count();

            if ($mittleance <= 0 && $techlite <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No lens codes assigned to this school.',
                ]);
            }

            if ($mittleance > 0 && $techlite > 0) {
                $templateId  = '1707177547118656752';
                $templateKey = 'Welcome teachlite and mittlens';
                $message     = "Congratulations! Your Mittlens and Teachlite licenses are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Mittlens and Teachlite licenses offered with your purchase. Mittsure";
            } elseif ($techlite > 0) {
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
                triggeredBy: 'manualLensSms',
                status: 'sent',
                senderUserId: auth()->id(),
                relatedSchoolId: $user->id,
            );

            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully.',
            ]);
        } catch (\Exception $e) {
            dd($e);
            \Log::error('sendLensSms error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ]);
        }
    }
    // public function sendLensSms($schoolId)
    // {
    //     try {
    //         $school = Schools::with('user')->findOrFail($schoolId);
    //         $user   = $school->user;

    //         if (!$user || empty($user->mobile_no)) {
    //             return response()->json(['success' => false, 'message' => 'No mobile number found for this school.']);
    //         }

    //         $addon = \App\Models\CrmSchoolAddon::where('user_id', $user->id)
    //             ->where(fn($q) => $q->where('mittleance', '>', 0)->orWhere('techlite', '>', 0))
    //             ->first();

    //         if (!$addon) {
    //             return response()->json(['success' => false, 'message' => 'No lens data found.']);
    //         }

    //         $mittleance = $addon->mittleance ?? 0;
    //         $techlite   = $addon->techlite   ?? 0;

    //         if ($mittleance > 0 && $techlite > 0) {
    //             $templateId  = '1707177547118656752';
    //             $templateKey = 'Welcome teachlite and mittlens';
    //             $message     = "Congratulations! Your Mittlens and Teachlite licenses are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Mittlens and Teachlite licenses offered with your purchase. Mittsure";
    //         } elseif ($techlite > 0) {
    //             $templateId  = '1707177547112734409';
    //             $templateKey = 'Welcome Teachlite';
    //             $message     = "Congratulations! Your Teachlite licenses are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Teachlite licenses offered with your purchase. Mittsure";
    //         } else {
    //             $templateId  = '1707177547108138952';
    //             $templateKey = 'Welcome Mittlens';
    //             $message     = "Congratulations! Your Mittlens are available in your Mittlearn LMS account. Please login to the Mittlearn LMS portal to access Mittlens licenses offered with your purchase. Mittsure";
    //         }

    //         sendSms($user->mobile_no, null, null, $templateKey, $message, $templateId);

    //         $this->logSms(
    //             sentTo: $user->mobile_no,
    //             templateKey: $templateKey,
    //             message: $message,
    //             triggeredBy: 'manualLensSms',
    //             status: 'sent',
    //             senderUserId: auth()->id(),
    //             relatedSchoolId: $school->id,
    //         );

    //         // Mark SMS as sent in addon record
    //         \App\Models\CrmSchoolAddon::where('user_id', $user->id)->update(['lens_sms_sent' => 1]);

    //         return response()->json(['success' => true, 'message' => 'SMS sent successfully.']);
    //     } catch (\Exception $e) {
    //         \Log::error('sendLensSms error: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Something went wrong.']);
    //     }
    // }

    // This feature isn’t activated for this session.
    public function index_for_all_old(Request $request)
    {
        // Start with a base query
        $this->data['schools']     = Schools::where('is_verified_by_admin', 1)->get();
        $this->data['book_series'] = BookSeries::where('is_active', 1)->get();
        $query                     = AccessCode::with('user', 'bookSeries');

        // Apply search filters if provided
        if ($request->filled('access_code')) {
            $query->where('access_code', 'like', '%' . $request->access_code . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('generated_by')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->generated_by . '%');
            });
        }

        if ($request->filled('school_name')) {
            $query->where('school_id', $request->school_name);
        }

        if ($request->filled('book_series_name')) {
            $query->where('book_series_id', $request->book_series_name);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('start_date', '>=', $request->start_date)
                ->whereDate('end_date', '<=', $request->end_date);
        }

        // Check if any search filters are applied
        $isFiltered = $request->filled('access_code') ||
            $request->filled('status') ||
            $request->filled('generated_by') ||
            $request->filled('school_name') ||
            $request->filled('book_series_name') ||
            ($request->filled('start_date') && $request->filled('end_date'));

        // Apply default limit or fetch all based on filters
        if ($isFiltered) {
            $this->data['accessCode'] = $query->orderBy('id', 'DESC')->get();
        } else {
            $this->data['accessCode'] = $query->orderBy('created_at', 'DESC')->limit(2000)->get();
            // $this->data['accessCode'] = $query->orderBy('id', 'DESC')->get();
        }

        return view('admin.accessCode.index', $this->data);
    }

    public function accessCodeCreate()
    {
        return view('admin.accessCode.add_edit');
    }
    public function showInfo($id)
    {
        // $accessCode = AccessCodeEmbibe::with(['usedBy', 'user', 'board', 'class', 'medium'])->find($id);
        $accessCode = AccessCodeEmbibe::find($id);
        // dd($accessCode);

        if (! $accessCode) {
            return response()->json(['error' => 'Access code not found'], 404);
        }

        return response()->json([
            'licence_key'         => $accessCode->licence_key,
            'ip' => $accessCode->ip,
            'device_id'   => $accessCode->device_id,
            'activation_date'  => $accessCode->activation_date,
            'activation_updatedAt'       => $accessCode->activation_updatedAt ?? 'N/A',
            'org_id'       => $accessCode->org_id ?? 'N/A',
            'activation_limit'        => $accessCode->activation_limit ?? 'N/A',
            'licence_expiry'        => $accessCode->licence_expiry ?? 'N/A',
            'content_bundle'       => $accessCode->content_bundle ?? 'N/A',
            'content_bundle_id'       => $accessCode->content_bundle_id ?? 'N/A',
            'notes'       => $accessCode->notes ?? 'N/A',
            'config'       => $accessCode->config ?? 'N/A',
            'requestBy'       => $accessCode->requestBy ?? 'N/A',
            'requestTeam'       => $accessCode->requestTeam ?? 'N/A',
            'requestPersonName'       => $accessCode->requestPersonName ?? 'N/A',
            'customerName'       => $accessCode->customerName ?? 'N/A',
            'platform'       => $accessCode->platform ?? 'N/A',
            'board'       => $accessCode->board ?? 'N/A',
            'grades'       => $accessCode->grades ?? 'N/A',
            'resolution'       => $accessCode->resolution ?? 'N/A',
            'license_createdAt'       => $accessCode->license_createdAt ?? 'N/A',
            'license_updatedAt'       => $accessCode->license_updatedAt ?? 'N/A',
            'type'       => $accessCode->type ?? 'N/A',
            'created_by'       => $accessCode->created_by ?? 'N/A',
        ]);
    }

    public function editAccessCode($id)
    {
        $subjects   = Subject::where('is_active', 1)->pluck('name', 'id');
        $accessCode = AccessCode::with(['user', 'school', 'board', 'medium', 'usedBy'])->findOrFail($id);
        return view('admin.accessCode.edit', compact('accessCode', 'subjects'));
    }
    // accessCodeSave is update function becuase access code saving trow livewire
    public function accessCodeSave(Request $request)
    {
        // Validate the input data
        $request->validate([
            'access_code' => 'required|string|max:255',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after:start_date',
        ]);
        // Find the AccessCode by ID
        $accessCode = AccessCode::findOrFail($request->id);
        // Update the AccessCode with new data
        $subjectIds = implode(',', $request->subject_id);

        $accessCode->update([
            'access_code' => $request->access_code,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'subject_id'  => $subjectIds,
        ]);
        // Check if start_date and end_date are within the current time and update status
        $currentTime = now();
        if (
            $request->start_date &&
            $request->end_date &&
            $currentTime->between($request->start_date, $request->end_date)
        ) {
            $accessCode->update(['status' => 'generated']);
        }

        // Redirect back with success message
        return redirect()->route('access.code.index')->with('success', config('constants.FLASH_REC_UPDATE_1'));
    }
    public function destroy($id)
    {

        // Find the accessCode by ID
        $accessCode = AccessCode::findOrFail($id);
        if ($accessCode) {
            // Check if there are user using for this accessCode
            if ($accessCode->user_id) {
                // Soft delete the accessCode
                return redirect()->back()->with(['error' => config('constants.FLASH_REC_DELETE_0')]);
            } else {
                // Force delete the accessCode since no subscriptions exist
                $accessCode->forceDelete();
                return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
            }
        }
        return redirect()->back()->with(['error' => config('constants.FLASH_REC_DELETE_0')]);
    }
    public function schoolAccessCode()
    {
        return view('admin.accessCode.school-code-access', $this->data);
    }
    public function accessCodeActivate($id)
    {
        $accessCode                                           = AccessCode::find($id);
        $accessCode->is_active === 1 ? $accessCode->is_active = 0 : $accessCode->is_active = 1;
        $accessCode->save();

        return redirect()->back()->with(['success' => config('constants.FLASH_STATUS')]);
    }

    public function exportPrint($classId)
    {
        // Fetch data for the given classId
        $accessCodes = AccessCode::with(['board', 'class', 'medium', 'usedBy', 'school'])
            ->where('class_id', $classId)
            ->get();
        // Return print-friendly view
        return view('admin.accessCode.print_access_code', ['accessCodes' => $accessCodes]);
    }
    public function exportCode(Request $request)
    {

        $type = $request->input('type');
        $ids  = explode(',', $request->input('ids'));

        // Fetch selected access codes
        $query = AccessCode::whereIn('id', $ids)->with(['board', 'class', 'medium', 'user', 'school', 'bookSeries']);

        $accessCodes = $query->get();
        if ($type === 'csv') {
            $file = Excel::raw(new AccessCodeExport($accessCodes), \Maatwebsite\Excel\Excel::CSV);
            // Return the file with custom headers using Response::download()
            return Response::make($file, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="access_codes.csv"',
            ]);
        } elseif ($type === 'excel') {
            $file = Excel::raw(new AccessCodeExport($accessCodes), \Maatwebsite\Excel\Excel::XLSX);

            return Response::make($file, 200, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="access_codes.xlsx"',
            ]);
        } elseif ($type === 'print') {
            return view('admin.accessCode.print_access_code', compact('accessCodes', 'getSetting'));
        }

        return redirect()->back()->with('error', 'Invalid export type');
    }
    public function print(Request $request)
    {
        $type       = $request->input('type');
        $ids        = explode(',', $request->input('ids'));
        $getSetting = Setting::pluck('field_value', 'field_name')->toArray();

        // Fetch selected access codes
        $query = AccessCode::whereIn('id', $ids)->with(['board', 'class', 'medium', 'user', 'school', 'bookSeries']);

        $accessCodes = $query->get();

        return view('admin.accessCode.print_access_code', compact('accessCodes', 'getSetting'));
    }
    public function exportCodeEmbibe(Request $request)
    {
        $type = $request->input('type');
        $ids  = explode(',', $request->input('ids'));

        // Fetch selected access codes
        $query = AccessCodeEmbibe::whereIn('id', $ids)->with(['school',]);

        $accessCodes = $query->get();
        if ($type === 'csv') {
            $file = Excel::raw(new AccessCodeEmbibeExport($accessCodes), \Maatwebsite\Excel\Excel::CSV);
            return Response::make($file, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="access_codes.csv"',
            ]);
        } elseif ($type === 'excel') {
            $file = Excel::raw(new AccessCodeEmbibeExport($accessCodes), \Maatwebsite\Excel\Excel::XLSX);

            return Response::make($file, 200, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="access_codes.xlsx"',
            ]);
        } elseif ($type === 'print') {
            return view('admin.accessCode.print_access_code', compact('accessCodes', 'getSetting'));
        }

        return redirect()->back()->with('error', 'Invalid export type');
    }
    public function printEmbibe(Request $request)
    {
        $type       = $request->input('type');
        $ids        = explode(',', $request->input('ids'));
        $getSetting = Setting::pluck('field_value', 'field_name')->toArray();

        // Fetch selected access codes
        $query = AccessCodeEmbibe::whereIn('id', $ids)->with(['school']);

        $accessCodes = $query->get();

        return view('admin.accessCode.print_access_code', compact('accessCodes', 'getSetting'));
    }
    public function sendCode(Request $request, $type)
    {
        $query = AccessCode::with(['board', 'class', 'medium', 'user', 'school', 'bookSeries']);

        if ($request->filled('access_code')) {
            $query->where('access_code', 'like', '%' . $request->access_code . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('generated_by')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->generated_by . '%');
            });
        }

        if ($request->filled('school_name')) {
            $query->where('school_id', $request->school_name);
        }

        if ($request->filled('book_series_name')) {
            $query->where('book_series_id', $request->book_series_name);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('start_date', '>=', $request->start_date)
                ->whereDate('end_date', '<=', $request->end_date);
        }

        $accessCodes = $query->get();

        if ($type === 'mail') {
            $this->sendMail($request->email);
            //    dd('here');
        } else {
            $this->sendSms($request->mobile_number);
        }
    }
    private function sendMail($email)
    {
        return 'SMTP Required';

        try {
            return 'SMTP Required';
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    private function sendSms($mobileNo)
    {
        try {
            return 'SMS Api Required';
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function printSetting()
    {
        $this->data['settings'] = Setting::pluck('field_value', 'field_name')->toArray();
        return view('admin.accessCode.print_setting', $this->data);
    }
    public function printSettingSave(Request $request)
    {
        $settingsData = $request->all();
        foreach ($settingsData as $fieldName => $fieldValue) {
            Setting::updateOrInsert(
                ['field_name' => $fieldName],
                ['field_value' => $fieldValue],
            );
        }
        return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }

    public function getSchools($stateId, $cityId = null)
    {
        $query = Schools::where('state', $stateId);
        if ($cityId) {
            $query->where('city', $cityId);
        }
        $schools = $query->join('users', 'schools.user_id', '=', 'users.id')
            ->select('schools.unique_id', 'users.name', 'users.id', 'schools.postal_code')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->unique_id} : {$item->name} ({$item->postal_code})"];
            });
        return response()->json($schools);
    }

    public function assignToSchool(Request $request)
    {
        // dd($request->all());
        $schoolId = $request->input('school_id');

        $accessCodes = explode(',', $request->access_codes);

        // Update the selected access codes
        if (!empty($accessCodes)) {
            AccessCodeEmbibe::whereIn('id', $accessCodes)
                ->update([
                    'school_id' => $schoolId,
                    'status' => 1
                ]);
            return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
        }
        return redirect()->back()->with(['error' => config('constants.FLASH_REC_ADD_0')]);
    }

    public function revokeAccessCode(Request $request)
    {
        $accessCode = AccessCodeEmbibe::find($request->access_code_id);

        if ($accessCode && $accessCode->school_id && $accessCode->is_distribute == 0) {
            // Nullify the school_id (or set a revoke flag)
            $accessCode->school_id = null;
            $accessCode->status = 0;
            $accessCode->save();

            return redirect()->back()->with('success', 'Access code revoked successfully.');
        }

        return redirect()->back()->with('error', 'Unable to revoke the access code.');
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
