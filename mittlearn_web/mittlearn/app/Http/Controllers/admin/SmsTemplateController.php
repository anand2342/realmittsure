<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmailAction;
use App\Models\AlertTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsTemplateController extends Controller
{
    public $data = [];
    public function index(Request $request)
    {
        try {
            $this->data['smsData'] = AlertTemplate::where('type', 'sms')->paginate(config('constants.PAGINATION.default'));
            return view('admin.alertTemplates.sms.index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function add()
    {
        try {
            $this->data['actionOptions'] = EmailAction::pluck('action', 'action');
            $this->data['type'] = 'sms';
            return view('admin.alertTemplates.sms.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function edit($id)
    {
        try {
            $this->data['actionOptions'] = EmailAction::pluck('action', 'action');
            $this->data['emailTemplate'] = AlertTemplate::findOrFail($id);
            if (!$this->data['emailTemplate']) {
                return redirect()->back()->with(['error' => config('constants.FLASH_REC_NOT_FOUND')]);
            }
            $this->data['type'] = 'sms';
            return view('admin.alertTemplates.sms.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function save(Request $request)
    {
        $sanitizedData = $request->all();
        $validator = Validator::make($sanitizedData, [
            'name' => "required|max:255|unique:alert_templates,name,{$request->id}",
            'subject' => "required|max:255|unique:alert_templates,subject,{$request->id}",
            'body' => 'required',
            'cc' => 'required',
            'bcc' => 'required',
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
            return redirect()->route('sms-template.index')->with(['success' => $success]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}
