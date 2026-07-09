<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationAlert;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class NotificationFlashAlertController extends Controller
{
    public $data = [];

    public function index()
    {
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $this->data['data'] = NotificationAlert::orderBy('id', 'DESC')->paginate($perPageRecords);
        return view('admin.notificationFlashAlerts.index', $this->data);
    }
    public function add()
    {
        $this->data['roles'] = Role::where('is_active', 1)->whereIn('role_slug', ['school_admin', 'school_teacher', 'school_student', 'b2c_student', 'd2c_user'])->pluck('role_name', 'role_slug')->toArray();
        return view('admin.notificationFlashAlerts.add', $this->data);
    }
    public function edit($id)
    {
        $this->data['data']  = NotificationAlert::where('id', $id)->first();
        $this->data['roles'] = Role::where('is_active', 1)->whereIn('role_slug', ['school_admin', 'school_teacher', 'school_student', 'b2c_student', 'd2c_user'])->pluck('role_name', 'role_slug')->toArray();
        // return $this->data;
        return view('admin.notificationFlashAlerts.add', $this->data);
    }
    public function save(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'message'         => 'required',
                'role_visibility' => 'required|array',
            ]);

            // Convert role_visibility array to comma-separated string
            $roleVisibilityString = implode(',', $request->role_visibility);

            // Deactivate all other active alerts (only one should be active)
            NotificationAlert::where('is_active', 1)
                ->when($request->id, function ($query) use ($request) {
                    $query->where('id', '!=', $request->id);
                })
                ->update(['is_active' => 0]);

            // Find existing alert by ID if updating
            $existingMarketingBanner = NotificationAlert::find($request->id);
            $filename = $existingMarketingBanner->marketing_banner ?? null;

            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($existingMarketingBanner && Storage::disk('public')->exists('uploads/marketing_banner/' . $existingMarketingBanner->marketing_banner)) {
                    Storage::disk('public')->delete('uploads/marketing_banner/' . $existingMarketingBanner->marketing_banner);
                }

                // Upload new image
                $marketingBanner = $request->file('image');
                $extension = $marketingBanner->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                Storage::disk('public')->put('uploads/marketing_banner/' . $filename, file_get_contents($marketingBanner));
            }

            // Save or update the Notification Alert
            NotificationAlert::updateOrCreate(
                ['id' => $request->id],
                [
                    'marketing_banner' => $filename,
                    'message'          => $request->message,
                    'redirection_url'  => $request->redirection_url,
                    'created_by'       => auth()->id(),
                    'role_visibility'  => $roleVisibilityString,
                    'is_active'        => 1, // Always set the current alert as active
                ]
            );

            return redirect()->route('flash.notification.alerts')->with('success', config('constants.FLASH_REC_ADD_1'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function delete($id)
    {
        $data = NotificationAlert::find($id);

        if ($data) {
            $data->delete();
            return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } else {
            return redirect()->back()->with(['error' => 'Holiday not found.']);
        }
    }
}
