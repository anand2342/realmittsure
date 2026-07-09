<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\CourseMetadataField;
use App\Models\CourseMetadataValue;
use App\Models\Language;
use App\Models\LessonNumber;
use App\Models\Medium;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Livewire\WithFileUploads;

class CoursesBulkUpload extends Component
{
    use WithFileUploads;
    public $categories;
    public $selectedSubGroup = '';
    public $childCategories = [];
    public $selectedSubCategory = [];
    public $file;
    public $originalFilename;
    public $selectedCategory;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = []; // Store errors for specific rows and columns
    public function mount()
    {
        $this->categories = Category::where('status', 1)->where('parent_id', null)->pluck('name', 'id')->toArray();
        // $this->resetFilters();
    }
    public function childCategoriesFetch()
    {
        $this->selectedSubCategory = [];
        $this->selectedSubGroup = null;
        if ($this->selectedCategory) {
            // $this->selectedSubCategory = Category::getAllCategories($this->selectedCategory)->pluck('name', 'slug')->toArray();
            $categories = Category::where('status', 1)->getAllCategories($this->selectedCategory);

            $this->selectedSubCategory = $categories
                ->filter(function ($category) {
                    return ($category->parent_id == 2) ||
                        ($category->parent_id == 1 && in_array($category->id, [6, 7]));
                })
                ->pluck('name', 'slug')
                ->toArray();
        }
    }
    // public function resetFilters()
    // {
    //     $this->selectedCategory = null;
    //     $this->selectedSubGroup = '';
    // }
    public function downloadSampleFile()
    {
        if (!$this->selectedSubGroup) {
            session()->flash('error', 'Please Select a Subgroup');
        }
        $validatedData = $this->validate([
            'selectedCategory' => 'required',
            'selectedSubGroup' => 'required',
        ]);
        if ($this->selectedSubGroup == 'academic-digital-content') {
            $filePath = public_path("admin/sample-files/academic-digital-content-sample-file.xlsx");
        } elseif ($this->selectedSubGroup == 'academic_activities') {
            $filePath = public_path("admin/sample-files/academic_activities-sample-file.xlsx");
        } else {
            $filePath = public_path("admin/sample-files/talent-skills-sample-file.xlsx");
        }
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
            $this->originalFilename = $this->file->getClientOriginalName();
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
        $dateColumns = ['Content Creation Date*', 'Date*', 'Date of Registration']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }

    public function convertToSnakeCase($string)
    {
        // Remove "Book " or "Book/" at the start of string
        $string = preg_replace('/^Book(\/|\s)/', '', $string);

        // Replace spaces with underscores
        $string = preg_replace('/\s+/', '_', $string);

        // Remove asterisks
        $string = str_replace('*', '', $string);

        // Convert to lowercase
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
                $this->validateRow($convertedRow, $rowKey);
            }

            // Second pass: Save all rows (only reaches here if all validation passed)
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->saveDataBasedOnRole($convertedRow, $rowKey);
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                $this->isModalOpen = false;
                // session()->flash('successMsg', "{$successCount} row(s) successfully saved!");
                $this->file = null;
                return redirect()->route('course.index', ['group' => 'academic-digital-content'])->with('success', "{$successCount} row(s) successfully saved!"); // or ->to('/your-url')
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleImportError($e);
        }
    }
    private function convertRowKeys(array $row): array
    {
        $converted = [];
        foreach ($row as $key => $value) {
            $converted[$this->convertToSnakeCase($key)] = $value;
        }
        return $converted;
    }
    /**
     * Save data based on the role type.
     */
    private function saveDataBasedOnRole(array $data, $rowKey)
    {
        if ($this->selectedSubGroup === 'academic_activities') {
            $this->saveActivites($data, $rowKey);
        } elseif ($this->selectedSubGroup === 'academic-digital-content') {
            $this->validateDigitalContentCourse($data, $rowKey);
        } else {
            $this->saveTalentAndSkills($data, $rowKey);
        }
    }
    private function validateFileType()
    {
        if (!$this->file) {
            throw new \Exception('No file uploaded');
        }

        $filename = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = strtolower($filename);

        $error = null;

        if ($this->selectedSubGroup === 'academic_activities' && !str_contains($filename, 'activities')) {
            $error = 'The selected file does not match the "Academic Activities" subgroup. Please upload a file containing "activities" in its name.';
        } elseif ($this->selectedSubGroup === 'academic-digital-content' && !str_contains($filename, 'digital')) {
            $error = 'For "Academic Digital Content", please upload a file with "digital" in the filename.';
        } elseif (!in_array($this->selectedSubGroup, ['academic_activities', 'academic-digital-content']) && !str_contains($filename, 'talent')) {
            $error = 'For "Talent and Skills" content, the filename should include the word "talent". Please check your file name.';
        }


        if ($error) {
            session()->flash('errorMsg', [$error]);
            throw new \Exception($error);
        }
    }

    private function validateRow(array $data, string $rowKey)
    {
        $this->validateFileType(); // Validate file type first

        if ($this->selectedSubGroup === 'academic_activities') {
            $this->validateActivites($data, $rowKey);
        } elseif ($this->selectedSubGroup === 'academic-digital-content') {
            $this->saveDigitalContentCourse($data, $rowKey);
        } else {
            $this->saveTalentAndSkills($data, $rowKey);
        }
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
    private function validateDigitalContentCourse(array $data, string $rowKey)
    {

        $validator = Validator::make(
            $data,
            [
                'course_name' => 'required|max:255',
                'price' => 'required|numeric|min:0',
                'discount_type' => 'required|in:Flat,Percentage',
                'discount_value' => 'required|numeric|min:0',
                'board' => 'required|max:255',
                'subject' => 'required|max:255',
                'class' => 'required|max:255',
                'series' => 'required|max:255',
                'medium' => 'required|max:255',
                'content_creation_date' => 'nullable|date_format:d/m/Y',
                'status' => 'nullable|in:Active,Inactive',
                'channel_to_push' => 'nullable|in:Mittlearn,MittBunny,Both'
            ],
            [
                'content_creation_date.date_format' => 'The content creation date must be in the format dd/mm/yyyy.',
            ]
        );

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate category and sub-group
        if (!$this->selectedCategory || !Category::where('status', 1)->where('id', $this->selectedCategory)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['category' => ['Invalid category selected']]
            ]));
        }

        if (!$this->selectedSubGroup || !Category::where('status', 1)->where('slug', $this->selectedSubGroup)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['sub_group' => ['Invalid sub-group selected']]
            ]));
        }

        // Validate relationships
        $relationships = [
            'board' => Board::class,
            'medium' => Medium::class,
            'series' => BookSeries::class,
            'class' => Classes::class,
            'subject' => Subject::class,
            'content_language' => Language::class
        ];

        foreach ($relationships as $field => $model) {
            if (!empty($data[$field])) {
                $exists = $model::where('name', $data[$field])->exists();
                if (!$exists) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => [$field => ["{$field} not found"]]
                    ]));
                }
            }
        }

        // Additional validation for series-board-medium relationship
        if (!empty($data['series']) && !empty($data['board']) && !empty($data['medium'])) {
            $series = BookSeries::where('name', $data['series'])->first();
            $boardId = Board::where('name', $data['board'])->value('id');
            $mediumId = Medium::where('name', $data['medium'])->value('id');

            if ($series) {
                if ($series->board_id != $boardId) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['series' => ['The selected series does not belong to the selected board']]
                    ]));
                }

                if ($series->medium_id != $mediumId) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['series' => ['The selected series does not belong to the selected medium']]
                    ]));
                }
            }
        }

        // Additional validation for series-class-subject relationship
        if (!empty($data['series']) && !empty($data['class']) && !empty($data['subject'])) {
            $series = BookSeries::where('name', $data['series'])->first();
            $class = Classes::where('name', $data['class'])->first();
            $subject = Subject::where('name', $data['subject'])->first();

            if ($series && $class && $subject) {
                $classSubjects = json_decode($series->class_subjects, true);
                $classSubject = collect($classSubjects)->firstWhere('class_id', $class->id);

                if (!$classSubject) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['class' => ['The selected class is not available for this series']]
                    ]));
                }

                if (!in_array($subject->id, $classSubject['subject_ids'])) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['subject' => ['The selected subject is not available for this series and class']]
                    ]));
                }
            }
        }
    }

    private function saveDigitalContentCourse(array $data, string $rowKey)
    {

        $slug = generateUniqueSlug($data['course_name'], Course::class, 'slug', null);
        $selectedSubGroup = Category::where('status', 1)->where('slug', $this->selectedSubGroup)->value('id');

        $courseData = [
            'category_id' => $this->selectedCategory,
            'sub_category_id' => $selectedSubGroup,
            'course_name' => $data['course_name'],
            'slug' => $slug,
            'price' => $data['price'],
            'discount_type' => strtolower($data['discount_type']),
            'discount_value' => $data['discount_value'],
            'is_active' => true,
        ];

        // Create course
        $course = Course::create($courseData);
        if (!$course) {
            throw new \Exception("Error creating course for row: {$rowKey}");
        }

        // Prepare metadata
        $metadataDataArr = [];
        $excludedFields = [
            'course_name',
            'price',
            'discount_type',
            'discount_value',
            'amount'
        ];

        // Process relationship fields
        $relationshipFields = [
            'board',
            'medium',
            'series',
            'class',
            'subject',
            'content_language'
        ];

        foreach ($data as $key => $value) {
            if (!in_array($key, $excludedFields)) {
                // Handle special fields
                if ($key === 'content_creation_date' && !empty($value)) {
                    $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                } elseif ($key === 'status') {
                    $value = ($value === 'Active') ? 1 : 0;
                } elseif ($key === 'channel_to_push') {
                    $value = array_search($value, ['Mittlearn', 'MittBunny', 'Both']);
                } elseif (in_array($key, $relationshipFields) && !empty($value)) {
                    $model = match ($key) {
                        'board' => Board::class,
                        'medium' => Medium::class,
                        'series' => BookSeries::class,
                        'class' => Classes::class,
                        'subject' => Subject::class,
                        'content_language' => Language::class
                    };
                    $value = $model::where('name', $value)->value('id');
                }

                $field_id = CourseMetadataField::where('field_name', $key)->value('id');
                if ($field_id) {
                    $metadataDataArr[] = [
                        "course_id" => $course->id,
                        "field_id" => $field_id,
                        "field_name" => $key,
                        "field_value" => $value
                    ];
                }
            }
        }

        // Save metadata
        if (!empty($metadataDataArr)) {
            $result = CourseMetadataValue::insert($metadataDataArr);
            if (!$result) {
                throw new \Exception("Error saving metadata for row: {$rowKey}");
            }
        }
    }
    private function validateActivites(array $data, string $rowKey)
    {

        // dd($data);

        $validator = Validator::make($data, [
            'course_name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:Flat,Percentage',
            'discount_value' => 'required|numeric|min:0',
            'board' => 'required|max:255',
            'subject' => 'required|max:255',
            'class' => 'required|max:255',
            'series' => 'required|max:255',
            'medium' => 'required|max:255',
            'status' => 'nullable|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate category and sub-group
        if (!$this->selectedCategory || !Category::where('status', 1)->where('id', $this->selectedCategory)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['category' => ['Invalid category selected']]
            ]));
        }

        if (!$this->selectedSubGroup || !Category::where('status', 1)->where('slug', $this->selectedSubGroup)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['sub_group' => ['Invalid sub-group selected']]
            ]));
        }

        // Validate relationships
        $relationships = [
            'board' => Board::class,
            'medium' => Medium::class,
            'series' => BookSeries::class,
            'class' => Classes::class,
            'subject' => Subject::class,
            'content_language' => Language::class
        ];

        foreach ($relationships as $field => $model) {
            if (!empty($data[$field])) {
                $exists = $model::where('name', $data[$field])->exists();
                if (!$exists) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => [$field => ["{$field} not found"]]
                    ]));
                }
            }
        }

        // Additional validation for series-board-medium relationship
        if (!empty($data['series']) && !empty($data['board']) && !empty($data['medium'])) {
            $series = BookSeries::where('name', $data['series'])->first();
            $boardId = Board::where('name', $data['board'])->value('id');
            $mediumId = Medium::where('name', $data['medium'])->value('id');

            if ($series) {
                if ($series->board_id != $boardId) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['series' => ['The selected series does not belong to the selected board']]
                    ]));
                }

                if ($series->medium_id != $mediumId) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['series' => ['The selected series does not belong to the selected medium']]
                    ]));
                }
            }
        }

        // Additional validation for series-class-subject relationship
        if (!empty($data['series']) && !empty($data['class']) && !empty($data['subject'])) {
            $series = BookSeries::where('name', $data['series'])->first();
            $class = Classes::where('name', $data['class'])->first();
            $subject = Subject::where('name', $data['subject'])->first();

            if ($series && $class && $subject) {
                $classSubjects = json_decode($series->class_subjects, true);
                $classSubject = collect($classSubjects)->firstWhere('class_id', $class->id);

                if (!$classSubject) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['class' => ['The selected class is not available for this series']]
                    ]));
                }

                if (!in_array($subject->id, $classSubject['subject_ids'])) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['subject' => ['The selected subject is not available for this series and class']]
                    ]));
                }
            }
        }
    }
    private function saveActivites(array $data, string $rowKey)
    {
        // dd($data);
        $slug = generateUniqueSlug($data['course_name'], Course::class, 'slug', null);
        $selectedSubGroup = Category::where('status', 1)->where('slug', $this->selectedSubGroup)->value('id');

        $courseData = [
            'category_id' => $this->selectedCategory,
            'sub_category_id' => $selectedSubGroup,
            'course_name' => $data['course_name'],
            'slug' => $slug,
            'price' => $data['price'],
            'discount_type' => strtolower($data['discount_type']),
            'discount_value' => $data['discount_value'],
            'is_active' => true,
        ];

        // Create course
        $course = Course::create($courseData);
        if (!$course) {
            throw new \Exception("Error creating course for row: {$rowKey}");
        }

        // Prepare metadata
        $metadataDataArr = [];
        $excludedFields = [
            'course_name',
            'price',
            'discount_type',
            'discount_value',
            'amount'
        ];

        // Process relationship fields
        $relationshipFields = [
            'board',
            'medium',
            'series',
            'class',
            'subject',
            'content_language'
        ];

        foreach ($data as $key => $value) {
            if (!in_array($key, $excludedFields)) {
                // Handle special fields
                if ($key === 'status') {
                    $value = ($value === 'Active') ? 1 : 0;
                } elseif (in_array($key, $relationshipFields) && !empty($value)) {
                    $model = match ($key) {
                        'board' => Board::class,
                        'medium' => Medium::class,
                        'series' => BookSeries::class,
                        'class' => Classes::class,
                        'subject' => Subject::class,
                        'content_language' => Language::class
                    };
                    $value = $model::where('name', $value)->value('id');
                }
                if ($key === 'add_filename_for_activity') {
                    $key = 'add_filename';
                }
                $field_id = CourseMetadataField::where('field_name', $key)->value('id');
                if ($field_id) {
                    $metadataDataArr[] = [
                        "course_id" => $course->id,
                        "field_id" => $field_id,
                        "field_name" => $key,
                        "field_value" => $value
                    ];
                }
            }
        }
        // Save metadata
        if (!empty($metadataDataArr)) {
            $result = CourseMetadataValue::insert($metadataDataArr);
            if (!$result) {
                throw new \Exception("Error saving metadata for row: {$rowKey}");
            }
        }
    }
    private function validateTalentAndSkills(array $data, string $rowKey)
    {
        $validator = Validator::make($data, [
            'course_name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:Flat,Percentage',
            'discount_value' => 'required|numeric|min:0',
            'course_level' => 'required|max:255',
            'language' => 'required|max:255',
            'date_of_registration' => 'nullable|date_format:d/m/Y',
            'date' => 'nullable|date_format:d/m/Y',
            'available_for_talent_box' => 'nullable|in:Yes,No'
        ], [
            'date_of_registration.date_format' => 'The date of registration must be in the format dd/mm/yyyy.',
            'date.date_format' => 'The date must be in the format dd/mm/yyyy.',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate category and sub-group
        if (!$this->selectedCategory || !Category::where('status', 1)->where('id', $this->selectedCategory)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['category' => ['Invalid category selected']]
            ]));
        }

        if (!$this->selectedSubGroup || !Category::where('status', 1)->where('slug', $this->selectedSubGroup)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['sub_group' => ['Invalid sub-group selected']]
            ]));
        }

        // Validate relationships
        $relationships = [
            'course_level' => CourseLevel::class,
            'language' => Language::class,
            'lessons_number' => LessonNumber::class,
            'content_language' => Language::class
        ];
        foreach ($relationships as $field => $model) {
            if (!empty($data[$field])) {
                $exists = $model::where('name', $data[$field])->exists();
                if (!$exists && $field !== 'lessons_number') {
                    $exists = $model::where('number', $data[$field])->exists();
                }
                if (!$exists && $field !== 'certification_status') {
                    $exists = $data[$field];
                } elseif ($field === 'certification_status') {
                    // Validate certification status values
                    if (!in_array(strtolower($data[$field]), ['issued', 'not issued'])) {
                        throw new \Exception(json_encode([
                            'row' => $rowKey,
                            'errors' => [
                                $field => [
                                    'Certification status must be either "Issued" or "Not Issued"'
                                ]
                            ]
                        ]));
                    }
                    $exists = true; // Mark as valid if it passes the check
                }

                if (!$exists) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => [$field => ["{$field} not found"]]
                    ]));
                }
            }
        }
    }

    private function saveTalentAndSkills(array $data, string $rowKey)
    {
        $slug = generateUniqueSlug($data['course_name'], Course::class, 'slug', null);
        $selectedSubGroup = Category::where('status', 1)->where('slug', $this->selectedSubGroup)->value('id');

        $courseData = [
            'category_id' => $this->selectedCategory,
            'sub_category_id' => $selectedSubGroup,
            'course_name' => $data['course_name'],
            'slug' => $slug,
            'price' => $data['price'],
            'discount_type' => strtolower($data['discount_type']),
            'discount_value' => $data['discount_value'],
            'is_active' => true,
        ];

        // Create course
        $course = Course::create($courseData);
        if (!$course) {
            throw new \Exception("Error creating course for row: {$rowKey}");
        }

        // Prepare metadata
        $metadataDataArr = [];
        $excludedFields = [
            'course_name',
            'price',
            'discount_type',
            'discount_value',
            'amount'
        ];
        if (strtolower($data['available_for_talent_box']) === 'yes') {
            $data['available_for_talent_box'] = 1;
        } elseif (strtolower($data['available_for_talent_box']) === 'no') {
            $data['available_for_talent_box'] = 0;
        }
        // Process special fields
        $specialFields = [
            // 'available_for_complimentary_package' => fn($v) => $v === 'Yes' ? 1 : 0,
            'date_of_registration' => fn($v) => Carbon::createFromFormat('d/m/Y', $v)->format('Y-m-d'),
            'date' => fn($v) => Carbon::createFromFormat('d/m/Y', $v)->format('Y-m-d')
        ];

        // Process relationship fields
        $relationshipFields = [
            'course_level' => CourseLevel::class,
            'language' => Language::class,
            'lessons_number' => LessonNumber::class,
            'content_language' => Language::class
        ];

        foreach ($data as $key => $value) {
            if (!in_array($key, $excludedFields) && !empty($value)) {
                // Handle special fields
                if (array_key_exists($key, $specialFields)) {
                    $value = $specialFields[$key]($value);
                }

                // Handle relationship fields
                elseif (array_key_exists($key, $relationshipFields)) {
                    $model = $relationshipFields[$key];

                    // // Special handling for certification_status (1=Issued, 2=Not Issued)
                    // if ($key === 'certification_status') {
                    //     $value = ($value == 1) ? 'Issued' : 'Not Issued';
                    // }

                    if ($key === 'level') {
                        $value = $model::where('name', $value)->value('id');
                    } else {
                        // Default handling for other relationship fields
                        $value = $model::where('name', $value)->value('id');
                    }

                    if ($value === null) continue;
                }
                if ($key === 'certification_status') {
                    if ($value === 'Issued') {
                        $value = 1;
                    } elseif ($value === 'Not Issued') {
                        $value = 2;
                    }
                }
                if ($key === 'available_for_talent_box') {
                    $key = 'available_for_complimentary_package';
                }
                if ($key === 'about_instructor') {
                    $key = 'instructor';
                }
                $field_id = CourseMetadataField::where('field_name', $key)->value('id');
                if ($field_id) {
                    $metadataDataArr[] = [
                        "course_id" => $course->id,
                        "field_id" => $field_id,
                        "field_name" => $key,
                        "field_value" => $value
                    ];
                }
            }
        }

        // Save metadata
        if (!empty($metadataDataArr)) {
            $result = CourseMetadataValue::insert($metadataDataArr);
            if (!$result) {
                throw new \Exception("Error saving metadata for row: {$rowKey}");
            }
        }
    }
    public function closeModal()
    {
        $this->isLoading = false;
        $this->file = null;
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
        return view('livewire.courses-bulk-upload');
    }
}
