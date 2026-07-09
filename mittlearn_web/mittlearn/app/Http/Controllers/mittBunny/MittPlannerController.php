<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\FileController;
use App\Models\CourseChapter;
use App\Models\Planner;
use App\Models\PlannerOff;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\StudentDetails;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MittPlannerController extends Controller
{
    public $data     = [];
    public $coreCtrl = '';
    public $fileCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
        $this->fileCtrl = FileController::class;
    }
    public function myPlanner(Request $request)
    {
        try {

            $role     = getUserRoles();
            $schoolId = Auth::user()->userAdditionalDetail->school_id;
            $classId  = StudentDetails::where('user_id', Auth::id())
                ->value('class');
            $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->orderBy('series_id', 'asc')
                ->get();

            $mappedSubjects = []; // subject_id => series_id

            if ($schoolAssignedDigitalContent->isNotEmpty()) {
                foreach ($schoolAssignedDigitalContent as $content) {
                    $seriesId = $content->series_id;
                    $subjectIds = explode(',', $content->subject_id);

                    foreach ($subjectIds as $subjectId) {
                        if (!isset($mappedSubjects[$subjectId])) {
                            $mappedSubjects[$subjectId] = $seriesId;
                        }
                    }
                }
            }

            // Optional: extract just subject IDs (used in whereIn etc.)
            $schoolAssignedSubjects = array_keys($mappedSubjects);

            $classes = Planner::with(['class', 'subject', 'chapter'])
                ->when($role === "school_student", function ($query) use ($classId) {
                    // dd($classId);
                    if (! empty($classId)) {
                        $query->where('class_id', $classId);
                    }
                    if (! empty($schoolAssignedSubjects)) {
                        $query->whereIn('subject_id', $schoolAssignedSubjects);
                    }
                })
                ->orderBy('class_id')->get();
            // dd($classes);

            if ($classes->isEmpty()) {
                return view('mittBunny.myPlanner.index');
            }
            $pData = Planner::where('class_id', $classId)->whereIn('subject_id', $schoolAssignedSubjects)
                ->first();
            $plannerType = $pData ? $pData->type : null;
            if (!$pData) {
                return view('mittBunny.myPlanner.index');
            }
            $subjectId   = $request->query('subject_id') ?? $pData->subject_id;

            // View Daily Planner
            if ($plannerType == 'daily') {
                $plannerDates = Planner::where(function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId)
                        ->orWhereNull('school_id');
                })
                    ->where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // If no planner dates are found, fallback to the current month's start and end date
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                $totalPlannerDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
                $currentDate      = now();

                // Weekday names for the header
                $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                // Get all dates between start and end date, excluding Sundays
                $allDates = [];
                $current  = new DateTime($startDate);
                $end      = new DateTime($endDate);

                while ($current <= $end) {
                    if ($current->format('w') != 0) { // Exclude Sunday (0 corresponds to Sunday)
                        $allDates[] = clone $current;     // Add non-Sunday date
                    }
                    $current->modify('+1 day');
                }

                $totalDays = count($allDates); // Total number of non-Sunday days

                // Fetch planner data
                $plannerData = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId)
                            ->orWhereNull('school_id');
                    })
                    ->where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->get();

                // Fetch planner_offs data for the authenticated school
                $plannerOffs = PlannerOff::whereHas('planner', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId)
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
                            $day = array_search($boxDateFormatted, array_map(fn($date) => $date->format('Y-m-d'), $allDates));

                            if ($day !== false) {
                                $day += 1; // Convert to 1-based index for Blade usage

                                $boxClass = 'shiftBox';
                                if ($currentDate >= $boxDate && $currentDate <= $completionDate) {
                                    $boxClass .= ' lightgreen';
                                } elseif ($currentDate < $boxDate) {
                                    $boxClass .= ' lightorange';
                                } else {
                                    $boxClass .= ' lightred';
                                }

                                $existingChapters = $dayWiseData[$day][$item->subject_id] ?? [];
                                $duplicate        = false;

                                foreach ($existingChapters as $existingChapter) {
                                    if ($existingChapter['chapter_id'] == $item->chapter_id) {
                                        $duplicate = true;
                                        break;
                                    }
                                }

                                if (! $duplicate) {
                                    $title = $item->chapter->chapter_name ?? 'No Chapter Name';

                                    // Handle comma-separated chapter IDs
                                    $chapterIds     = explode(',', $item->chapter_id);
                                    $firstChapterId = trim($chapterIds[0]); // Get the first chapter ID

                                    // Retrieve the first chapter and its course
                                    $chapter = CourseChapter::find($firstChapterId);
                                    $course  = $chapter ? $chapter->course : null; // Get related course

                                    $dayWiseData[$day][$item->subject_id][] = [
                                        'chapter_id'  => $firstChapterId, // Store first valid chapter ID
                                        'title'       => $chapter ? $chapter->chapter_name : 'No Chapter Name',
                                        'class'       => $boxClass,
                                        'course_id'   => $course ? $course->id : null,
                                        'course_slug' => $course ? $course->slug : null,
                                    ];
                                }
                            }
                        }
                    }
                }

                $subjects = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->get();

                return view('mittBunny.myPlanner.index', compact('plannerType', 'schoolId', 'subjectId', 'classes', 'totalPlannerDays', 'dayWiseData', 'subjects', 'weekDays', 'allDates', 'totalDays', 'startDate', 'endDate'));
            } elseif ($plannerType == 'weekly') {
                // Get planner data grouped by week
                $plannerDates = Planner::where('class_id', $classId)->whereIn('subject_id', $schoolAssignedSubjects)
                    ->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // Define start and end dates
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                // Get weekly breakdown
                $weeks       = [];
                $currentDate = Carbon::parse($startDate);
                while ($currentDate <= Carbon::parse($endDate)) {
                    $weekNumber = $currentDate->copy()->startOfWeek()->format('W');
                    if (! isset($weeks[$weekNumber])) {
                        $weeks[$weekNumber] = [
                            'start' => $currentDate->copy()->startOfWeek()->format('Y-m-d'),
                            'end'   => $currentDate->copy()->endOfWeek()->format('Y-m-d'),
                        ];
                    }
                    $currentDate->addWeek();
                }

                // Fetch planner data
                $plannerData = Planner::with(['class', 'subject', 'chapter'])
                    ->where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->get();

                // Organize data into weeks
                $weekWiseData = [];
                foreach ($plannerData as $item) {
                    $chapterIds = explode(',', $item->chapter_id);                                                   // Convert string to array
                    $chapters   = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    // Get the first valid chapter
                    $firstChapterId = trim($chapterIds[0]);
                    $firstChapter   = CourseChapter::find($firstChapterId);
                    $course         = $firstChapter ? $firstChapter->course : null; // Get related course

                    $startOfWeek = Carbon::parse($item->start_date)->startOfWeek()->format('W');

                    $weekWiseData[$startOfWeek][$item->subject_id][] = [
                        'palnner_id'  => $item->palnner_id,
                        'chapter_id'  => $chapterIds, // Store array of IDs
                        'titles'      => $chapters,   // Store an array of chapter names
                        'class'       => 'shiftBox',
                        'course_id'   => $course ? $course->id : null,
                        'course_slug' => $course ? $course->slug : null,
                    ];
                }

                // Fetch subjects
                $subjects = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->get();

                return view('mittBunny.myPlanner.index', compact('plannerType', 'subjectId', 'schoolId', 'classes', 'weeks', 'weekWiseData', 'subjects'));
            } elseif ($plannerType == 'monthly') {
                $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                $endOfMonth   = now()->endOfMonth()->format('Y-m-d');

                // Fetch all classes with monthly planners
                $classesWithMonthlyPlanners = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId)
                            ->orWhereNull('school_id'); // Include universal planners if no school-specific data
                    })
                    ->where('type', 'monthly')
                    ->where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                        $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])  // Starts in current month
                            ->orWhereBetween('completion_date', [$startOfMonth, $endOfMonth]) // Completes in current month
                            ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                                // Spans across the month (started before and ends after)
                                $query->where('start_date', '<', $startOfMonth)
                                    ->where('completion_date', '>', $endOfMonth);
                            });
                    })
                    ->get();

                // Group the data by class
                $classPlannerData = [];
                foreach ($classesWithMonthlyPlanners as $planner) {
                    $classId     = $planner->class_id;
                    $subjectName = $planner->subject->name ?? 'No Subject';
                    $chapterIds  = explode(',', $planner->chapter_id);                                                // Convert string to array
                    $chapters    = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names
                    // Get the first valid chapter
                    $firstChapterId = trim($chapterIds[0]);
                    $firstChapter   = CourseChapter::find($firstChapterId);
                    $course         = $firstChapter ? $firstChapter->course : null; // Get related course

                    $classPlannerData[$classId][] = [
                        'subject'         => $subjectName,
                        'chapter_id'      => $chapterIds, // Store array of IDs
                        'titles'          => $chapters,   // Store an array of chapter names
                        'planner_id'      => $planner->id,
                        'start_date'      => $planner->start_date,
                        'completion_date' => $planner->completion_date,
                        'course_id'       => $course ? $course->id : null,
                        'course_slug'     => $course ? $course->slug : null,
                    ];
                }

                // Fetch subjects
                $subjects = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->get();

                return view('mittBunny.myPlanner.monthly_planner', compact('classes', 'subjects', 'subjectId', 'classPlannerData', 'startOfMonth', 'endOfMonth'));
            }
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function filterMonthlyPlanner(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('n')); // Default to current month
        $startOfMonth  = Carbon::create(null, $selectedMonth, 1)->startOfMonth()->format('Y-m-d');
        $endOfMonth    = Carbon::create(null, $selectedMonth, 1)->endOfMonth()->format('Y-m-d');

        // Fetch all classes with monthly planners
        $schoolId                   = auth()->user()->school_id;
        $classesWithMonthlyPlanners = Planner::with(['class', 'subject', 'chapter'])
            ->where(function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)
                    ->orWhereNull('school_id');
            })
            ->where('type', 'monthly')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('completion_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                        $query->where('start_date', '<', $startOfMonth)
                            ->where('completion_date', '>', $endOfMonth);
                    });
            })
            ->get();

        // Group the data by class
        $classPlannerData = [];
        foreach ($classesWithMonthlyPlanners as $planner) {
            $classId        = $planner->class_id;
            $subjectName    = $planner->subject->name ?? 'No Subject';
            $chapterIds     = explode(',', $planner->chapter_id);
            $chapters       = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray();
            $firstChapterId = trim($chapterIds[0]);
            $firstChapter   = CourseChapter::find($firstChapterId);
            $course         = $firstChapter ? $firstChapter->course : null; // Get related course

            $classPlannerData[$classId]['class_name'] = $planner->class->name ?? 'No Class Name';
            $classPlannerData[$classId]['planners'][] = [
                'subject'         => $subjectName,
                'chapter_id'      => $chapterIds,
                'titles'          => $chapters,
                'planner_id'      => $planner->id,
                'start_date'      => $planner->start_date,
                'completion_date' => $planner->completion_date,
                'course_id'       => $course ? $course->id : null,
                'course_slug'     => $course ? $course->slug : null,
            ];
        }

        return response()->json($classPlannerData);
    }

    public function plannerCoursesChapterListing(Request $request, $slug, $id)
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

        return view('mittBunny.myPlanner.courses-chapter-listing', $this->data);
    }
}
