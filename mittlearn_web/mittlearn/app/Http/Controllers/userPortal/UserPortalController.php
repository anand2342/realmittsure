<?php

namespace App\Http\Controllers\userPortal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Models\AccessCodeOlympiad;
use App\Models\Setting;
use App\Models\TrackUserVideoProgress;
use App\Models\UserClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPortalController extends Controller
{
    public $data     = [];
    public $res      = [];
    public $coreCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function dashboard(Request $request)
    {
        $request->merge(['from' => 'web']);
        $this->data['dashData'] = $this->coreCtrl::getUserDashboard($request);
        $this->data['courses']  = $this->coreCtrl::getUserMyCourses($request);
        // dd($this->data['courses']);
        $this->coreCtrl::storeStudentOverviewSection($request);

        $courses = $this->coreCtrl::getUserMyCourses($request);
        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);

        // if (!empty($courses)) {
        //     $totalAcadCourses = $courses['academic_courses']->count();
        //     $totalNonAcadCourses = $courses['nonacademic_courses']->count();
        // } else {
        //     $totalAcadCourses = 0;
        //     $totalNonAcadCourses = 0;
        // }

        $completedAcadCourses    = 0;
        $completedNonAcadCourses = 0;

        $academicWatchTime    = [];
        $nonAcademicWatchTime = [];

        // Initialize empty data array for all 12 months
        for ($i = 1; $i <= 12; $i++) {
            $academicWatchTime[$i]    = 0;
            $nonAcademicWatchTime[$i] = 0;
        }

        // Process Academic Courses
        if (!empty($courses)) {
            foreach ($courses['academic_courses'] as $course) {
                $totalVideoDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->sum('video_duration');
                $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->sum('watched_duration');

                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $completedAcadCourses++;
                }

                // Fetch watch time per month
                $monthlyWatchTime = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
                    ->groupBy('month')
                    ->get();

                foreach ($monthlyWatchTime as $record) {
                    $academicWatchTime[$record->month] += round($record->total_watched / 60, 2); // Convert to minutes
                }
            }

            // Process Non-Academic Courses
            foreach ($courses['nonacademic_courses'] as $course) {
                $totalVideoDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->sum('video_duration');
                $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->sum('watched_duration');

                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $completedNonAcadCourses++;
                }

                // Fetch watch time per month
                $monthlyWatchTime = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
                    ->groupBy('month')
                    ->get();

                foreach ($monthlyWatchTime as $record) {
                    $nonAcademicWatchTime[$record->month] += round($record->total_watched / 60, 2); // Convert to minutes
                }
            }
        }
        $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
            ->sum('watched_duration'); // Total watched seconds

        $this->data['totalHours']   = floor($totalWatchedDuration / 3600);        // Convert seconds to hours
        $this->data['totalMinutes'] = floor(($totalWatchedDuration % 3600) / 60); // Remaining minutes

        // Prepare Data for Highcharts
        $this->data['timeSpendingsData'] = json_encode([
            'academic'     => array_values($academicWatchTime),
            'non_academic' => array_values($nonAcademicWatchTime),
        ]);

        return view('userPortal.dashboard', $this->data);
    }

    public function showDashClasses(Request $request, $date)
    {
        $request->merge(['from' => 'web']);
        $onlineClasses  = $this->coreCtrl::getUserOnlineClass($request);
        $onlineClasses  = collect($onlineClasses);
        $inputDate      = Carbon::parse($date);
        $date           = $inputDate->format('Y-m-d');
        $dateComparison = $onlineClasses->flatMap(function ($classes) use ($date) {
            return $classes->where('class_date', $date);
        });
        if (request()->ajax()) {
            return response()->json(['dateComparison' => $dateComparison]);
        }
    }
    public function dashboardTimeSpendings(Request $request, $slug, $id)
    {
        // Fetch user's watched duration grouped by month
        $userId        = Auth::id();
        $watchTimeData = TrackUserVideoProgress::where('user_id', $userId)
            ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => round($item->total_watched / 60, 2)]; // Convert to minutes
            });

        // Convert data into an array for Highcharts
        $formattedWatchTime = [];
        for ($i = 1; $i <= 12; $i++) {
            $formattedWatchTime[] = $watchTimeData[$i] ?? 0;
        }

        $this->data['watchTimeData'] = json_encode($formattedWatchTime);

        return view('userPortal.myCourses.courses-chapter-listing', $this->data);
    }
    public function downloadApp(Request $request)
    {
        $this->data['setting'] = Setting::pluck('field_value', 'field_name')->toArray();
        return view('userPortal.download-app', $this->data);
    }

    public function vallidateAccessCode(Request $request)
    {
        $request->validate([
            'access_code' => 'required'
        ]);

        $matchAccessCode = AccessCodeOlympiad::where('access_code', $request->access_code)->first();

        if (!$matchAccessCode) {
            return redirect()->back()->withErrors([
                'access_code' => 'Oops! That access code doesn’t match our records. Please double-check and try again. 🔐'
            ], 'accessCodeErrors');
        }

        if ($matchAccessCode->status == 'active') {
            return redirect()->back()->withErrors([
                'access_code' => 'Heads up! 🚨 This access code has already been used. Try a different one.'
            ], 'accessCodeErrors');
        }

        $userClassData = [
            'user_id' => Auth::id(),
            'class_id' => $matchAccessCode->class_id,
            'category_id' => '35',
            'user_role' => 'd2c_user',
        ];

        UserClass::create($userClassData);

        $matchAccessCode->update([
            'user_id' => Auth::id(),
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', '✅ Access code validated successfully! You’ve unlocked new content.');
    }
}
