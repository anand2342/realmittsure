<?php
namespace App\Http\Controllers\userPortal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\FileController;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserMyCoursesController extends Controller
{
    public $data     = [];
    public $res      = [];
    public $coreCtrl = '';
    public $fileCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
        $this->fileCtrl = FileController::class;
    }
    public function myCourses(Request $request)
    {
        $request->merge(['from' => 'web']);
        $this->data['courses']     = $this->coreCtrl::getUserMyCourses($request);
        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
        $this->coreCtrl::storeStudentOverviewSection($request);


        return view('userPortal.myCourses.my-courses', $this->data);
    }
    public function coursesListing(Request $request, $slug)
    {
        $request->merge(['from' => 'web']);
        $this->data['courses'] = $this->coreCtrl::getUserMyCoursesListing($request, $slug);
        $this->coreCtrl::storeStudentOverviewSection($request);


        return view('userPortal.myCourses.my-courses-listing', $this->data);
    }
    public function coursesChapterListing(Request $request, $slug, $id)
    {
        $request->merge(['from' => 'web']);
        // return $this->data;
        $this->data['data'] = $this->coreCtrl::getUserMyCoursesChapterListing($request, $id);
        $this->coreCtrl::storeStudentOverviewSection($request);
        $this->fileCtrl::saveUserVideoDurationOnPageLoad($this->data['data']['coursesChapter']);

        return view('userPortal.myCourses.courses-chapter-listing', $this->data);
    }
    public function courseDigitalContent(Request $request, $id)
    {
        $request->merge(['from' => 'web']);
        $this->data['data'] = $this->coreCtrl::getCourseDigitalContent($request, $id);
        $this->coreCtrl::storeStudentOverviewSection($request);

        // Generate signed URLs for videos
        foreach ($this->data['data']['chapters'] as &$chapter) { // Use reference (&) to modify the array directly
            foreach ($chapter['chapterListing'] as &$file) {
                if (in_array($file['file_extension'], [ 'mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf'])) {
                    $file['signed_url'] = FileController::sendOnSignedRoute($file['attachment_file']);
                }
            }
        }

        return view('userPortal.myCourses.course-digital-content', $this->data);
    }

    public function nonAcadCourseDetail(Request $request)
    {
        return view('userPortal.myCourses.non-acad-course-detail');
    }
    public function getChapterDetails($id)
    {
        $chapter = CourseChapter::find($id);

        if (! $chapter) {
            return response()->json(['error' => 'Chapter not found'], 404);
        }

        // Fetch associated media files

        $files = MediaFiles::where('tbl_id', $id)
            ->where('type', 'course_chapter')
            ->get()
            ->map(function ($file) {
                return [
                    'file_name' => $file->original_name,
                    'video_id'  => $file->id,
                    'file_path' => Storage::url('uploads/course_chapter_files/' . $file->attachment_file),
                    'file_type' => $file->file_extension,
                ];
            });
        $supportingFolder = MediaFiles::where('tbl_id', $chapter->supporting_folder_id)->where('type', 'content_upload')->get()->map(function ($supportingFolder) {
            return [
                'file_name' => $supportingFolder->original_name,
                'file_path' => Storage::url('/uploads/media-files/' . $supportingFolder->attachment_file),
                'file_type' => $supportingFolder->file_extension,
            ];
        });
        return response()->json([
            'chapter_name'     => $chapter->chapter_name,
            'description'      => $chapter->chapter_description,
            'course_id'        => $chapter->course_id,
            'chapter_id'       => $chapter->id,
            // 'video_url'     => $chapter->video_url ? asset('uploads/course_chapter_files/' . $chapter->video_url) : null,
            'files'            => $files,
            'supportingFolder' => $supportingFolder,
        ]);
    }
}