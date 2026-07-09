<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherDevelopmentContent;
use App\Models\TeacherDevelopmentVideo;
use App\Models\Schools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherDevContentController extends Controller
{
    public $data = [];

    // INDEX
    public function index()
    {
        $this->data['contents'] = TeacherDevelopmentContent::withCount('videos')->latest()->get();
        return view('admin.teacher-development.index', $this->data);
    }

    // CREATE
    public function create()
    {
        $data['schools'] = Schools::pluck('name', 'id');
        return view('admin.teacher-development.add', $data);
    }

    // STORE
    public function store(Request $request)
    {
        ini_set('pcre.backtrack_limit', '100000000');
        ini_set('pcre.recursion_limit', '100000000');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        ini_set('max_input_time', '600');
        ini_set('post_max_size', '1024M');
        ini_set('upload_max_filesize', '1024M');

        $request->validate([
            'title'          => 'required',
            'type'          => 'required',
            'videos'         => 'required|array|min:1',
            'videos.*.title' => 'required',
            'videos.*.file'  => 'nullable|file',
        ]);
        // dd($request->videos);

        $content = TeacherDevelopmentContent::create([
            'title'              => $request->title,
            'type'              => $request->type,
            'description'        => $request->description,
            'is_for_all_schools' => $request->is_for_all ? 1 : 0,
            'is_active'          => $request->is_active ?? 1,
        ]);

        // Assign schools if not for all
        if (!$request->is_for_all && !empty($request->school_ids)) {
            $content->schools()->sync($request->school_ids);
        }
        // Save videos — use values() to reset keys so gaps don't matter
        foreach (collect($request->videos)->values() as $video) {
            $filePath = $this->uploadVideoFile($video['file'] ?? null);

            TeacherDevelopmentVideo::create([
                'content_id'  => $content->id,
                'video_title' => $video['title'] ?? null,
                'video_file'  => $filePath,
                'order'       => $video['order'] ?? 0,  // ← add this
            ]);
        }

        return redirect()->route('teacher.development.index')
            ->with('success', 'Content Created Successfully');
    }

    // EDIT
    public function edit($id)
    {
        $data['content'] = TeacherDevelopmentContent::with(['videos', 'schools'])->findOrFail($id);
        $data['schools']  = Schools::pluck('name', 'id');
        return view('admin.teacher-development.add', $data);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'          => 'required',
            'type'          => 'required',
            'videos'         => 'required|array|min:1',
            'videos.*.title' => 'required',
            'videos.*.file'  => 'nullable|file',
        ]);

        $content = TeacherDevelopmentContent::findOrFail($id);

        $content->update([
            'title'              => $request->title,
            'type'              => $request->type,
            'description'        => $request->description,
            'is_for_all_schools' => $request->is_for_all ? 1 : 0,
            'is_active'          => $request->is_active ?? $content->is_active,
        ]);

        // Assign schools
        if ($request->is_for_all) {
            $content->schools()->detach();
        } else {
            $content->schools()->sync($request->school_ids ?? []);
        }

        // --------------------------------------------------
        // Smart video update:
        // - If a new file is uploaded  → delete old, save new
        // - If no new file uploaded    → keep existing file path
        // - If video_id present        → it's an existing record
        // - If no video_id             → it's a brand new row
        // --------------------------------------------------
        $submittedVideoIds = [];

        foreach (collect($request->videos)->values() as $video) {
            $videoId      = $video['video_id'] ?? null; // hidden field from blade
            $existingPath = $video['existing_file'] ?? null; // hidden field from blade
            $newFile      = $video['file'] ?? null;

            if ($videoId) {
                // UPDATE existing record
                $videoRecord = TeacherDevelopmentVideo::find($videoId);

                if ($videoRecord) {
                    $filePath = $existingPath; // default: keep old path

                    if ($newFile instanceof \Illuminate\Http\UploadedFile && $newFile->isValid()) {
                        // Delete old file
                        if ($videoRecord->video_file && Storage::disk('public')->exists($videoRecord->video_file)) {
                            Storage::disk('public')->delete($videoRecord->video_file);
                        }
                        $filePath = $this->uploadVideoFile($newFile);
                    }
                    $videoRecord->update([
                        'video_title' => $video['title'] ?? null,
                        'video_file'  => $filePath,
                        'order'       => $video['order'] ?? 0,  // ← add this
                    ]);

                    $submittedVideoIds[] = $videoRecord->id;
                }
            } else {
                // INSERT new record
                $filePath = $this->uploadVideoFile($newFile);

                $newRecord = TeacherDevelopmentVideo::create([
                    'content_id'  => $id,
                    'video_title' => $video['title'] ?? null,
                    'video_file'  => $filePath,
                    'order'       => $video['order'] ?? 0,  // ← add this
                ]);

                $submittedVideoIds[] = $newRecord->id;
            }
        }

        // Delete videos that were removed in the form
        $removedVideos = TeacherDevelopmentVideo::where('content_id', $id)
            ->whereNotIn('id', $submittedVideoIds)
            ->get();

        foreach ($removedVideos as $removed) {
            if ($removed->video_file && Storage::disk('public')->exists($removed->video_file)) {
                Storage::disk('public')->delete($removed->video_file);
            }
            $removed->delete();
        }

        return redirect()->route('teacher.development.index')
            ->with('success', 'Content Updated Successfully');
    }

    // DELETE
    public function destroy($id)
    {
        $content = TeacherDevelopmentContent::with('videos')->findOrFail($id);

        foreach ($content->videos as $video) {
            if ($video->video_file && Storage::disk('public')->exists($video->video_file)) {
                Storage::disk('public')->delete($video->video_file);
            }
        }

        $content->delete();

        return back()->with('success', 'Content Deleted Successfully');
    }

    public function assignSchoolsModal($id)
    {
        $content = TeacherDevelopmentContent::with('schools')->findOrFail($id);
        $schools = Schools::pluck('name', 'id');

        // Returns only the partial view (no layout)
        return view('admin.teacher-development.assign-schools', compact('content', 'schools'));
    }

    // REPLACE your existing saveAssignedSchools with this
    // Returns JSON for AJAX, works for normal POST too
    public function saveAssignedSchools(Request $request, $id)
    {
        $content = TeacherDevelopmentContent::findOrFail($id);

        if ($request->is_for_all) {
            $content->update(['is_for_all_schools' => 1]);
            $content->schools()->detach();
        } else {
            $content->update(['is_for_all_schools' => 0]);
            // Filter out empty string sent as fallback when no checkboxes are checked
            $schoolIds = collect($request->school_ids ?? [])->filter()->values()->toArray();
            $content->schools()->sync($schoolIds);
        }

        // AJAX request → return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Schools assigned successfully',
                'is_for_all' => (bool) $content->is_for_all_schools,
            ]);
        }

        // Normal POST fallback (non-AJAX)
        return back()->with('success', 'Schools assigned successfully');
    }

    // PRIVATE HELPER
    private function uploadVideoFile($file): ?string
    {
        if (!$file instanceof \Illuminate\Http\UploadedFile || !$file->isValid()) {
            return null;
        }

        $filename        = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = storage_path('app/public/uploads/teacher_dev_content');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        return 'uploads/teacher_dev_content/' . $filename;
    }
}
