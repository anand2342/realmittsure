<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnquiriesController extends Controller
{
    public $data = [];
    public function allEnquiries(Request $request)
    {
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $query = ContactInquiry::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }

        if ($request->filled('mobile')) {
            $query->where('mobile_no', $request->mobile);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $this->data['enquiry'] = $query->orderByRaw('status = 0 DESC')->orderBy('created_at', 'DESC')->paginate($perPageRecords);
        return view('admin.enquiries.index', $this->data);
    }
    public function enquiryView($id)
    {
        $this->data['data'] = ContactInquiry::where('id', $id)->update(['read_at' => now()]);
        $this->data['data'] = ContactInquiry::where('id', $id)->first();

        return view('admin.enquiries.view', $this->data);
    }
    public function enquirySave(Request $request, $id)
    {
        $res = ContactInquiry::where('id', $id)->update(['reply_subject' => $request->reply_subject, 'status' => '1', 'response_message' => $request->reply_message, 'resolved_by' =>  Auth::id(), 'resolved_at' =>  now()]);
        $subject = $request->reply_subject;
        $description = $request->reply_message;
        $recipient = $request->email;
        // Mail::to($recipient)->send(new TriggerEmail($subject, $description));
        if ($res) {
            return redirect()->route('enquiries')->with(['success' => "Replied successfully"]);
        }
        return redirect()->back()->with(['error' => config('constants.FLASH_REC_ADD_0')]);
    }
}
