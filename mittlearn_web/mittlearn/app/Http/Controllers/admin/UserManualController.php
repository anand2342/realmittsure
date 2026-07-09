<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\UserManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserManualController extends Controller
{
    public $data = [];

    public function index()
    {
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $this->data['data'] = UserManual::orderBy('id', 'DESC')->paginate($perPageRecords);
        return view('admin.userManual.index', $this->data);
    }
    public function add()
    {
        $this->data['roles'] = Role::where('is_active', 1)->whereIn('role_slug', ['school_admin', 'school_teacher'])->pluck('role_name', 'role_slug')->toArray();
        return view('admin.userManual.add', $this->data);
    }
    public function edit($id)
    {
        $this->data['data']  = UserManual::where('id', $id)->first();
        $this->data['roles'] = Role::where('is_active', 1)->whereIn('role_slug', ['school_admin', 'school_teacher'])->pluck('role_name', 'role_slug')->toArray();
        // return $this->data;
        return view('admin.userManual.add', $this->data);
    }
    public function save(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'title'         => 'required|string|max:555',
                'pdf_path'      => 'nullable|file|mimes:pdf',
                'video_path'      => 'nullable|file',
                'visible_to_roles' => 'required|array',
            ]);

            // Convert visible_to_roles array to comma-separated string
            $roleVisibilityString = implode(',', $request->visible_to_roles);


            $manual = UserManual::find($request->id);
            $filename = $manual->pdf_path ?? null;
            $videoFilename = $manual->video_path ?? null;

            // Handle PDF upload
            if ($request->hasFile('pdf_path')) {
                if ($manual && Storage::disk('public')->exists('uploads/user_manuals/' . $manual->pdf_path)) {
                    Storage::disk('public')->delete('uploads/user_manuals/' . $manual->pdf_path);
                }

                $file = $request->file('pdf_path');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->put('uploads/user_manuals/' . $filename, file_get_contents($file));
            }
            if ($request->hasFile('video_path')) {
                if ($manual && Storage::disk('public')->exists('uploads/user_manuals/' . $manual->video_path)) {
                    Storage::disk('public')->delete('uploads/user_manuals/' . $manual->video_path);
                }

                $videoFile = $request->file('video_path');
                $videoFilename = time() . '.' . $videoFile->getClientOriginalExtension();
                Storage::disk('public')->put('uploads/user_manuals/' . $videoFilename, file_get_contents($videoFile));
            }

            // Save or update the user manual
            UserManual::updateOrCreate(
                ['id' => $request->id],
                [
                    'title'            => $request->title,
                    'description'      => $request->description,
                    'pdf_path'         => $filename,
                    'video_path'         => $videoFilename,
                    'created_by'          => Auth::id(), // Set default if not provided
                    'visible_to_roles'  => $roleVisibilityString,
                    'is_active'        =>  $request->is_active,
                    'created_by'       => auth()->id(),
                ]
            );

            return redirect()->route('user-manual.index')
                ->with('success', config('constants.FLASH_REC_ADD_1'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', config('constants.FLASH_TRY_CATCH'));
        }
    }


    public function delete($id)
    {
        $data = UserManual::find($id);

        if ($data) {
            $data->delete();
            return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } else {
            return redirect()->back()->with(['error' => 'User manual not found.']);
        }
    }
}
