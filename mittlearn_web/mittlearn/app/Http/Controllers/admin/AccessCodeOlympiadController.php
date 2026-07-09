<?php

namespace App\Http\Controllers\admin;

use App\Exports\AccessCodeExport;
use App\Exports\AccessCodeEmbibeExport;
use App\Exports\OlympiadAccessCodeExport;
use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\AccessCodeEmbibe;
use App\Models\AccessCodeOlympiad;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\Classes;
use App\Models\Schools;
use App\Models\Setting;
use App\Models\State;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class AccessCodeOlympiadController extends Controller
{
    public $data = [];

    // This feature isn’t activated for this session.
    public function index(Request $request)
    {
        // Start with a base query
        $this->data['schools']     = Schools::where('is_verified_by_admin', 1)->get();
        $this->data['book_series'] = BookSeries::where('slug', 'olympiad')->first();
        $classSubjects = json_decode($this->data['book_series']->class_subjects);
        $classIds = collect($classSubjects)->pluck('class_id')->toArray();
        $this->data['class'] = Classes::whereIn('id', $classIds)->pluck('name', 'id')->toArray();
        $allSubjectIds = collect($classSubjects)
            ->pluck('subject_ids')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        $this->data['subject'] = Subject::where('is_active', '1')
            ->whereIn('id', $allSubjectIds)
            ->pluck('name', 'id')
            ->toArray();
        $query = AccessCodeOlympiad::with('user', 'bookSeries');

        // Apply search filters if provided
        if ($request->filled('access_code')) {
            $query->where('access_code', 'like', '%' . $request->access_code . '%');
        }

        if (request('usage_status') === 'used') {
            $query->whereNotNull('user_id');
        }

        if (request('usage_status') === 'unused') {
            $query->whereNull('user_id');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('generated_by')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->generated_by . '%');
            });
        }

        if ($request->filled('book_series_name')) {
            $query->where('book_series_id', $request->book_series_name);
        }

        if ($request->filled('generation_date') && $request->filled('expiration_date')) {
            $query->whereDate('generation_date', '>=', $request->generation_date)
                ->whereDate('expiration_date', '<=', $request->expiration_date);
        }
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));



        // Check if any search filters are applied
        $isFiltered = $request->filled('access_code') ||
            $request->filled('is_active') ||
            $request->filled('usage_status') ||
            $request->filled('subject_id') ||
            $request->filled('class_id') ||
            ($request->filled('generation_date') && $request->filled('expiration_date'));

        // Apply default limit or fetch all based on filters
        if ($isFiltered) {
            $this->data['accessCode'] = $query->orderBy('id', 'DESC')->paginate($perPageRecords);
        } else {
            // $this->data['accessCode'] = $query->orderBy('id', 'DESC')->limit(2000)->get();
            $this->data['accessCode'] = $query->orderBy('id', 'DESC')->paginate($perPageRecords);
        }

        return view('admin.accessCode.index-olympiad', $this->data);
    }

    public function editAccessCode($id)
    {
        $classes   = Classes::where('is_active', 1)->pluck('name', 'id');
        $subjects   = Subject::where('is_active', 1)->pluck('name', 'id');
        $accessCode = AccessCodeOlympiad::with(['user', 'usedBy'])->findOrFail($id);
        return view('admin.accessCode.edit-olympiad', compact('accessCode', 'classes', 'subjects'));
    }
    // accessCodeSave is update function becuase access code saving trow livewire
    public function accessCodeSave(Request $request)
    {
        // Validate the input data
        $request->validate([
            'access_code' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'expiration_date'    => 'nullable|date|after:today',
        ]);

        // Find the AccessCode by ID
        $accessCode = AccessCodeOlympiad::findOrFail($request->id);
        // Update the AccessCode with new data
        $accessCode->update([
            'access_code' => $request->access_code,
            'class_id'  => $request->class_id,
            'subject_id'  => $request->subject_id,
            'expiration_date'    => $request->expiration_date,
            'created_by'          => auth()->id(),
        ]);

        // Redirect back with success message
        return redirect()->route('access.code.olympiad.index')->with('success', config('constants.FLASH_REC_UPDATE_1'));
    }

    public function accessCodeActivate($id)
    {
        $accessCode = AccessCodeOlympiad::find($id);
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
        $query = AccessCodeOlympiad::whereIn('id', $ids)->with(['class', 'subject', 'user', 'bookSeries']);

        $accessCodes = $query->get();
        if ($type === 'csv') {
            $file = Excel::raw(new OlympiadAccessCodeExport($accessCodes), \Maatwebsite\Excel\Excel::CSV);
            // Return the file with custom headers using Response::download()
            return Response::make($file, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="olympiad_access_codes.csv"',
            ]);
        } elseif ($type === 'excel') {
            $file = Excel::raw(new OlympiadAccessCodeExport($accessCodes), \Maatwebsite\Excel\Excel::XLSX);

            return Response::make($file, 200, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="olympiad_access_codes.xlsx"',
            ]);
        } elseif ($type === 'print') {
            return view('admin.accessCode.print_access_code', compact('accessCodes', 'getSetting'));
        }

        return redirect()->back()->with('error', 'Invalid export type');
    }
    public function print(Request $request)
    {
        $olympiadSetting = [
            'paper_size',
            'orientation',
            'margin_top',
            'margin_bottom',
            'margin_left',
            'margin_right',
            'blocks_per_row',
            'blocks_per_column',
            'blocks_width',
            'blocks_height',
            'block_padding',
            'block_border',
            'font_family',
            'font_size',
            'text_align',
            'custom_width',
            'custom_height',
        ];
        $type       = $request->input('type');
        $ids        = explode(',', $request->input('ids'));
        $getSetting = Setting::whereIn('field_name', $olympiadSetting)->pluck('field_value', 'field_name')->toArray();
        // Fetch selected access codes
        $query = AccessCodeOlympiad::whereIn('id', $ids);

        $accessCodes = $query->get();

        return view('admin.accessCode.print_olympiad-code', compact('accessCodes', 'getSetting'));
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
    public function olympiadPrintSetting(Request $request)
    {
        $this->data['settings'] = Setting::pluck('field_value', 'field_name')->toArray();

        return view('admin.accessCode.olympiad-print', $this->data);
    }
    public function revokeAccessCode(Request $request)
    {
        $accessCode = AccessCodeOlympiad::find($request->access_code_id);

        if ($accessCode && $accessCode->user_id) {
            // Nullify the user_id (or set a revoke flag)
            $accessCode->user_id = null;
            $accessCode->status = 'generated';
            $accessCode->save();

            return redirect()->back()->with('success', 'Access code revoked successfully.');
        }

        return redirect()->back()->with('error', 'Unable to revoke the access code.');
    }
}
