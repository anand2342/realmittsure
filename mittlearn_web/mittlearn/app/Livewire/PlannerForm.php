<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\AdditionalDataRow;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\Medium;
use App\Models\Planner;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Add this import

class PlannerForm extends Component
{
    use WithFileUploads;
    public $stepTypes = [
        2  => 'teaching_aids',
        3  => 'prior_knowledge',
        4  => 'learning_objectives',
        5  => 'teaching_methodology',
        6  => 'topics_covered',
        7  => 'concept_learned',
        8  => 'exercises',
        9  => 'classwork',
        10 => 'homework',
        11 => 'notes',
        12 => 'topic_related_activities',
        13 => 'Screening_review_time',
        14 => 'student_to_bring',
        15 => 'any_other_info_remark',
        16 => 'event_function',
    ];
    public $stepTypesName = [
        2  => 'Teaching Aids',
        3  => 'Prior Knowledge',
        4  => 'Learning Objectives',
        5  => 'Teaching Methodology',
        6  => ' Topics Covered',
        7  => 'Concept Learned',
        8  => 'Exercises',
        9  => 'Classwork',
        10 => 'Homework',
        11 => 'Notes',
        12 => 'Topic Related Activities',
        13 => 'Screening/Review Time',
        14 => 'Student to Bring',
        15 => 'Any other info/ Remark',
        16 => 'Mention Event/Function',
    ];
    public $multiSelect = false;
    public $planner = [];
    public $planners;
    public $boards;
    public $mediums;
    public $subject;
    public $chapters = [];
    public $series;
    public $class;
    public $step;
    public $board_id, $medium_id, $series_id, $class_id, $subject_id, $allotted_days, $start_date, $completion_date, $total_periods;
    // public $rows_1 = [];
    public $coreTitle;
    public $coreHeading;
    public $coreAcademicFeatureAddtional;
    public $type = 'daily';
    public $chapter_id;
    public $chapter_ids = [];
    // public $event_title;
    public $batch_id;
    // public $event_description;
    public $board;
    public $medium;
    public $seriesId;
    public $bookSeries;
    public $classes;
    public $subjects;
    public $batches;
    public $batchEndtDate;
    public $batchStartDate;
    public $classSubjectsMapping;
    public $academicSession;
    public $selectedBatch;
    public array $event_title = [];
    public array $event_description = [];
    public $rows_1 = [
        ['title' => '', 'image' => '', 'description' => '']
    ];
    public function mount()
    {
        $this->step          = 1;
        $this->boards        = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->mediums       = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->series        = BookSeries::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->class         = Classes::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->subject       = Subject::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->academicSession = AcademicSession::where('is_active', 1)
            ->get()
            ->unique('name')
            ->pluck('name', 'id')
            ->toArray();
        $this->batches = [];
        $this->stepTypesName = $this->stepTypesName;
        $this->addRow();
        $this->addPlanner();
    }
    public function addPlanner()
    {
        if ($this->type === 'daily') {
            $this->planners[] = [
                'academic_session_id' => '',
                'batch_id' => '',
                'allotted_days' => '',
                'start_date' => '',
                'completion_date' => '',
                'total_periods' => '',
            ];
        } elseif ($this->type === 'weekly') {
            $this->planners[] = [
                'academic_session_id' => '',
                'batch_id' => '',
                'allotted_days' => 7,
                'start_date' => '',
                'completion_date' => '',
                'total_periods' => '',
            ];
        } else {
            $this->planners[] = [
                'academic_session_id' => '',
                'batch_id' => '',
                'allotted_days' => 30,
                'start_date' => '',
                'completion_date' => '',
                'total_periods' => '',
            ];
        }
    }

    public function updateCompletionDate($index)
    {
        // For debugging
        // dd($this->planners[$index]['start_date']);

        // Clear any old error first
        $this->resetErrorBag("planners.$index.start_date");

        $startDate = $this->planners[$index]['start_date'];
        $startLimit = $this->planners[$index]['batchStartDate'] ?? null;
        $endLimit = $this->planners[$index]['batchEndtDate'] ?? null;

        // Custom validation
        if ($startDate < $startLimit || $startDate > $endLimit) {
            $this->addError("planners.$index.start_date", 'Please select a start date within the selected academic year.');
            $this->planners[$index]['completion_date'] = null;
            return;
        }

        if ($startDate && $this->planners[$index]['allotted_days']) {
            $this->planners[$index]['completion_date'] = calculatePlannerCompletionDate(
                $startDate,
                $this->planners[$index]['allotted_days'],
                true,
                false
            );
        } else {
            $this->planners[$index]['completion_date'] = null;
        }
    }

    public function batchUpdate($index)
    {
        $sessionId = $this->planners[$index]['academic_session_id'] ?? null;

        if (!$sessionId) {
            $this->planners[$index]['batches'] = [];
            return;
        }

        $academicSession = AcademicSession::find($sessionId);

        if (!$academicSession) {
            $this->planners[$index]['batches'] = [];
            return;
        }

        $this->planners[$index]['batches'] = AcademicSession::where('is_active', 1)
            ->where('name', $academicSession->name)
            ->pluck('batch_name', 'id')
            ->toArray();
    }

    public function batchDate($index)
    {
        // For debugging
        // dd($this->planners[$index]['batch_id']);

        $batch = AcademicSession::find($this->planners[$index]['batch_id']);

        if ($batch) {
            $this->planners[$index]['batchStartDate'] = $batch->start_date;
            $this->planners[$index]['batchEndtDate'] = $batch->end_date;
        }
    }

    public function getBookSeries($boardId, $mediumId)
    {
        $this->board = $boardId;
        $this->medium = $mediumId;
        $query = BookSeries::query();
        if ($this->board) {
            $query->where('board_id', $this->board);
        }
        if ($this->medium) {
            $query->where('medium_id', $this->medium);
        }
        $this->series = $query->pluck('name', 'id')->toArray() ?? [];
    }
    public function getSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;
        $bookSeries = BookSeries::find($seriesId);
        if ($bookSeries) {
            $classSubjects = json_decode($bookSeries->class_subjects, true);
            $classIds = array_column($classSubjects, 'class_id');
            $subjectIds = array_unique(array_merge(...array_column($classSubjects, 'subject_ids')));
            $this->class = Classes::whereIn('id', $classIds)->where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->subject = Subject::whereIn('id', $subjectIds)->where('is_active', 1)->pluck('name', 'id');

            $this->classSubjectsMapping = $classSubjects;
        } else {
            $this->class = [];
            $this->subject = [];
            $this->classSubjectsMapping = [];
        }
        // $this->modelsDataList['classes'] = $this->classes ?? [];
        // $this->modelsDataList['subjects'] = $this->subjects ?? [];
    }

    public function getSubjectsByClass($classId)
    {
        $selectedClassSubjects = collect($this->classSubjectsMapping)->firstWhere('class_id', $classId);

        if ($selectedClassSubjects) {
            $subjectIds = $selectedClassSubjects['subject_ids'];
            $this->subject = Subject::whereIn('id', $subjectIds)->where('is_active', 1)->pluck('name', 'id')->toArray();
        } else {
            $this->subject = [];
        }
        // $this->modelsDataList['subjects'] = $this->subjects ?? [];
    }


    public function updateChapterName()
    {
        if ($this->board_id != 0 && $this->medium_id != 0 && $this->series_id && $this->class_id && $this->subject_id) {
            $courses = Course::whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'board')
                    ->where('field_value', $this->board_id);
            })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'medium')
                        ->where('field_value', $this->medium_id);
                })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'series')
                        ->where('field_value', $this->series_id);
                })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $this->class_id);
                })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'subject')
                        ->where('field_value', $this->subject_id);
                })
                ->get();

            $courseIds = $courses->pluck('id')->toArray(); // Extract the course IDs

            $this->chapters = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();
        } else if (($this->board_id == 0 || $this->medium_id == 0) && $this->series_id && $this->class_id && $this->subject_id) {
            $query = Course::whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'series')
                    ->where('field_value', $this->series_id);
            })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $this->class_id);
                })
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'subject')
                        ->where('field_value', $this->subject_id);
                });

            $courses   = $query->get();
            $courseIds = $courses->pluck('id')->toArray();

            $this->chapters = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();
        } else {
            $this->chapters = [];
        }
    }
    // Validation rules for Step 1
    protected function rules()
    {
        $rules = [
            'board_id'   => 'required',
            'medium_id'  => 'required',
            'series_id'  => 'required',
            'class_id'   => 'required',
            'subject_id'   => 'required',
        ];

        foreach ($this->planners as $index => $planner) {
            $rules["planners.$index.academic_session_id"]         = 'required';
            $rules["planners.$index.batch_id"]         = 'required';
            $rules["planners.$index.allotted_days"]     = 'required|numeric';
            $rules["planners.$index.start_date"]        = 'required|date';
            $rules["planners.$index.completion_date"]   = "required|date|after_or_equal:planners.$index.start_date";
            $rules["planners.$index.total_periods"]     = 'required|numeric';
            // Optional: $rules["planners.$index.chapter_id"] = 'required';
        }

        return $rules;
    }

    // Validation messages for Step 1
    protected $messages = [
        'board_id.required'                => 'Board is required.',
        'medium_id.required'               => 'Medium is required.',
        'series_id.required'               => 'Series is required.',
        'class_id.required'                => 'Class is required.',
        'subject_id.required'                => 'Subject is required.',

        '*.academic_session_id.required'   => 'Academic session is required.',
        '*.batch_id.required'              => 'Batch is required.',
        '*.allotted_days.required'         => 'Allotted days are required.',
        '*.allotted_days.numeric'          => 'Allotted days must be a number.',
        '*.start_date.required'            => 'Start date is required.',
        '*.completion_date.required'       => 'Completion date is required.',
        '*.completion_date.after_or_equal' => 'Completion date must be on or after the start date.',
        '*.total_periods.required'         => 'Total periods are required.',
        '*.total_periods.numeric'          => 'Total periods must be a number.',
        '*.chapter_id.required'            => 'Chapter title is required.',
    ];

    public function validateAndSaveStep1()
    {
        // Validate step 1 data
        $validatedData = $this->validate();

        // Save the validated data and check if it was successful
        if (! $this->savePlannerData()) {
            return false;
        }
        return true;
    }
    public function nextStep()
    {
        $maxSteps = ($this->type === 'daily') ? 15 : 16;

        if ($this->step === 1) {
            if (! $this->validateAndSaveStep1()) {
                return;
            }
        } elseif (in_array($this->step, range(2, $maxSteps))) {

            if ($this->planner != null && $this->step === 15) {
                $this->selectedBatch = AcademicSession::whereIn('id', collect($this->planner)->pluck('batch_id')->toArray())->get();
            }
            $plannerIds = collect($this->planner)->pluck('id')->toArray();
            // dd($plannerIds);
            $this->savePlannerChapters([
                'plannerIds' => $plannerIds,
                'steps' => $this->step,
            ]);
        }

        if ($this->step <= $maxSteps) {
            $this->step++;
        }
        $this->rows_1 = [['title' => '', 'image' => '', 'description' => '']];
    }

    public function savePlannerChapters(array $params)
    {
        // dd($params);
        try {
            $stepType = $this->stepTypes[$params['steps']] ?? null;
            $plannerIds = $params['plannerIds'] ?? [];

            // Validate required parameters
            if (empty($plannerIds) || !$stepType) {
                throw new \Exception('Missing required parameters');
            }

            // Handle event function type
            if ($stepType === 'event_function') {
                return $this->saveEventFunctionForPlanners($plannerIds, $params['steps']);
            }
            // Process regular step data for all planners
            return $this->processStepDataForPlanners($plannerIds, $stepType, $params['steps']);
        } catch (\Exception $e) {
            // dd($e);
            session()->flash('error', 'Error saving planner data: ' . $e->getMessage());
            return false;
        }
    }

    protected function saveEventFunctionForPlanners(array $plannerIds, int $currentStep): bool
    {


        $data = [];
        foreach ($plannerIds as $index => $plannerId) {
            if (empty($this->event_title[$index])) {
                session()->flash('info', 'This step has been skipped.');
                return false;
            }
            $title = $this->event_title[$index] ?? null;
            $description = $this->event_description[$index] ?? null;
            // dd($this->event_description[$index]);
            if (!empty($title)) {
                $data[] = [
                    'model_id'    => $plannerId,
                    'type'        => 'event_function',
                    'title'       => $title,
                    'description' => $description,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        if (!empty($data)) {
            AdditionalDataRow::insert($data);
            session()->flash('success', 'Event data saved successfully for all planners!');
            $this->handleFinalStepRedirect($currentStep);
        }

        session()->flash('info', 'No valid event data to save.');
        $this->handleFinalStepRedirect($currentStep);
        return false;
    }


    protected function processStepDataForPlanners(array $plannerIds, string $stepType, int $currentStep): bool
    {
        $hasSavedData = false;
        $dataToInsert = [];

        foreach ($this->rows_1 as $index => $row) {
            if (empty($row['title'])) {
                continue; // Skip empty rows
            }

            $filename = $this->processImage($row['image'] ?? null, $index);
            // dd($row['description']);

            foreach ($plannerIds as $plannerId) {
                $dataToInsert[] = [
                    'model_id'    => $plannerId,
                    'type'        => $stepType,
                    'title'       => $row['title'],
                    'description' => $row['description'] ?? null,
                    'image'       => $filename,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $hasSavedData = true;
        }

        if ($hasSavedData) {
            // Bulk insert all data at once
            AdditionalDataRow::insert($dataToInsert);
            session()->flash('success', 'Data saved successfully for all planners!');
        } else {
            session()->flash('info', 'This step has been skipped.');
        }

        // Handle final step redirection
        $this->handleFinalStepRedirect($currentStep);

        return $hasSavedData;
    }

    protected function processImage($image, int $index): ?string
    {
        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $extension = $image->getClientOriginalExtension();
            $filename = time() . "_planner_files_{$index}.{$extension}";
            $image->storeAs('uploads/planner-files', $filename, 'public');
            return $filename;
        }

        // Copy default image if no image provided
        $defaultImagePath = public_path('admin/img/default-planner.jpg');
        if (file_exists($defaultImagePath)) {
            $filename = time() . "_planner_files_{$index}.jpg";
            Storage::disk('public')->put(
                'uploads/planner-files/' . $filename,
                file_get_contents($defaultImagePath)
            );
            return $filename;
        }

        return null;
    }

    protected function handleFinalStepRedirect(int $currentStep): void
    {
        $finalStep = ($this->type === 'daily') ? 15 : 16;

        if ($currentStep === $finalStep) {
            redirect()->route('planner.index')
                ->with(['success' => config('constants.FLASH_REC_ADD_1')])
                ->send();
            exit;
        }
    }
    public function prevStep()
    {
        // Move back one step
        if ($this->step > 1) {
            $this->step--;
        }
    }
    public function finish()
    {
        return redirect()->to(request()->header('Referer'))->with(['success', config('constants.FLASH_REC_ADD_1')]);
    }
    public function savePlannerData()
    {
        $commonData = [
            'board_id' => $this->board_id,
            'medium_id' => $this->medium_id,
            'series_id' => $this->series_id,
            'class_id' => $this->class_id,
            'type' => $this->type,
            'subject_id' => $this->subject_id,
        ];

        // Clear previous errors
        $this->resetErrorBag();

        // Validate chapters
        if ($this->type == 'daily') {
            if (empty($this->chapter_id)) {
                $this->addError('chapter_id', 'Please select a chapter');
                return false;
            }

            $chapter = CourseChapter::find($this->chapter_id);
            $commonData['chapter_id'] = $this->chapter_id;
        } else {
            if (empty($this->chapter_ids)) {
                $this->addError('chapter_ids', 'Please select at least one chapter');
                return false;
            }
            $commonData['chapter_id'] = implode(',', $this->chapter_ids);
        }

        // Check for duplicate batch+chapter combinations in the current submission
        $batchChapterCombinations = [];
        foreach ($this->planners as $index => $planner) {
            $key = $planner['batch_id'] . '_' . $commonData['chapter_id'];

            if (in_array($key, $batchChapterCombinations)) {
                $this->addError(
                    'planners.' . $index . '.batch_id',
                    'This batch already has the same chapter(s) assigned in your current submission'
                );
                return false;
            }
            $batchChapterCombinations[] = $key;

            // Also check against existing database entries
            $exists = Planner::where('batch_id', $planner['batch_id'])
                ->where('chapter_id', $commonData['chapter_id'])
                ->exists();

            if ($exists) {
                $chapterNames = $this->type == 'daily'
                    ? CourseChapter::find($this->chapter_id)->chapter_name
                    : implode(', ', CourseChapter::whereIn('id', $this->chapter_ids)
                        ->pluck('chapter_name')->toArray());

                $this->addError(
                    'planners.' . $index . '.batch_id',
                    "Batch already has these chapters assigned: $chapterNames"
                );
                return false;
            }
        }

        // Save planners if no duplicates found
        foreach ($this->planners as $planner) {
            $this->planner[] = Planner::updateOrCreate(
                [
                    'batch_id' => $planner['batch_id'],
                    'chapter_id' => $commonData['chapter_id'],
                ],
                array_merge($commonData, [
                    'academic_session_id' => $planner['academic_session_id'],
                    'allotted_days' => $planner['allotted_days'],
                    'start_date' => $planner['start_date'],
                    'completion_date' => $planner['completion_date'],
                    'total_periods' => $planner['total_periods'],
                ])
            );
        }

        session()->flash('success', 'Planner saved successfully!');
        return true;
    }
    // public function savePlannerData()
    // {
    //     $commonData = [
    //         'board_id' => $this->board_id,
    //         'medium_id' => $this->medium_id,
    //         'series_id' => $this->series_id,
    //         'class_id' => $this->class_id,
    //         'type' => $this->type,
    //         'subject_id' => $this->subject_id,
    //     ];
    //     foreach ($this->planners as $planner) {
    //         // Handle chapter validation and assignment
    //         if ($this->type == 'daily') {
    //             $exists = Planner::whereRaw("FIND_IN_SET(?, chapter_id)", [$this->chapter_id])->where('batch_id', $planner['batch_id'])->exists();
    //             $chapter = CourseChapter::find($this->chapter_id);

    //             if ($exists) {
    //                 session()->flash('error', "The chapter '{$chapter->chapter_name}' is already assigned in the planner.");
    //                 return false;
    //             }
    //             $commonData['chapter_id'] = $this->chapter_id;
    //         } else {
    //             $existingChapters = [];
    //             foreach ($this->chapter_ids as $chapterId) {
    //                 $exists = Planner::whereRaw("FIND_IN_SET(?, chapter_id)", [$chapterId])->where('batch_id', $planner['batch_id'])->exists();
    //                 $chapter = CourseChapter::find($chapterId);

    //                 if ($exists) {
    //                     $existingChapters[] = $chapter->chapter_name;
    //                 }
    //             }

    //             if (!empty($existingChapters)) {
    //                 $existingChaptersStr = implode(', ', $existingChapters);
    //                 session()->flash('error', "These chapters are already assigned in the planner: $existingChaptersStr");
    //                 return false;
    //             }

    //             $commonData['chapter_id'] = $this->chapter_ids ? implode(',', $this->chapter_ids) : null;
    //         }
    //     }
    //     // Save each planner (batch)
    //     foreach ($this->planners as $planner) {
    //         $plannerData = array_merge($commonData, [
    //             'academic_session_id' => $planner['academic_session_id'],
    //             'batch_id' => $planner['batch_id'],
    //             'allotted_days' => $planner['allotted_days'],
    //             'start_date' => $planner['start_date'],
    //             'completion_date' => $planner['completion_date'],
    //             'total_periods' => $planner['total_periods'],
    //         ]);

    //         // If you need to update existing planners, you might need a way to identify them
    //         // For example, you could pass planner IDs in the $this->planners array
    //         $this->planner[] =   Planner::updateOrCreate(
    //             [
    //                 'batch_id' => $planner['batch_id'],
    //                 'chapter_id' => $commonData['chapter_id'],
    //                 // Add other unique identifiers if needed
    //             ],
    //             $plannerData
    //         );
    //     }

    //     session()->flash('success', 'Planner data saved successfully!');
    //     return true;
    // }
    public function addRow()
    {
        $this->rows_1[] = ['title' => '', 'image' => '', 'description' => '', 'row_id' => Str::random(8)];
    }
    public function updateType()
    {
        switch ($this->type) {
            case 'daily':
                $this->allotted_days = null;
                $this->multiSelect = false;
                $this->reset('chapter_id');
                // Update planners
                foreach ($this->planners as &$planner) {
                    $planner['allotted_days'] = null;
                }
                break;

            case 'weekly':
                $this->allotted_days = 7;
                $this->multiSelect = true;
                $this->reset('chapter_id');
                // Update planners
                foreach ($this->planners as &$planner) {
                    $planner['allotted_days'] = 7;
                }
                break;

            case 'monthly':
                $this->allotted_days = 30;
                $this->multiSelect = true;
                $this->reset('chapter_id');
                // Update planners
                foreach ($this->planners as &$planner) {
                    $planner['allotted_days'] = 30;
                }
                break;
        }
        unset($planner); // Don't forget to unset the reference


        // Emit an event to reinitialize Select2
        $this->dispatch('reinitializeSelect2');
    }
    public function removeRow($index)
    {
        unset($this->rows_1[$index]);
        $this->rows_1 = array_values($this->rows_1); // Reindex the array
    }


    public function removePlanner($index)
    {
        unset($this->planners[$index]);
        $this->planners = array_values($this->planners); // reindex
    }
    public function updatedStep($value)
    {
        if ($value > $this->step + 1 || $value < $this->step - 1) {
            $this->step = $this->step;
            return;
        }

        if ($value == 2 && ! $this->isStep1Valid()) {
            $this->step = 1;
        }
    }

    public function render()
    {
        return view('livewire.planner-form');
    }
}
