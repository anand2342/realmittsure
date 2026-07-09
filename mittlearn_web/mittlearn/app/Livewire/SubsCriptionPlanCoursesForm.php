<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\CourseMetadataValue;

class SubsCriptionPlanCoursesForm extends Component
{

    public $planData = null;
    public $parentCategories = [];
    public $categoriesWithChildren = [];
    public $classesList = [];
    public $subjectsList = [];
    public $bookSeriesList = [];
    public $coursesList = ['display_courses'=>[], 'academic'=>[], 'non_academic'=> []];
    public $selectedCourseIds = ['academic'=>[], 'non_academic'=> []];
    public $filterCategory = null;
    public $filterCategoryNonAcademic = [];
    public $filterCourseName = null;
    public $filterClasses = [];
    public $filterSubjects = [];
    public $filterBookSeries = [];

    public function mount($plan_data)
    {
        $this->planData = $plan_data;
        $this->parentCategories = getParentCategories();
        $this->classesList = getClasses();
        $this->subjectsList = getSubjects();
        $this->bookSeriesList = getBookSeries();
        $categories = getCategoriesWithChild();
        $this->categoriesWithChildren = categoriesToArray($categories);

        if ($this->planData) {
            
        }
    }

    public function handleFilterCourses($value, $fieldName)
    {
        if ($fieldName == 'search') {
            $this->searchCourses();
        } else {
            $this->{$fieldName} = $value;
        }
    }
   
    public function toggleCourse($courseId)
    {
        
        if ($this->filterCategory == 1) {
            if (in_array($courseId, $this->selectedCourseIds['academic'])) {
                // Remove if already selected
                $this->selectedCourseIds['academic'] = array_diff($this->selectedCourseIds['academic'], [$courseId]);
            } else {
                // Add if not selected
                $this->selectedCourseIds['academic'][] = $courseId;
            }
        } else {

            if (in_array($courseId, $this->selectedCourseIds['non_academic'])) {
                // Remove if already selected
                $this->selectedCourseIds['non_academic'] = array_diff($this->selectedCourseIds['non_academic'], [$courseId]);
            } else {
                // Add if not selected
                $this->selectedCourseIds['non_academic'][] = $courseId;
            }
        }
        //$this->searchCourses();
    }

    public function searchCourses()
    {
        // set blank if category select non-academic
        if ($this->filterCategory != 1) {
            $this->filterClasses = [];
            $this->filterSubjects = [];
            $this->filterBookSeries = [];
        }
        $query = Course::query();
        $query->with('metadata');
        $query->orderBy('course_name', 'ASC');
        // Filter by category ID
        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        // Filter by course name
        if (!empty($this->filterCourseName)) {
            $query->where('course_name', 'LIKE', '%' . $this->filterCourseName . '%');
        }

        // Filter by sub-category ID
        $subCategories = $this->filterCategoryNonAcademic;
        if (!empty($subCategories) && is_array($subCategories)) {
            $query->whereHas('metadata', function ($q) use ($subCategories) {
                $q->where('field_name', 'sub_category')
                ->whereIn('field_value', $subCategories);
            });
        }

        // Filter by classes (array of class IDs)
        $classes = $this->filterClasses;
        if (!empty($classes) && is_array($classes)) {
            $query->whereHas('metadata', function ($q) use ($classes) {
                $q->where('field_name', 'class')
                ->whereIn('field_value', $classes);
            });
        }

        // Filter by subjects (array of subject IDs)
        $subjects = $this->filterSubjects;
        if (!empty($subjects) && is_array($subjects)) {
            $query->whereHas('metadata', function ($q) use ($subjects) {
                $q->where('field_name', 'subject')
                ->whereIn('field_value', $subjects);
            });
        }

        // Filter by bookSeries (array of book series IDs)
        $bookSeries = $this->filterBookSeries;
        if (!empty($bookSeries) && is_array($bookSeries)) {
            $query->whereHas('metadata', function ($q) use ($bookSeries) {
                $q->where('field_name', 'series')
                ->whereIn('field_value', $bookSeries);
            });
        }

        // Fetch results
        $this->coursesList['display_courses'] = $query->get();
        if ($this->filterCategory == 1) {
            $this->coursesList['academic'] = $this->coursesList['display_courses'];
        } else {
            $this->coursesList['non_academic'] = $this->coursesList['display_courses'];
        }
       
    }

    public function render()
    {
        return view('livewire.subs-cription-plan-courses-form');
    }
}
