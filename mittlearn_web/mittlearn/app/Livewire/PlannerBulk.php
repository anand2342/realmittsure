<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\AdditionalDataRow;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\Language;
use App\Models\Medium;
use App\Models\Planner;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class PlannerBulk extends Component
{
    use WithFileUploads;
    public $courseId;
    public $isShow = false;
    public $selectedCourse;
    public $childCategories = [];
    public $file;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = []; // Store errors for specific rows and columns

    public function downloadSampleFile()
    {

        $filePath = public_path("admin/sample-files/planner-sample-file.xlsx");
        if (file_exists($filePath)) {
            return response()->download($filePath);
            session()->flash('succes', 'File downloaded!');
        }
        session()->flash('error', 'File not found!');
    }

    public function uploadUsers()
    {
        $this->resetImportState();
        $this->isLoading = true;
        if ($this->file) {

            try {
                // Convert the Excel file into an array
                $data = Excel::toArray([], $this->file)[0];

                if (empty($data) || !isset($data[0])) {
                    session()->flash('errorMsg', ['The file is empty or has invalid formatting.']);
                    return;
                }


                // Extract headers dynamically and remove empty ones
                $this->headers = array_values(array_filter($data[0], function ($header) {
                    return !empty($header);
                }));

                // Process the remaining rows dynamically
                $this->uploadedData = array_filter(array_map(function ($row) {
                    if (empty(array_filter($row))) {
                        return null; // Skip empty rows
                    }

                    // Match row with header count
                    $row = array_pad($row, count($this->headers), null);
                    $row = array_slice($row, 0, count($this->headers));

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }

                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)));

                // Remove null rows
                $this->uploadedData = array_values($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading = false;
            } catch (\Exception $e) {
                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
    }

    /**
     * Check if a column should be treated as a date
     */
    private function isDateColumn($columnName)
    {
        $dateColumns = ['Start Date*', 'Completion Date*']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }

    public function convertToSnakeCase($string)
    {
        $string = preg_replace('/\s+/', '_', $string);
        $string = str_replace('*', '', $string);
        $string = strtolower($string);
        return $string;
    }

    public function processSelectedData()
    {
        if (empty($this->selectedData)) {
            session()->flash('errorMsg', 'No data selected for processing.');
            return;
        }

        $this->rowErrors = []; // Reset row errors
        $successCount = 0;

        // Start a database transaction for the entire batch
        DB::beginTransaction();

        try {
            // First pass: Validate all selected rows
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->validatePlanner($convertedRow, $rowKey);
            }

            // Second pass: Save all rows (only reaches here if all validation passed)
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->savePlanner($convertedRow, $rowKey);
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                $this->isModalOpen = false;
                session()->flash('successMsg', "{$successCount} row(s) successfully saved!");
                $this->file = null;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleImportError($e);
        }
    }
    /**
     * Save data based on the role type.
     */
    private function convertRowKeys(array $row): array
    {
        $converted = [];
        foreach ($row as $key => $value) {
            $converted[$this->convertToSnakeCase($key)] = $value;
        }
        return $converted;
    }
    private function handleImportError(\Exception $e)
    {
        // dd($e);

        $errorData = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($errorData['row'])) {
            $this->rowErrors[$errorData['row']] = $errorData['errors'];
            session()->flash('errorMsg', 'Some rows have errors. No data was saved. Please review.');
        } else {
            session()->flash('errorMsg', 'Error during import: ' . $e->getMessage());
        }
    }
    // private function checkForDuplicateValues(array $uploadedData, string $field, string $errorKey)
    // {
    //     $values = [];
    //     $duplicates = [];

    //     foreach ($uploadedData as $rowKey => $row) {
    //         $convertedRow = $this->convertRowKeys($row);
    //         $rawValue = $convertedRow[$field] ?? null;

    //         if ($rawValue !== null) {
    //             // Split and normalize comma-separated values
    //             $splitValues = array_map('trim', explode(',', $rawValue));

    //             foreach ($splitValues as $value) {
    //                 if (in_array(strtolower($value), $values)) {
    //                     $duplicates[$rowKey][] = $value;
    //                 }
    //                 $values[] = strtolower($value); // track lowercase to avoid case-sensitive dupes
    //             }
    //         }
    //     }

    //     if (!empty($duplicates)) {
    //         $errors = [];
    //         foreach ($duplicates as $rowKey => $titles) {
    //             $titleList = implode(', ', $titles);
    //             $errors[$rowKey] = ["The Chapter Title=> '{$titleList}' are duplicated in the file"];
    //         }
    //         return $errors;
    //     }

    //     return null;
    // }

    public function validatePlanner(array $data, string $rowKey)
    {
        // dd($data);
        // if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'chapter_title', 'chapter_title')) {
        //     if (isset($duplicateErrors[$rowKey])) {
        //         throw new \Exception(json_encode([
        //             'row' => $rowKey,
        //             'errors' => ['chapter_title' => $duplicateErrors[$rowKey]]
        //         ]));
        //     }
        // }
        $validator = Validator::make($data, [
            'board' => 'required',
            'medium' => 'required',
            'series' => 'required',
            'class' => 'required',
            'subject' => 'required',
            'batches' => 'required',
            'academic_session' => 'required',
            'allotted_days' => 'required|integer|min:1',
            'start_date' => 'required|date_format:d/m/Y',
            'completion_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
            'total_periods' => 'required|integer|min:1',
            'planner_type' => 'required|in:Daily,Weekly,Monthly',
            'chapter_title' => 'required',
        ], [
            'start_date.date_format' => 'The start date must be in the format dd/mm/yyyy.',
            'completion_date.date_format' => 'The completion of birth must be in the format dd/mm/yyyy.',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate relationships exist
        $this->validateRelationships($data, $rowKey);

        // Validate series relationships
        $this->validateSeriesRelationships($data, $rowKey);

        // Validate course exists
        $this->validateCourseChapters($data, $rowKey);

        // Validate date calculations
        $this->validateDateCalculations($data, $rowKey);


        // Get course ID for chapter validation
        $courseId = $this->getCourseId(
            Board::where('name', $data['board'])->value('id'),
            Medium::where('name', $data['medium'])->value('id'),
            BookSeries::where('name', $data['series'])->value('id'),
            Classes::where('name', $data['class'])->value('id'),
            Subject::where('name', $data['subject'])->value('id')
        );

        // dd($courseId);
        if (strtolower($data['planner_type']) === 'daily') {
            if ($data['mention_event_or_function_title'] != null) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mention_event_or_function_title' => ["Planner type Daily for Event Title doesn't not accepted"]]
                ]));
            }
            if ($data['mention_event_or_function_description'] != null) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mention_event_or_function_description' => ["Planner type Daily for Event Description doesn't not accepted"]]
                ]));
            }
            // Daily planner - single chapter validation
            $chapter = CourseChapter::where('course_id', $courseId)
                ->first();

            if (!$chapter) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['chapter_title' => ['Chapter not found for this course']]
                ]));
            }

            if (Planner::where('chapter_id', $chapter->id)->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['chapter_title' => ["Chapter '{$chapter->chapter_name}' is already assigned"]]
                ]));
            }
        } else {

            $chapterNames = array_map('trim', explode(',', $data['chapter_title']));
            $existingChapters = [];
            $missingChapters = [];
            foreach ($chapterNames as $chapterName) {
                $chapter = CourseChapter::where('course_id', $courseId)
                    ->where('chapter_name', $chapterName)
                    ->first();

                if (!$chapter) {
                    $missingChapters[] = $chapterName;
                    continue;
                }

                if (Planner::where('chapter_id', $chapter->id)->exists()) {
                    $existingChapters[] = $chapter->chapter_name;
                }
            }
            if (!empty($missingChapters)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['chapter_title' => ["These chapters were not found: " . implode(', ', $missingChapters)]]
                ]));
            }

            if (!empty($existingChapters)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['chapter_title' => ["These chapters are already assigned: " . implode(', ', $existingChapters)]]
                ]));
            }
        }
    }
    private function validateRelationships(array $data, string $rowKey)
    {
        $relationships = [
            'board' => [Board::class, 'name'],
            'medium' => [Medium::class, 'name'],
            'series' => [BookSeries::class, 'name'],
            'class' => [Classes::class, 'name'],
            'subject' => [Subject::class, 'name'],
            'academic_session' => [AcademicSession::class, 'name'],
        ];

        foreach ($relationships as $field => [$model, $column]) {
            if (!empty($data[$field])) {
                if (!$model::where($column, $data[$field])->exists()) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => [$field => ["{$field} not found"]]
                    ]));
                }
            }
        }
        if (!empty($data['batches'])) {
            // Check if the batch exists for the given session name
            $batchExists = AcademicSession::where('batch_name', $data['batches'])
                ->where('name', $data['academic_session'])
                ->exists();

            if (!$batchExists) {
                // Throw an exception with a structured error message
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => [
                        'batches' => ["Please enter a valid Batch Name for the entered Session."]
                    ]
                ]));
            }
        }


        if (!empty($data['start_date'])) {

            $batch = AcademicSession::where('batch_name', $data['batches'])
                ->where('name', $data['academic_session'])
                ->first();

            $inputStartDate = Carbon::createFromFormat('d/m/Y', $data['start_date']);
            $batchStartDate = Carbon::parse($batch->start_date);

            // Check if input start date is before or equal to batch start date
            if ($inputStartDate->lte($batchStartDate)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['start_date' => [
                        "Start date ({$inputStartDate->format('d/m/Y')}) must be after the batch start date ({$batchStartDate->format('d/m/Y')})"
                    ]]
                ]));
            }
        }

        if (!empty($data['completion_date'])) {
            $batch = AcademicSession::where('batch_name', $data['batches'])
                ->where('name', $data['academic_session'])
                ->first();

            $inputCompletionDate = Carbon::createFromFormat('d/m/Y', $data['completion_date']);
            $batchEndDate = Carbon::parse($batch->end_date);

            // Check if completion date is after or equal to batch end date
            if ($inputCompletionDate->gte($batchEndDate)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['completion_date' => [
                        "Completion date ({$inputCompletionDate->format('d/m/Y')}) must be before the batch end date ({$batchEndDate->format('d/m/Y')})"
                    ]]
                ]));
            }
        }
    }

    private function validateSeriesRelationships(array $data, string $rowKey)
    {
        // Check if we need to validate series-board-medium relationship
        if (!empty($data['series']) && !empty($data['board']) && !empty($data['medium'])) {
            $series = BookSeries::where('name', $data['series'])->first();

            if ($series) {
                $isAllBoards = $data['board'] === 'All Boards';
                $isAllMediums = $data['medium'] === 'All Mediums';

                // Only validate board if not "All Boards"
                if (!$isAllBoards) {
                    $boardId = Board::where('name', $data['board'])->value('id');
                    if ($series->board_id != $boardId) {
                        throw new \Exception(json_encode([
                            'row' => $rowKey,
                            'errors' => ['series' => ['The selected series does not belong to the selected board']]
                        ]));
                    }
                }

                // Only validate medium if not "All Mediums"
                if (!$isAllMediums) {
                    $mediumId = Medium::where('name', $data['medium'])->value('id');
                    if ($series->medium_id != $mediumId) {
                        throw new \Exception(json_encode([
                            'row' => $rowKey,
                            'errors' => ['series' => ['The selected series does not belong to the selected medium']]
                        ]));
                    }
                }
            }
        }

        // Validate class and subject belong to series
        if (!empty($data['series']) && !empty($data['class']) && !empty($data['subject'])) {
            $series = BookSeries::where('name', $data['series'])->first();
            $class = Classes::where('name', $data['class'])->first();
            $subject = Subject::where('name', $data['subject'])->first();

            if ($series && $class && $subject) {
                $classSubjects = json_decode($series->class_subjects, true) ?? [];
                $classSubject = collect($classSubjects)->firstWhere('class_id', $class->id);

                if (!$classSubject) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['class' => ['The selected class is not available for this series']]
                    ]));
                }

                if (!in_array($subject->id, $classSubject['subject_ids'] ?? [])) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['subject' => ['The selected subject is not available for this series and class']]
                    ]));
                }
            }
        }
    }


    private function validateCourseChapters(array $data, string $rowKey)
    {
        if (
            !empty($data['board']) && !empty($data['medium']) && !empty($data['series']) &&
            !empty($data['class']) && !empty($data['subject'])
        ) {
            $query = Course::query();

            // Only filter by board if it's not "All Boards"
            if ($data['board'] !== 'All Boards') {
                $boardId = Board::where('name', $data['board'])->value('id');
                $query->whereHas(
                    'metadataValues',
                    fn($q) =>
                    $q->where('field_name', 'board')->where('field_value', $boardId)
                );
            }

            // Only filter by medium if it's not "All Mediums"
            if ($data['medium'] !== 'All Mediums') {
                $mediumId = Medium::where('name', $data['medium'])->value('id');
                $query->whereHas(
                    'metadataValues',
                    fn($q) =>
                    $q->where('field_name', 'medium')->where('field_value', $mediumId)
                );
            }

            $seriesId = BookSeries::where('name', $data['series'])->value('id');
            $classId = Classes::where('name', $data['class'])->value('id');
            $subjectId = Subject::where('name', $data['subject'])->value('id');

            $query->whereHas(
                'metadataValues',
                fn($q) =>
                $q->where('field_name', 'series')->where('field_value', $seriesId)
            )->whereHas(
                'metadataValues',
                fn($q) =>
                $q->where('field_name', 'class')->where('field_value', $classId)
            )->whereHas(
                'metadataValues',
                fn($q) =>
                $q->where('field_name', 'subject')->where('field_value', $subjectId)
            );

            if (!$query->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['chapter_title' => ['No course found for the selected criteria']]
                ]));
            }
        }
    }


    private function validateDateCalculations(array $data, string $rowKey)
    {
        if (strtolower($data['planner_type']) === 'weekly' && $data['allotted_days'] != '7') {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => [
                    'allotted_days' => [
                        "If planner type is weekly then Allotted Days Must be 7"
                    ]
                ]
            ]));
        }
        if (strtolower($data['planner_type']) === 'monthly' && $data['allotted_days'] != '30') {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => [
                    'allotted_days' => [
                        "If planner type is monthly then Allotted Days Must be 30"
                    ]
                ]
            ]));
        }
        $startDate = \DateTime::createFromFormat('d/m/Y', $data['start_date']);
        $userCompletionDate = \DateTime::createFromFormat('d/m/Y', $data['completion_date']);

        $calculatedDateString = calculatePlannerCompletionDate(
            $startDate,
            $data['allotted_days'],
            true,
            false
        ); // e.g., '2025-04-24'

        // Convert the calculated date to DateTime to match formats
        $calculatedDate = \DateTime::createFromFormat('Y-m-d', $calculatedDateString);
        $formattedCalculatedDate = $calculatedDate->format('d/m/Y');

        if ($data['completion_date'] !== $formattedCalculatedDate) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => [
                    'allotted_days' => [
                        "Allotted days ({$data['allotted_days']}) must match the calculated completion date ({$formattedCalculatedDate}), but you entered ({$data['completion_date']})"
                    ]
                ]
            ]));
        }
    }

    public function savePlanner(array $data, string $rowKey)
    {


        // Get all required IDs
        $boardId = Board::where('name', $data['board'])->value('id');
        $mediumId = Medium::where('name', $data['medium'])->value('id');
        $seriesId = BookSeries::where('name', $data['series'])->value('id');
        $classId = Classes::where('name', $data['class'])->value('id');
        $subjectId = Subject::where('name', $data['subject'])->value('id');
        $academicSessionId = AcademicSession::where('name', $data['academic_session'])->value('id');

        // Get course ID
        $courseId = $this->getCourseId($boardId, $mediumId, $seriesId, $classId, $subjectId);

        // Handle chapter validation and assignment differently based on planner type
        if (strtolower($data['planner_type']) === 'daily') {
            // Check if chapter is already assigned to any planner
            $chapter = CourseChapter::where('course_id', $courseId)->value('id');
            $chapterId = $chapter;
        } else {
            $chaptersId = [];
            $chapterNames = array_map('trim', explode(',', $data['chapter_title']));

            $chapetrs = explode(',', $data['chapter_title']);
            foreach ($chapterNames as $chapterName) {
                $chapter = CourseChapter::where('course_id', $courseId)->where('chapter_name', $chapterName)->value('id');
                $chaptersId[] = $chapter;
            }

            $chapterId = implode(',', $chaptersId);
        }

        // Get batch ID
        $batchId = AcademicSession::where('batch_name', $data['batches'])
            ->value('id');

        // Common data for planner
        $commonData = [
            'board_id' => $boardId,
            'medium_id' => $mediumId,
            'series_id' => $seriesId,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'academic_session_id' => $academicSessionId,
            'type' => strtolower($data['planner_type']),
            'allotted_days' => $data['allotted_days'],
            'start_date' => Carbon::createFromFormat('d/m/Y', $data['start_date'])->format('Y-m-d'),
            'completion_date' => Carbon::createFromFormat('d/m/Y', $data['completion_date'])->format('Y-m-d'),
            'total_periods' => $data['total_periods'],
            'status' => 'draft',
            'created_by' => Auth::id()
        ];

        // Create or update planner
        $planner = Planner::updateOrCreate(
            [
                'batch_id' => $batchId,
                'chapter_id' => $chapterId
            ],
            $commonData
        );

        // Process all sections
        $this->processPlannerSections($planner->id, $data);

        return "Planner saved successfully for row: {$rowKey}";
    }

    protected function getCourseId($boardId, $mediumId, $seriesId, $classId, $subjectId)
    {
        $query = Course::query();

        if ($boardId != 0) {
            $query->whereHas(
                'metadataValues',
                fn($q) =>
                $q->where('field_name', 'board')->where('field_value', $boardId)
            );
        }

        if ($mediumId != 0) {
            $query->whereHas(
                'metadataValues',
                fn($q) =>
                $q->where('field_name', 'medium')->where('field_value', $mediumId)
            );
        }

        $query->whereHas(
            'metadataValues',
            fn($q) =>
            $q->where('field_name', 'series')->where('field_value', $seriesId)
        );

        $query->whereHas(
            'metadataValues',
            fn($q) =>
            $q->where('field_name', 'class')->where('field_value', $classId)
        );

        $query->whereHas(
            'metadataValues',
            fn($q) =>
            $q->where('field_name', 'subject')->where('field_value', $subjectId)
        );

        return $query->value('id');
    }


    protected function processPlannerSections($plannerId, $data)
    {
        $sections = [
            'teaching_aids' => [
                'description' => $data['teaching_aids'] ?? null
            ],
            'prior_knowledge' => [
                'description' => $data['prior_knowledge'] ?? null
            ],
            // Add all other sections...
            'topics_covered' => [
                'description' => $data['topics_covered'] ?? null
            ],
            'learning_objectives' => [
                'description' => $data['learning_objectives'] ?? null
            ],
            'teaching_methodology' => [
                'description' => $data['teaching_methodology'] ?? null
            ],
            'concept_learned' => [
                'description' => $data['concept_learned'] ?? null
            ],
            'exercises' => [
                'description' => $data['exercises'] ?? null
            ],
            'homework' => [
                'description' => $data['homework'] ?? null
            ],
            'notes' => [
                'description' => $data['notes'] ?? null
            ],
            'topic_related_activities' => [
                'description' => $data['topic_related_activities'] ?? null
            ],
            'Screening_review_time' => [
                'description' => $data['Screening_review_time'] ?? null
            ],
            'student_to_bring' => [
                'description' => $data['student_to_bring'] ?? null
            ],
            'any_other_info_remark' => [
                'description' => $data['any_other_info_or_remark'] ?? null
            ],
            'event_function' => [
                'title' => $data['mention_event_or_function_title'] ?? null,
                'description' => $data['mention_event_or_function_description'] ?? null
            ],
        ];

        $dataToInsert = [];
        // dd($sections);
        foreach ($sections as $type => $section) {
            $dataToInsert[] = [
                'model_id' => $plannerId,
                'type' => $type,
                'title' => $section['title'] ?? null,
                'description' => $section['description'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (!empty($dataToInsert)) {
            AdditionalDataRow::insert($dataToInsert);
        }
    }
    public function closeModal()
    {
        $this->isLoading = false;
    }
    private function resetImportState()
    {
        $this->rowErrors = [];
        $this->uploadedData = [];
        $this->selectedData = [];
        $this->headers = [];
        $this->isModalOpen = false;
    }
    public function render()
    {
        return view('livewire.planner-bulk');
    }
}
