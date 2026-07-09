<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\AdditionalDataRow;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use App\Models\Medium;
use App\Models\Planner;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlannerController extends Controller
{
    public $data = [];


    public function index(Request $request)
    {
        try {
            // Initialize query
            $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
            $query = Planner::with('board', 'batch')->orderBy('created_at', 'DESC');
            if ($request->filled('academic_session')) {
                $session = AcademicSession::where('name', $request->academic_session)->pluck('id');
                $query->whereIn('batch_id', $session);
            }

            if ($request->filled('batch')) {
                $query->where('batch_id', $request->batch);
            }
            if ($request->filled('series')) {
                $query->where('series_id', $request->series);
            }
            if ($request->filled('class')) {
                $query->where('class_id', $request->class);
            }
            if ($request->filled('subject')) {
                $query->where('subject_id', $request->subject);
            }

            $planners = $query->paginate($perPageRecords);

            $this->data['classes'] = SchoolClass::where('is_active', '1')->pluck('name', 'id');
            $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id');
            $this->data['subject'] = Subject::where('is_active', '1')->pluck('name', 'id');
            // $this->data['academicSession'] = AcademicSession::where('is_active', 1)->distinct('name')->pluck('name', 'id');
            $this->data['academicSession'] = AcademicSession::where('is_active', 1)->get()->unique('name')->pluck('name', 'id');
            $this->data['batches'] = AcademicSession::where('is_active', '1')->pluck('batch_name', 'id');

            foreach ($planners as $planner) {
                $chapters = explode(',', $planner->chapter_id);
                $planner->chapter_names = CourseChapter::whereIn('id', $chapters)->pluck('chapter_name')->toArray();
            }
            return view('admin.plannerManagement.index', [
                'data' => $planners,
                'classes' => $this->data['classes'],
                'series' => $this->data['series'],
                'subject' => $this->data['subject'],
                'academicSession' => $this->data['academicSession'],
                'batches' => $this->data['batches'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }



    public function create()
    {
        try {
            $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id');
            $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id');
            $this->data['classes'] = SchoolClass::where('is_active', '1')->pluck('name', 'id');
            $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id');
            return view('admin.plannerManagement.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function plannerSave(Request $request)
    {
        // Validate general required fields
        $request->validate([
            'chapter_id' => 'required|array',
        ]);

        try {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');

            // First, if ID is given, fetch the original planner
            $existingPlanner = null;
            if ($request->id) {
                $existingPlanner = Planner::findOrFail($request->id);
            }

            // Extract all planner data from request
            $plannersData = [];
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^planners_(\d+)_(.+)$/', $key, $matches)) {
                    $index = $matches[1];
                    $field = $matches[2];
                    $plannersData[$index][$field] = $value;
                }
            }

            // If no planners data found but we have an existing planner, use its data
            if (empty($plannersData) && $existingPlanner) {
                $plannersData[0] = [
                    'academic_session_id' => $existingPlanner->academic_session_id,
                    'batch_id' => $existingPlanner->batch_id,
                    'allotted_days' => $existingPlanner->allotted_days,
                    'total_periods' => $existingPlanner->total_periods,
                    'start_date' => $existingPlanner->start_date,
                    'completion_date' => $existingPlanner->completion_date,
                ];
            }

            foreach ($plannersData as $index => $plannerFields) {
                $chapterIds = implode(',', $request->chapter_id);
                $batchId = $plannerFields['batch_id'] ?? null;

                $plannerData = [
                    'academic_session_id' => $plannerFields['academic_session_id'] ?? null,
                    'batch_id' => $batchId,
                    'allotted_days' => $plannerFields['allotted_days'] ?? null,
                    'total_periods' => $plannerFields['total_periods'] ?? null,
                    'start_date' => $plannerFields['start_date'] ?? null,
                    'completion_date' => $plannerFields['completion_date'] ?? null,
                    'chapter_id' => $chapterIds,
                ];

                // Check for existing planner with same batch and chapters (only if we have both values)
                if ($batchId && $chapterIds) {
                    $query = Planner::where('batch_id', $batchId)
                        ->where('chapter_id', $chapterIds);

                    // If updating, exclude the current planner from the check
                    if ($existingPlanner && $index == 0) {
                        $query->where('id', '!=', $existingPlanner->id);
                    }

                    if ($query->exists()) {
                        return redirect()->back()
                            ->with(['error' => 'A planner already exists for this batch and chapter combination'])
                            ->withInput();
                    }
                }

                if ($index == 0 && $existingPlanner) {
                    // Update the existing planner
                    $existingPlanner->update($plannerData);
                } else {
                    // Validate required fields for new planner
                    if (!$existingPlanner) {
                        throw new \Exception("Cannot create new planner without existing planner data");
                    }

                    // Create a new planner with data from existing planner
                    $newPlannerData = array_merge(
                        $plannerData,
                        [
                            'type' => $existingPlanner->type,
                            'school_id' => $existingPlanner->school_id,
                            'board_id' => $existingPlanner->board_id,
                            'medium_id' => $existingPlanner->medium_id,
                            'series_id' => $existingPlanner->series_id,
                            'class_id' => $existingPlanner->class_id,
                            'subject_id' => $existingPlanner->subject_id,
                        ]
                    );

                    $newPlanner = Planner::create($newPlannerData);

                    // Copy additional data from existing planner if it exists
                    if ($existingPlanner) {
                        AdditionalDataRow::where('model_id', $existingPlanner->id)
                            ->get()
                            ->each(function ($data) use ($newPlanner) {
                                AdditionalDataRow::create([
                                    'model_id' => $newPlanner->id,
                                    'type' => $data->type,
                                    'title' => $data->title,
                                    'image' => $data->image,
                                    'description' => $data->description,
                                ]);
                            });
                    }
                }
            }

            return redirect()->route('planner.index')->with(['success' => $success]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }


    // public function plannerEdit($id)
    // {
    //     try {
    //         $this->data['data'] = Planner::where('id', $id)->first();
    //         $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id');
    //         $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id');
    //         $this->data['classes'] = SchoolClass::where('is_active', '1')->pluck('name', 'id');
    //         $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id');
    //         return view('admin.plannerManagement.add', $this->data);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
    //     }
    // }
    public function deletePlanner($id)
    {
        try {
            $data = Planner::where('id', $id)->first();
            if ($data) {
                // Define step types that need to be deleted
                $stepTypes = [
                    1 => 'teaching_aids',
                    2 => 'prior_knowledge',
                    3 => 'learning_objectives',
                    4 => 'teaching_methodology',
                    5 => 'topics_covered',
                    6 => 'concept_learned',
                    7 => 'exercises',
                    8 => 'classwork',
                    9 => 'homework',
                    10 => 'notes',
                    11 => 'topic_related_activities',
                    12 => 'Screening_review_time',
                    13 => 'student_to_bring',
                    14 => 'any_other_info_remark',
                    15 => 'event_function',
                ];

                // Delete related AdditionalDataRow records
                AdditionalDataRow::where('model_id', $data->id)
                    ->whereIn('type', array_values($stepTypes)) // Ensure 'type' matches
                    ->delete();
            }
            // Delete the Planner record
            $data->delete();
            return redirect()->route('planner.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function viewPlanner($id)
    {
        try {

            $this->data['boards'] = Board::where('is_active', '1')->pluck('name', 'id');
            $this->data['mediums'] = Medium::where('is_active', '1')->pluck('name', 'id');
            $this->data['class'] = SchoolClass::where('is_active', '1')->pluck('name', 'id');
            $this->data['series'] = BookSeries::where('is_active', '1')->pluck('name', 'id');
            $this->data['subjects'] = Subject::where('is_active', '1')->pluck('name', 'id');
            $this->data['academicSession'] = AcademicSession::where('is_active', 1)
                ->get()
                ->unique('name')
                ->pluck('name', 'id')
                ->toArray();
            $formattedName = now()->year . '-' . substr(now()->addYear()->year, 2);
            $this->data['batches'] = AcademicSession::where('is_active', 1)
                ->where('name', $formattedName)
                ->pluck('batch_name', 'id')
                ->toArray();
            // dd($this->data['subjects']);
            $this->data['plannerData'] = Planner::with('details')->find($id);
            $this->data['selectedChapter'] = explode(',', $this->data['plannerData']->chapter_id);
            $courses = Course::whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'series')
                    ->where('field_value', $this->data['plannerData']->series_id);
            })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $this->data['plannerData']->class_id);
                })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'subject')
                        ->where('field_value', $this->data['plannerData']->subject_id);
                })
                ->when($this->data['plannerData']->board_id != 0, function ($query) {
                    $query->whereHas('metadataValues', function ($subQuery) {
                        $subQuery->where('field_name', 'board')
                            ->where('field_value', $this->data['plannerData']->board_id);
                    });
                })
                ->when($this->data['plannerData']->medium_id != 0, function ($query) {
                    $query->whereHas('metadataValues', function ($subQuery) {
                        $subQuery->where('field_name', 'medium')
                            ->where('field_value', $this->data['plannerData']->medium_id);
                    });
                })
                ->get();

            // dd($courses);
            $courseIds = $courses->pluck('id')->toArray();
            // dd($courseIds);
            $this->data['chapters'] = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();
            $this->data['stepTypesName'] = [
                1 => 'Teaching Aids',
                2 => 'Prior Knowledge',
                3 => 'Learning Objectives',
                4 => 'Teaching Methodology',
                5 => 'Topics Covered',
                6 => 'Concept Learned',
                7 => 'Exercises',
                8 => 'Classwork',
                9 => 'Homework',
                10 => 'Notes',
                11 => 'Topic Related Activities',
                12 => 'Screening/Review Time',
                13 => 'Student to Bring',
                14 => 'Any other info/ Remark',
            ];

            $this->data['stepTypes'] = [
                1 => 'teaching_aids',
                2 => 'prior_knowledge',
                3 => 'learning_objectives',
                4 => 'teaching_methodology',
                5 => 'topics_covered',
                6 => 'concept_learned',
                7 => 'exercises',
                8 => 'classwork',
                9 => 'homework',
                10 => 'notes',
                11 => 'topic_related_activities',
                12 => 'Screening_review_time',
                13 => 'student_to_bring',
                14 => 'any_other_info_remark',
            ];

            if (!empty($this->data['plannerData'])) {
                $type = $this->data['plannerData']->type;
                if (in_array($type, ['weekly', 'monthly'])) {
                    $this->data['stepTypesName'][15] = 'Mention Event/Function';
                    $this->data['stepTypes'][15] = 'event_function';
                }
            }


            return view('admin.plannerManagement.view', $this->data);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    // public function getChapters(Request $request)
    // {
    //     $boardId = $request->input('board_id');
    //     $mediumId = $request->input('medium_id');
    //     $seriesId = $request->input('series_id');
    //     $classId = $request->input('class_id');
    //     $subjectId = $request->input('subject_id');



    //     if ($boardId && $mediumId && $seriesId && $classId && $subjectId) {
    //         $courses = Course::whereHas('metadataValues', function ($query) use ($boardId) {
    //             $query->where('field_name', 'board')->where('field_value', $boardId);
    //         })
    //             ->whereHas('metadataValues', function ($query) use ($mediumId) {
    //                 $query->where('field_name', 'medium')->where('field_value', $mediumId);
    //             })
    //             ->whereHas('metadataValues', function ($query) use ($seriesId) {
    //                 $query->where('field_name', 'series')->where('field_value', $seriesId);
    //             })
    //             ->whereHas('metadataValues', function ($query) use ($classId) {
    //                 $query->where('field_name', 'class')->where('field_value', $classId);
    //             })
    //             ->whereHas('metadataValues', function ($query) use ($subjectId) {
    //                 $query->where('field_name', 'subject')->where('field_value', $subjectId);
    //             })
    //             ->get();

    //         $courseIds = $courses->pluck('id')->toArray();
    //         $chapters = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();
    //     } else if ($boardId == 0 && $mediumId == 0 && $seriesId && $classId && $subjectId) {
    //         $courses = Course::whereHas('metadataValues', function ($query) use ($seriesId) {
    //             $query->where('field_name', 'series')->where('field_value', $seriesId);
    //         })
    //             ->whereHas('metadataValues', function ($query) use ($classId) {
    //                 $query->where('field_name', 'class')->where('field_value', $classId);
    //             })
    //             ->whereHas('metadataValues', function ($query) use ($subjectId) {
    //                 $query->where('field_name', 'subject')->where('field_value', $subjectId);
    //             })
    //             ->get();

    //         $courseIds = $courses->pluck('id')->toArray();
    //         $chapters = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();
    //     } else {
    //         $chapters = [];
    //     }

    //     return response()->json($chapters);
    // }

    public function getChapters(Request $request)
    {
        $boardId   = $request->input('board_id');
        $mediumId  = $request->input('medium_id');
        $seriesId  = $request->input('series_id');
        $classId   = $request->input('class_id');
        $subjectId = $request->input('subject_id');

        $chapters = [];

        if ($seriesId && $classId && $subjectId) {
            $courses = Course::query();

            // Apply only if not 0
            if ($boardId && $boardId != 0) {
                $courses->whereHas('metadataValues', function ($query) use ($boardId) {
                    $query->where('field_name', 'board')->where('field_value', $boardId);
                });
            }

            if ($mediumId && $mediumId != 0) {
                $courses->whereHas('metadataValues', function ($query) use ($mediumId) {
                    $query->where('field_name', 'medium')->where('field_value', $mediumId);
                });
            }

            // Always apply these
            $courses->whereHas('metadataValues', function ($query) use ($seriesId) {
                $query->where('field_name', 'series')->where('field_value', $seriesId);
            })->whereHas('metadataValues', function ($query) use ($classId) {
                $query->where('field_name', 'class')->where('field_value', $classId);
            })->whereHas('metadataValues', function ($query) use ($subjectId) {
                $query->where('field_name', 'subject')->where('field_value', $subjectId);
            });

            $courseIds = $courses->pluck('id')->toArray();

            $chapters = CourseChapter::whereIn('course_id', $courseIds)
                ->pluck('chapter_name', 'id')
                ->toArray();
        }

        return response()->json($chapters);
    }

    // In your controller
    public function getBatchBySessionByName($name)
    {
        $batchData = AcademicSession::where('name', $name)->get(['id', 'batch_name']);

        return response()->json([
            'batches' => $batchData,
        ]);
    }
    public function bulkUpload()
    {
        return view('admin.plannerManagement.planner-bulk');
    }
}
