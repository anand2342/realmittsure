<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\FileController;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use App\Models\Planner;
use App\Models\SchoolCompletedPlanner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MittCoursesController extends Controller
{
    public $data     = [];
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
        $this->data['role'] = getUserRoles();
        // Fetch courses
        $courses = $this->coreCtrl::getUserMyCourses($request);
        // Ensure variables always have valid collections to avoid null errors
        $this->data['courses'] = [
            'academic_courses' => collect($courses['academic_courses'] ?? []),
            'nonacademic_courses' => collect($courses['nonacademic_courses'] ?? []),
            'academic_act_courses' => collect($courses['academic_activity_courses'] ?? []),
        ];

        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
        // dd($this->data);
        return view('mittBunny.myCourses.my-courses', $this->data);
    }

    public function myCoursesContinueWatching(Request $request)
    {
        $request->merge(['from' => 'web']);

        return view('mittBunny.myCourses.continue-watching-sec', $this->data);
    }
    public function coursesListing(Request $request, $slug)
    {
        $request->merge(['from' => 'web']);

        $this->data['courses']     = $this->coreCtrl::getUserMyCoursesListing($request, $slug);
        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
        return view('mittBunny.myCourses.my-courses-listing', $this->data);
    }
    public function courseDigitalContent(Request $request, $id)
    {
        $request->merge(['from' => 'web']);
        $this->data['data'] = $this->coreCtrl::getCourseDigitalContent($request, $id);
        // Generate signed URLs for videos
        foreach ($this->data['data']['chapters'] as &$chapter) {
            foreach ($chapter['chapterListing'] as &$file) {
                if (in_array($file['file_extension'], ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v', 'wmv', 'flv', 'mkv', 'webm', '3gp', '3gp', 'm2ts', 'ogv', 'ts', 'mxf'])) {
                    $file['signed_url'] = FileController::sendOnSignedRoute($file['attachment_file']);
                }
            }
        }

        return view('mittBunny.myCourses.course-digital-content', $this->data);
    }
    public function coursesChapterListing(Request $request, $slug, $id)
    {
        $request->merge(['from' => 'web']);
        $this->data['data'] = $this->coreCtrl::getUserMyCoursesChapterListing($request, $id);
        $this->fileCtrl::saveUserVideoDurationOnPageLoad($this->data['data']['coursesChapter']);

        $chapterIds = $this->data['data']['coursesChapter']->pluck('id');

        $planners = Planner::where(function ($query) use ($chapterIds) {
            foreach ($chapterIds as $id) {
                $query->orWhereRaw("FIND_IN_SET(?, chapter_id)", [$id]);
            }
        })
            ->with('details', 'class', 'subject', 'board', 'medium', 'series')
            ->get()
            ->groupBy(function ($planner) {
                return explode(',', $planner->chapter_id)[0];
            });

        $this->data['data']['coursesChapter']->each(function ($chapter) use ($planners) {
            $chapter->planner = $planners->get($chapter->id, null);
        });

        return view('mittBunny.myCourses.courses-chapter-listing', $this->data);
    }
    public function getChapterDetails($id, $plannerId = null)
    {
        $chapter = CourseChapter::find($id);
        $courseId = CourseChapter::with('course', 'resources')->where('id', $id)->first();
        // $actualPercentage = $actualPercentage ?? 0;
        $actualPercentage = 0;
        $markasCompletePerc = 0;

        if (! $chapter) {
            return response()->json(['error' => 'Chapter not found'], 404);
        }
        if ($courseId->course->category_id == 1) {
            $files = MediaFiles::where('tbl_id', $id)
                ->where('type', 'course_chapter')
                ->get()
                ->map(function ($file) {
                    return [
                        'original_name' => $file->original_name,
                        'file_name' => $file->file_name,
                        'video_id'  => $file->id,
                        'file_path' => Storage::url('uploads/course_chapter_files/' . $file->attachment_file),
                        'file_type' => $file->file_extension,
                    ];
                });
        } else {
            $type = ['course_chapter', 'talent_course_chapter_extra'];
            $files = MediaFiles::where('tbl_id', $id)
                ->whereIn('type', $type)
                ->get()
                ->map(function ($file) {
                    return [
                        'original_name' => $file->original_name,
                        'file_name' => $file->file_name,
                        'attachment_file' => $file->attachment_file,
                        'video_id'  => $file->id,
                        'file_path' => Storage::url('uploads/course_chapter_files/' . $file->attachment_file),
                        'file_type' => $file->file_extension,
                    ];
                });
        }
        $supportingFolder = MediaFiles::where('tbl_id', $id)->where('type', 'course_chapter_extra')->get()->map(function ($supportingFolder) {
            return [
                'original_name' => $supportingFolder->original_name,
                'file_name' => $supportingFolder->file_name,
                'file_path' => Storage::url('/uploads/course_chapter_files/' . $supportingFolder->attachment_file),
                'attachment_file' => $supportingFolder->attachment_file,
                'file_type' => $supportingFolder->file_extension,
            ];
        });
        if ($plannerId) {
            $schoolId                = Auth::user()->userAdditionalDetail->school_id;
            $planner = Planner::find($plannerId);
            $actualplanner = SchoolCompletedPlanner::where('planner_id', $plannerId)->where('school_id', $schoolId)->first();
            if ($planner) {
                $startDate = Carbon::parse($planner->start_date);
                $completionDate = Carbon::parse($planner->completion_date);
                $today = Carbon::today();

                $totalWorkingDays = $startDate->diffInDaysFiltered(
                    fn($date) => $date->dayOfWeek !== Carbon::SUNDAY,
                    $completionDate
                );

                if ($today->lt($startDate)) {
                    $actualPercentage = 0;
                } elseif ($today->gt($completionDate)) {
                    $actualPercentage = 100;
                } else {
                    $elapsedWorkingDays = $startDate->diffInDaysFiltered(
                        fn($date) => $date->dayOfWeek !== Carbon::SUNDAY,
                        $today
                    );
                    $actualPercentage = $totalWorkingDays > 0
                        ? round(($elapsedWorkingDays / $totalWorkingDays) * 100, 2)
                        : 0;
                }
            }
            $markasCompletePerc = $actualplanner ? 100 : 0;
        }
        return response()->json([
            'chapter_name'     => $chapter->chapter_name,
            'description'      => $chapter->chapter_description,
            'course_id'        => $chapter->course_id,
            'chapter_id'       => $chapter->id,
            'estimatedPercentage'       => $actualPercentage, // this is the one which is auto generated by the machine
            'actualPercentage'       => $markasCompletePerc, // this is if the school or teacher mark the planner as complete
            'files'            => $files,
            'supportingFolder' => $supportingFolder,
        ]);
    }
}
