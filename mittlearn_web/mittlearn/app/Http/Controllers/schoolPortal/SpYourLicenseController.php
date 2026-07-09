<?php

namespace App\Http\Controllers\schoolPortal;

use App\Exports\AccessCodeEmbibeExport;
use App\Http\Controllers\Controller;
use App\Mail\AccessCodeMittlenseMail;
use App\Models\AccessCodeEmbibe;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class SpYourLicenseController extends Controller
{
    public $data = [];

    public function yourLicense(Request $request)
    {
        $query = AccessCodeEmbibe::with('usedAccessCodes')->where('school_id', Auth::id())
            ->orderBy('id', 'DESC');

        $this->data['accessCodesCounts'] = $query->count();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
            $this->data['accessCode'] = $query->get();
        }

        $this->data['selectedType'] = $request->type ?? null; // Store selected type for view

        // Fetch already assigned user IDs
        $assignedUserIds = AccessCodeEmbibe::where('type', 'teachlite')->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();

        // Fetch user list based on type, excluding assigned users
        if ($request->type === 'teachlite') {
            $this->data['userList'] = User::whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', Auth::id());
            })->whereNotIn('id', $assignedUserIds)->get();
        } elseif ($request->type === 'mittlense') {
            $this->data['userList'] = User::whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_student')
                    ->where('school_id', Auth::id());
            })->whereNotIn('id', $assignedUserIds)->get();
        } else {
            $this->data['userList'] = collect(); // Empty collection if no type is selected
        }

        $this->data['classes'] = getUserSchoolClasses(Auth::id());

        return view('schoolPortal.yourLicense.your-license', $this->data);
    }

    public function exportCodeEmbibe(Request $request)
    {

        $type = $request->input('type');
        $ids  = explode(',', $request->input('ids'));

        // Fetch selected access codes
        $query = AccessCodeEmbibe::whereIn('id', $ids)->with(['school']);

        $accessCodes = $query->get();
        if ($type === 'csv') {
            $file = Excel::raw(new AccessCodeEmbibeExport($accessCodes), \Maatwebsite\Excel\Excel::CSV);
            // Return the file with custom headers using Response::download()
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
            return view('schoolPortal.yourLicense.sp_print_access_code', compact('accessCodes', 'getSetting'));
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

        return view('schoolPortal.yourLicense.sp_print_access_code', compact('accessCodes', 'getSetting'));
    }

    public function getClassUsers(Request $request)
    {
        $parentId = Auth::id();
        $assignedUserIds = AccessCodeEmbibe::where('type', 'mittlense')->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();

        $users    = User::where('status', 1)->whereHas('studentDetails', function ($query) use ($parentId, $request) {
            $query->where('parent_id', $parentId)
                ->where('class', $request->class_id);
        })->whereNotIn('id', $assignedUserIds)->pluck('name', 'id');

        return response()->json($users);
    }

    public function sendAccessCodeMittlense(Request $request)
    {
        $user       = User::find($request->user_id);
        $accessCode = AccessCodeEmbibe::find($request->access_code_id);

        $templateId = 28;
        $data = [
            'ACCESS_CODE' => $accessCode->licence_key,
        ];
        if ($accessCode && $user) {

            if (sendEmail($templateId, $user->email, $data)) {
                // Update access code status after sending
                if ($accessCode && $user) {
                    $accessCode->is_distribute = 1;
                    $accessCode->user_id = $request->user_id;
                    $accessCode->save();
                }
                return response()->json(['message' => 'Access code sent successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Something went wrong.']);
            }
        }
    }
    public function saveAccessCodesTeachlite(Request $request)
    {
        // Ensure count matches
        if (count($request->user_ids) !== count($request->access_code_ids)) {
            return response()->json(['success' => false, 'message' => 'Mismatch between users and access codes.']);
        }

        foreach ($request->user_ids as $index => $userId) {
            $accessCodeId = $request->access_code_ids[$index];

            $accessCode = AccessCodeEmbibe::find($accessCodeId);
            $user = User::find($userId);
            $templateId = 28;
            $data = [
                'ACCESS_CODE' => $accessCode->licence_key,
            ];
            if ($accessCode && $user) {
                if (sendEmail($templateId, $user->email, $data)) {

                    // Update existing record
                    $accessCode->update([
                        'user_id' => $userId,
                        'is_distribute' => 1
                    ]);
                    return response()->json(['message' => 'Access code sent successfully']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Something went wrong.']);
                }
            }
            // Mail::to($user->email)->send(new AccessCodeMittlenseMail($accessCode));

        }
        return response()->json(['success' => true, 'message' => 'Access codes updated and emails sent successfully!']);
    }
}
