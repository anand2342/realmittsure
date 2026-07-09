<?php
namespace App\Http\Controllers\schoolPortal;

use App\Exports\ActiveAccessCodesExport;
use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\AccessCodeEmbibe;
use App\Models\Course;
use App\Models\MediaFiles;
use App\Models\OnlineClass;
use App\Models\Planner;
use App\Models\PlannerOff;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\Schools;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserManual;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public $data = [];
    public function dashboard(Request $request)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();

        // If the role is "school_teacher", use school_id from UserAdditionalDetail
        if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }

        // Query counts and data based on the adjusted parentId
        $this->data['students'] = User::with(['userAdditionalDetail'])
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_student')
                    ->where('school_id', $parentId);
            })->count();

        $this->data['teachers'] = User::with('userAdditionalDetail')
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', $parentId);
            })->count();

        // $this->data['classes'] = SchoolClass::all();
        $this->data['classes'] = getUserSchoolClasses(Auth::id());
        // $this->data['classes']        = getUserSchoolClasses($parentId);
        // dd($this->data['classes']);

        // Total digital content
        // $this->data['digitalContent'] = CourseChapter::count();
        $role          = getUserRoles();
        $board         = getUserBoard();
        $medium        = getUserMedium();
        $schoolClasses = SchoolAssignedClass::where('school_id', Auth::id())->pluck('class_id');

        // Assigned series
        $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', Auth::id())
            ->whereNotNull('series_id')
            ->whereIn('class_id', $schoolClasses)
            ->distinct('series_id')
            ->pluck('series_id')
            ->toArray();

        // Assigned subject IDs
        $subjectIds = SchoolAssignedDigitalContent::where('school_id', Auth::id())
            ->whereIn('class_id', $schoolClasses)
            ->pluck('subject_id')
            ->flatMap(function ($item) {
                return explode(',', $item);
            })->map(function ($id) {
            return (int) trim($id);
        })->unique()->toArray();

        // Get course IDs
        // $courseIds = Course::whereHas('metadataValues', function ($q) use ($board) {
        //     $q->where('field_name', 'board')->where('field_value', $board);
        // })
        //     ->whereHas('metadataValues', function ($q) use ($medium) {
        //         $q->where('field_name', 'medium')->where('field_value', $medium);
        //     })
        //     ->whereHas('metadataValues', function ($q) use ($schoolAssignedSeries) {
        //         $q->where('field_name', 'series')->whereIn('field_value', $schoolAssignedSeries);
        //     })
        //     ->whereHas('metadataValues', function ($q) use ($subjectIds) {
        //         $q->where('field_name', 'subject')->whereIn('field_value', $subjectIds);
        //     })->pluck('id')->toArray();
        $courseIds = Course::whereHas('metadataValues', function ($q) use ($schoolAssignedSeries) {
            $q->where('field_name', 'series')->whereIn('field_value', $schoolAssignedSeries);
        })
            ->whereHas('metadataValues', function ($q) use ($subjectIds) {
                $q->where('field_name', 'subject')->whereIn('field_value', $subjectIds);
            })
            ->pluck('id')
            ->toArray();

        // Allowed video formats
        $videoExtensions = ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'm2ts', 'ogv', 'ts', 'mxf'];

        // Actual video count query
        $videoCount = MediaFiles::where('media_files.type', 'course_chapter')
            ->whereIn('media_files.file_extension', $videoExtensions)
            ->join('course_chapters', 'media_files.tbl_id', '=', 'course_chapters.id')
            ->join('courses', 'course_chapters.course_id', '=', 'courses.id')
            ->whereIn('courses.id', $courseIds)
            ->count();

        $this->data['digitalContent'] = $videoCount;

        // Available access codes
        $this->data['availableAccessCodesTeachlite'] = AccessCodeEmbibe::where('school_id', $parentId)->where('type', 'teachlite')
            ->count();
        $this->data['availableAccessCodesMittlense'] = AccessCodeEmbibe::where('school_id', $parentId)->where('type', 'mittlense')
            ->count();

        // Calculate percentage changes for students and teachers
        $lastMonth                             = now()->subMonth();
        $this->data['studentChangePercentage'] = $this->calculateChangePercentage(
            $this->data['students'],
            User::where('created_by', $parentId)
                ->with(['userAdditionalDetail'])
                ->whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'user');
                })
                ->whereMonth('created_at', $lastMonth->month)
                ->count()
        );

        $this->data['teacherChangePercentage'] = $this->calculateChangePercentage(
            $this->data['teachers'],
            User::where('created_by', $parentId)
                ->with('userAdditionalDetail')
                ->whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'school_teacher');
                })
                ->whereMonth('created_at', $lastMonth->month)
                ->count()
        );

        // Fetch planned online classes for the current school/teacher
        $this->data['plannedClasses'] = OnlineClass::where('parent_id', $parentId)->whereIn('status', ['ongoing', 'upcoming'])
            ->with(['class', 'instructor', 'subject'])
            ->get();

        $this->data['accessCodesClasses'] = AccessCode::where('school_id', $parentId)
            ->with('class')
            ->select('class_id')
            ->selectRaw('count(*) as total_codes')
            ->selectRaw('count(case when user_id is not null then 1 end) as used_codes')
            ->selectRaw('count(case when user_id is null then 1 end) as unused_codes')
            ->groupBy('class_id')
            ->orderBy('class_id', 'asc')
            ->get();

        $this->data['studentsPerMonth'] = User::selectRaw('MONTH(created_at) as month')
            ->selectRaw('COUNT(*) as count')
            ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                $query->where('role', 'school_student')
                    ->where('school_id', $parentId);
            })
            ->whereYear('created_at', date('Y')) // Filter for current year only
            ->where('is_verified', 1)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [(int) $item->month => $item->count];
            });

        // Return appropriate view based on role
        if ($role == "school_teacher") {
            $teacherAssignedClasses  = getTeacherAssignedClasses();
            $teacherAssignedSubjects = getTeacherAssignedSubjects();

            // For get daily planner
            $plannerDates = Planner::whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->where('school_id', $parentId)
                ->orWhereNull('school_id') // Include universal planners if needed
                ->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                ->first();

            // If no planner dates are found, fallback to the current month's start and end date
            $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
            $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

            $this->data['totalPlannerDays'] = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));

            $currentDate = now();

            // Weekday names for the header
            $this->data['weekDays'] = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            // Get all dates between start and end date, excluding Sundays
            $this->data['allDates'] = [];
            $current                = new DateTime($startDate);
            $end                    = new DateTime($endDate);

            while ($current <= $end) {
                if ($current->format('w') != 0) {           // Exclude Sunday (0 corresponds to Sunday)
                    $this->data['allDates'][] = clone $current; // Add non-Sunday date
                }
                $current->modify('+1 day');
            }

            $this->data['totalDays'] = count($this->data['allDates']); // Total number of non-Sunday days

            // Fetch planner data
            $plannerData = Planner::with(['class', 'subject', 'chapter'])
                ->whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->where(function ($query) use ($parentId) {
                    $query->where('school_id', $parentId)
                        ->orWhereNull('school_id'); // Include universal planners if no school-specific data
                })
                ->whereBetween('start_date', [$startDate, $endDate])
                ->get();

            // Fetch planner_offs data for the authenticated school
            $plannerOffs = PlannerOff::whereHas('planner', function ($query) use ($parentId, $teacherAssignedClasses, $teacherAssignedSubjects) {
                $query->whereIn('class_id', $teacherAssignedClasses)
                    ->whereIn('subject_id', $teacherAssignedSubjects)
                    ->where('school_id', $parentId)
                    ->orWhereNull('school_id'); // Include universal planners' holidays if needed
            })->pluck('date')->toArray();

            // Process planner data into day-wise structure
            $dayWiseData = [];
            foreach ($plannerData as $item) {
                $plannedDate    = new DateTime($item->start_date);
                $completionDate = new DateTime($item->completion_date);

                for ($i = 0; $i < $item->allotted_days; $i++) {
                    $boxDate          = (clone $plannedDate)->modify("+$i days");
                    $boxDateFormatted = $boxDate->format('Y-m-d');

                    if ($boxDate->format('w') != 0 && ! in_array($boxDateFormatted, $plannerOffs)) { // Exclude Sunday and holiday dates
                                                                                                        // Map boxDate to its day index in allDates
                        $day = array_search($boxDateFormatted, array_map(fn($date) => $date->format('Y-m-d'), $this->data['allDates']));

                        if ($day !== false) { // Ensure the date is valid
                            $day += 1;            // Convert to 1-based index for Blade usage

                            // Determine class for the shiftBox
                            $boxClass = 'shiftBox';
                            if ($currentDate >= $boxDate && $currentDate <= $completionDate) {
                                $boxClass .= ' lightgreen';
                            } elseif ($currentDate < $boxDate) {
                                $boxClass .= ' lightorange';
                            } else {
                                $boxClass .= ' lightred'; // Overdue
                            }

                            // Ensure that the chapter doesn't already exist for this day and subject
                            $existingChapters = $dayWiseData[$day][$item->subject_id] ?? [];
                            $duplicate        = false;

                            foreach ($existingChapters as $existingChapter) {
                                if ($existingChapter['chapter_id'] == $item->chapter_id) {
                                    $duplicate = true; // Found duplicate, break the loop
                                    break;
                                }
                            }

                            // Only add if no duplicate found
                            if (! $duplicate) {
                                $title = $item->chapter->chapter_name ?? 'No Chapter Name';

                                // Add chapter to the dayWiseData
                                $dayWiseData[$day][$item->subject_id][] = [
                                    'chapter_id' => $item->chapter_id,
                                    'title'      => $title,
                                    'class'      => $boxClass,
                                ];
                            }
                        }
                    }
                }
            }

            // Fetch subjects
            $this->data['subjects'] = Planner::select('class_id', 'subject_id')
                ->with('subject')
                ->whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->distinct()
                ->get();

            $this->data['classes'] = Planner::with(['class', 'subject', 'chapter'])
                ->whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->get();
            $this->data['chartData'] = $this->getClassWiseStudentCountChartData(); // Get the chart data

            return view('schoolPortal.teacherPortal.dashboard', $this->data);
        }

        $showExpiryPopup = false;

        $user = Schools::where('user_id', Auth::user()->id)->first();

        if ($user->academic_session_id && $user->batch_id) {

            $session = DB::table('academic_sessions')
                ->where('id', $user->academic_session_id)
                ->first();

            if ($session && $session->end_date) {

                $endDate = Carbon::createFromFormat('Y-m', $session->end_date)->endOfMonth();
                $today   = Carbon::now();

                if ($today->greaterThan($endDate) && $today->diffInDays($endDate) <= 15) {
                    $showExpiryPopup = true;
                }
            }
        }

// pass to view
        $this->data['showExpiryPopup'] = $showExpiryPopup;
        // dd($showExpiryPopup);
        return view('schoolPortal.dashboard', $this->data);
    }

    private function calculateChangePercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }
    public function activeAccessCodeDownload()
    {
        $file = Excel::raw(new ActiveAccessCodesExport, \Maatwebsite\Excel\Excel::XLSX);
        return Response::make($file, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="active_access_codes.xlsx"',
        ]);
    }

    public function getClassWiseStudentCountChartData()
    {
        $parentId               = Auth::user()->userAdditionalDetail->school_id;
        $teacherAssignedClasses = getTeacherAssignedClasses();

        $studentCountsByClass = DB::table('student_details')
            ->select('student_details.class', 'classes.name as class_name', DB::raw('COUNT(*) as count'))
            ->join('user_additional_details', 'student_details.user_id', '=', 'user_additional_details.user_id') // Join with user_additional_details
            ->join('classes', 'student_details.class', '=', 'classes.id')
            ->where('user_additional_details.school_id', $parentId)
            ->whereIn('student_details.class', $teacherAssignedClasses)
            ->groupBy('student_details.class', 'classes.name')
            ->get();

        $chartData = $studentCountsByClass->map(function ($studentCount) {
            $color = in_array($studentCount->class, getTeacherAssignedClasses()) ? '#61F51D' : '#EC7172';
            return [
                'name'  => $studentCount->class_name,
                'y'     => (int) $studentCount->count,
                'color' => $color,
            ];
        });

        return $chartData->toArray();
    }
    public function downloadApp(Request $request)
    {
        $this->data['setting'] = Setting::pluck('field_value', 'field_name')->toArray();
        return view('schoolPortal.download-app', $this->data);
    }
    public function userManual(Request $request)
    {
        $userRole = getUserRoles(); // e.g., 'admin'

        $this->data['manuals'] = UserManual::where('is_active', 1)->whereRaw("FIND_IN_SET(?, visible_to_roles)", [$userRole])->get();
        return view('schoolPortal.user-manual', $this->data);
    }
}
