<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmailAction;
use App\Models\AlertTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    public $data = [];
    public function listTemplate(Request $request)
    {
        try {
            $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

            $this->data['emails'] = AlertTemplate::where('type', 'email')->paginate($perPageRecords);
            return view('admin.alertTemplates.email.index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function addTemplate()
    {
        try {
            $this->data['actionOptions'] = EmailAction::pluck('action', 'action');
            $this->data['type'] = 'email';
            return view('admin.alertTemplates.email.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function editTemplate($id)
    {
        try {
            $this->data['actionOptions'] = EmailAction::pluck('action', 'action');
            $this->data['emailTemplate'] = AlertTemplate::findOrFail($id);
            if (!$this->data['emailTemplate']) {
                return redirect()->back()->with(['error' => config('constants.FLASH_REC_NOT_FOUND')]);
            }
            $this->data['type'] = 'email';
            return view('admin.alertTemplates.email.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function saveTemplate(Request $request)
    {

        $sanitizedData = $request->all();
        $validator = Validator::make($sanitizedData, [
            'name' => "required|max:255|unique:alert_templates,name,{$request->id}",
            'subject' => "required|max:255|unique:alert_templates,subject,{$request->id}",
            'body' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if ($request->id > 0) {
                $success = config('constants.FLASH_REC_UPDATE_1');
                $error = config('constants.FLASH_REC_UPDATE_0');
            } else {
                $success = config('constants.FLASH_REC_ADD_1');
                $error = config('constants.FLASH_REC_ADD_0');
            }
            $this->data['emailTemplate'] = AlertTemplate::updateOrCreate(['id' => $request->id], $request->except(['_token']));
            if (is_null($this->data['emailTemplate'])) {
                return redirect()->back()->with(['error' => $error]);
            }
            return redirect()->route('email-template.index')->with(['success' => $success]);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}
