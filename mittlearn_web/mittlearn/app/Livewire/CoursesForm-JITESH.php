<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\Classes;
use App\Models\CourseLevel;
use App\Models\CourseMetadataField;
use App\Models\Language;
use App\Models\LessonNumber;
use App\Models\Medium;
use App\Models\Subject;
use Livewire\Component;

class CoursesForm extends Component
{
    public $course;
    public $categories = [];
    public $subCategories = [];
    public $boards = [];
    public $mediums = [];
    public $bookSeries = [];
    public $selectedCategory = null;
    public $selectedBoard = null;
    public $selectedMedium = null;
    public $selectedSeries = null;
    public $category_slug = '';
    public $field_name = '';
    public $field_label = '';
    public $sort_order;
    public $field_type;
    public $is_required;
    public $is_active;
    public $courseMetadataFields;
    public $showBoardSelect = false;
    public $showMediumSelect = false;
    public $showSeriesSelect = false;
    public $lookupOptions = [];
    public $priceRows = [];
    public $childCategories = [];
    public $allChildCategories = [];
    public $metadataFieldValues = [];
    public $seriesId;
    public $board;
    public $medium;
    public $modelsData = [];

    // Course related properties
    public $modelsDataList;
    public $course_name;
    public $requirements = '';
    public $what_you_will_learn = '';
    public $price;
    public $classSubjectsMapping;
    public $classes = [];
    public $subjects = [];
    public $discount_type;
    public $discount_value;
    public $amount;
    public $selectedSubCategory = null;
    public $subCategoryData = [];
    protected $fieldTypes = ['text', 'textarea', 'select', 'multiselect', 'file', 'number', 'date'];

    public function mount($category,  $course = null,  $modelsData = null, $metadataFieldValues = null, $seriesId = null)
    {
        // $modelsData                                        = [];
        $this->course = $course;
        $this->modelsDataList = $modelsData;
        $this->requirements = $this->requirements ?? '';
        $this->what_you_will_learn = $this->what_you_will_learn ?? '';

        $this->board = $metadataFieldValues['board'] ?? null;
        $this->medium = $metadataFieldValues['medium'] ?? null;

        $query = BookSeries::query();
        if ($this->board) {
            $query->where('board_id',   $this->board);
        }
        if ($this->medium) {
            $query->where('medium_id', $this->medium);
        }
        $this->bookSeries = $query->pluck('name', 'id')->toArray();
        $this->modelsDataList['book_series'] = $this->bookSeries ?? [];


        $this->seriesId = $metadataFieldValues['series'] ?? null;
        $bookSeries = BookSeries::find($this->seriesId);
        if ($bookSeries) {
            $classSubjects = json_decode($bookSeries->class_subjects, true);
            $classIds = array_column($classSubjects, 'class_id');
            $subjectIds = array_unique(array_merge(...array_column($classSubjects, 'subject_ids')));
            $this->classes = Classes::whereIn('id', $classIds)->where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->subjects = Subject::whereIn('id', $subjectIds)->where('is_active', 1)->pluck('name', 'id');

            $this->classSubjectsMapping = $classSubjects;
        } else {
            $this->classes = [];
            $this->subjects = [];
            $this->classSubjectsMapping = [];
        }
        $this->modelsDataList['classes'] = $this->classes ?? [];
        $this->modelsDataList['subjects'] = $this->subjects ?? [];

        // Set course attributes if provided
        if ($this->course) {
            $this->selectedCategory = $this->course->category_id;
            $this->selectedSubCategory = $this->course->sub_category_id;
            $this->course_name = $this->course->course_name;
            $this->price = $this->course->price;
            $this->discount_type = $this->course->discount_type;
            $this->discount_value = $this->course->discount_value;
            $this->priceRows = [
                'price' => $this->price,
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_value,
            ];
            $this->amount = calculatePlanFinalPrice($this->priceRows);

            if ($this->selectedCategory) {
                $this->childCategories = Category::where('status', 1)->where('parent_id', $this->selectedCategory)->pluck('name', 'id')->toArray();
            }
            $this->metadataFieldValues = $metadataFieldValues ?? [];
            $this->showBoardSelect = true;
        }
        $categories = getCategoriesWithChild();
        $this->allChildCategories = categoriesToArray($categories);

        $this->categories = Category::where('status', 1)->where('parent_id', null)->pluck('name', 'id')->toArray();
        $this->sort_order = $this->getLastSortOrder() + 1;
        $this->loadCourseMetadataFields();

        $this->loadLookupOptions();
    }

    //     public function onChangeFiledValue($value, $fieldName)
    //     {
    //         $this->$fieldName = $value;
    //         $this->priceRows[$fieldName] = $value ?? 0;

    //         // Calculate the amount if all fields have values
    //         $this->amount = isset($this->priceRows['price'], $this->priceRows['discount_type'], $this->priceRows['discount_value'])
    //         ? calculatePlanFinalPrice($this->priceRows)
    //         : ($this->priceRows['price'] ?? 0);
    //     }
    public function onChangeFiledValue($value, $fieldName)
    {
        $this->$fieldName = $value;

        $this->priceRows[$fieldName] = $value ?? 0;

        // Check if all necessary fields have values
        if (isset($this->priceRows['price'], $this->priceRows['discount_value'], $this->priceRows['discount_type'])) {
            // Only call calculatePlanFinalPrice when all fields are available
            $this->amount = calculatePlanFinalPrice($this->priceRows);
        } else {
            $this->amount = $this->priceRows['price'] ?? 0;
        }
    }

    public function loadConstants($categoryId)
    {
        $category = Category::find($categoryId);

        // Reset options
        $this->boards = [];
        $this->mediums = [];
        $this->bookSeries = [];
        $this->showBoardSelect = false;
        $this->showMediumSelect = false;
        $this->showSeriesSelect = false;
        $this->childCategories = [];
        $this->allChildCategories = [];

        $categories = getCategoriesWithChild();
        $this->allChildCategories = categoriesToArray($categories);

        if ($category) {
            // $this->childCategories = $category->pluck('name', 'id')->toArray();
            $this->childCategories = $category->where('parent_id', $categoryId)->pluck('name', 'id')->toArray();

            if ($category->id === '1') {
                $this->boards = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
                $this->mediums = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
                $this->bookSeries = BookSeries::where('is_active', 1)->pluck('name', 'id')->toArray();

                $this->showBoardSelect = !empty($this->boards);
                $this->showMediumSelect = !empty($this->mediums);
                $this->showSeriesSelect = !empty($this->bookSeries);
            }
            if ($category->id == '2') {
                $this->courseMetadataFields = CourseMetadataField::where('is_active', 1)->where('category_id', '2')->get();
                $this->showBoardSelect = true;
            }

            // Pre-select subgroup if in edit mode
            if ($this->course && $this->course->sub_category_id) {
                $this->selectedSubCategory = $this->course->sub_category_id;
            }
        }
    }

    // public function getBookSeries($boardId, $mediumId)
    // {
    //     dd($boardId, $mediumId);
    //     $this->board = $boardId;
    //     $this->medium = $mediumId;
    //     $this->bookSeries = BookSeries::where('board_id', $boardId)
    //         ->where('medium_id', $mediumId)
    //         ->pluck('name', 'id')->toArray();
    //     $this->modelsDataList['book_series'] = $this->bookSeries ?? [];
    // }
    public function getBookSeries($boardId = null, $mediumId = null)
    {
        if ($boardId !== null) {
            $this->board = $boardId;
        }
        if ($mediumId !== null) {
            $this->medium = $mediumId;
        }
        $query = BookSeries::query();

        if ($this->board) {
            $query->where('board_id', $this->board);
        }

        if ($this->medium) {
            $query->where('medium_id', $this->medium);
        }

        $this->bookSeries = $query->pluck('name', 'id')->toArray();
        $this->modelsDataList['book_series'] = $this->bookSeries ?? [];
    }
    public function getSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;
        $bookSeries = BookSeries::find($seriesId);
        if ($bookSeries) {
            $classSubjects = json_decode($bookSeries->class_subjects, true);
            $classIds = array_column($classSubjects, 'class_id');
            $subjectIds = array_unique(array_merge(...array_column($classSubjects, 'subject_ids')));
            $this->classes = Classes::whereIn('id', $classIds)->where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->subjects = Subject::whereIn('id', $subjectIds)->where('is_active', 1)->pluck('name', 'id');

            $this->classSubjectsMapping = $classSubjects;
        } else {
            $this->classes = [];
            $this->subjects = [];
            $this->classSubjectsMapping = [];
        }
        $this->modelsDataList['classes'] = $this->classes ?? [];
        $this->modelsDataList['subjects'] = $this->subjects ?? [];
    }


    public function getSubjectsByClass($classId)
    {
        $selectedClassSubjects = collect($this->classSubjectsMapping)->firstWhere('class_id', $classId);

        if ($selectedClassSubjects) {
            $subjectIds = $selectedClassSubjects['subject_ids'];
            $this->subjects = Subject::whereIn('id', $subjectIds)->where('is_active', 1)->pluck('name', 'id')->toArray();
        } else {
            $this->subjects = [];
        }
        $this->modelsDataList['subjects'] = $this->subjects ?? [];
    }

    public function getMetaDataByCategoryId()
    {
        if ($this->selectedSubCategory) {
            if ($this->selectedCategory == '1') {
                $this->courseMetadataFields = CourseMetadataField::where('is_active', 1)->where('category_id', $this->selectedSubCategory)->get();
            } else {
                $this->courseMetadataFields = CourseMetadataField::where('is_active', 1)->where('category_id', '2')->get();
            }
        } else {
            $this->courseMetadataFields = []; // Reset if no subgroup is selected
        }

        $this->showBoardSelect = true; // Show the board select dropdown
    }

    public function updatedSelectedCategory($value)
    {
        if ($value) {
            $this->category_slug = $this->generateSlug($this->categories[$value]);
            $this->loadConstants($value);

            $this->field_type = 'select';
            $this->loadLookupOptions();
        } else {
            $this->category_slug = '';
            $this->lookupOptions = [];
        }
    }

    public function loadCourseMetadataFields()
    {
        if ($this->selectedSubCategory) {
            if ($this->selectedCategory == '1') {
                $this->courseMetadataFields = CourseMetadataField::where('is_active', 1)->where('category_id', $this->selectedSubCategory)->get();
            } else {
                $this->courseMetadataFields = CourseMetadataField::where('is_active', 1)->where('category_id', '2')->get();
            }
        } else {
            $this->courseMetadataFields = []; // Reset if no subgroup is selected
        }
    }

    public function loadLookupOptions()
    {
        $this->lookupOptions = [];

        if ($this->field_type === 'select' || $this->field_type === 'multiselect') {
            if ($this->showBoardSelect) {
                $this->lookupOptions = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
            } elseif ($this->showMediumSelect) {
                $this->lookupOptions = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
            } elseif ($this->showSeriesSelect) {
                $this->lookupOptions = BookSeries::where('is_active', 1)->pluck('name', 'id')->toArray();
            }
        }
    }

    protected function calculatePlanFinalPrice($priceRows)
    {
        $discount = 0;

        if ($priceRows['discount_type'] === 'percentage') {
            $discount = ($priceRows['price'] * $priceRows['discount_value']) / 100;
        } else {
            $discount = $priceRows['discount_value'];
        }

        return max(0, $priceRows['price'] - $discount); // Ensure final price is not negative
    }

    protected function generateSlug($value)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value)));
    }

    protected function getLastSortOrder()
    {
        return CourseMetadataField::max('sort_order') ?? 0;
    }

    protected function resetFields()
    {
        $this->selectedCategory = null;
        $this->category_slug = '';
        $this->field_name = '';
        $this->field_label = '';
        $this->sort_order = $this->getLastSortOrder() + 1;
        $this->boards = [];
        $this->mediums = [];
        $this->bookSeries = [];
        $this->field_type = null;
        $this->showBoardSelect = false;
        $this->showMediumSelect = false;
        $this->showSeriesSelect = false;
        $this->lookupOptions = [];

        $this->course_name = null;
        // $this->price = null;
        // $this->discount_type = null;
        // $this->discount_value = null;

        // Reset dynamic fields as well
        foreach ($this->courseMetadataFields as $field) {
            $this->{$field->field_name} = null;
        }
    }

    public function render()
    {
        $this->loadLookupOptions();

        return view('livewire.courses-form', [
            'mediums' => $this->mediums,
            'boards' => $this->boards,
            'bookSeries' => $this->bookSeries,
            'categories' => $this->categories,
            'fieldTypes' => $this->fieldTypes,
            'courseMetadataFields' => $this->courseMetadataFields,
            'metadataFieldValues' => $this->metadataFieldValues,
            'lookupOptions' => $this->lookupOptions,
            'showBoardSelect' => $this->showBoardSelect,
            'showMediumSelect' => $this->showMediumSelect,
            'showSeriesSelect' => $this->showSeriesSelect,
        ]);
    }
}
