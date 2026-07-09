<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFiles;
use App\Models\MediaFolder;
use App\Models\Schools;
use App\Models\UserAdditionalDetail;
use Illuminate\Http\Request;

class UploadedContentController extends Controller
{
    public $data = [];

    public function folderListing(Request $request)
    {
        try {
            $this->data['schools'] = Schools::where('is_verified_by_admin', 1)
                ->pluck('name', 'user_id')
                ->toArray();

            $query = MediaFolder::with('fileCount');

            /*
        |--------------------------------------------------------------------------
        | Case 1: Direct user_id in URL (first-time entry)
        |--------------------------------------------------------------------------
        */
            if ($request->filled('user_id')) {

                // Check if user is a school
                $school = Schools::where('user_id', $request->user_id)->first();

                if ($school) {
                    $query->where('parent_id', $school->user_id);
                } else {
                    // Otherwise treat as teacher
                    $query->where('parent_id', $request->user_id);
                }

                /*
        |--------------------------------------------------------------------------
        | Case 2: Filter by teacher
        |--------------------------------------------------------------------------
        */
            } elseif ($request->filled('teacher_id')) {

                $query->where('parent_id', $request->teacher_id);

                /*
        |--------------------------------------------------------------------------
        | Case 3: Filter by school
        |--------------------------------------------------------------------------
        */
            } elseif ($request->filled('school_id')) {

                $query->where('parent_id', $request->school_id);
            }

            $this->data['folderListing'] = $query
                ->orderBy('media_folders.id', 'DESC')
                ->paginate(config('constants.PAGINATION.default'));

            return view('admin.uploadedContent.index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function getTeacheListBySchool($id)
    {
        $teachers = UserAdditionalDetail::with('user')->where('role', 'school_teacher')->where('school_id', $id)->get()
            ->pluck('user.name', 'user.id');
        return response()->json($teachers);
    }
    public function filesListing($id)
    {
        try {
            $this->data['folder'] = MediaFolder::find($id);
            $this->data['filesListing'] = MediaFiles::where('tbl_id', $id)->where('type', 'content_upload')->get();
            // return $this->data['folderListing'];
            return view('admin.uploadedContent.folder_files_view', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}
