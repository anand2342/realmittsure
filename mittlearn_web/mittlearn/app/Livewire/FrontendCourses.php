<?php

namespace App\Livewire;

use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\FrontendCoursesView;
use Livewire\Component;

class FrontendCourses extends Component
{
    public $series = [];
    public $class = [];
    public $seriesCourses;
    public $courseSets = []; // for add-more
    public array $classOptions = [];

    public function mount()
    {
        $this->series = BookSeries::pluck('name', 'id')->toArray();
        $this->class = [];
        $this->seriesCourses = FrontendCoursesView::get();

        if ($this->seriesCourses && !$this->seriesCourses->isEmpty()) {
            foreach ($this->seriesCourses as $index => $series) {
                // $allSeries = BookSeries::pluck('name', 'id')->toArray();
                // unset($allSeries[$series->series_id]);
                // $this->series = $allSeries;
                $bookSeries = BookSeries::find($series->series_id);
                if ($bookSeries && $bookSeries->class_subjects) {
                    $classSubjects = json_decode($bookSeries->class_subjects, true);
                    $classIds = array_column($classSubjects, 'class_id');

                    $this->classOptions[$index] = Classes::whereIn('id', $classIds)
                        ->where('is_active', 1)
                        ->pluck('name', 'id')
                        ->toArray();
                }

                $this->courseSets[] = [
                    'id' => $series->id,
                    'series_id' => $series->series_id,
                    'classes_ids' => explode(',', $series->classes_ids),
                ];
            }
        } else {
            $this->addCourseSet();
        }
    }



    public function addCourseSet()
    {
        $this->courseSets[] = ['series_id' => null, 'class_ids' => []];
    }
    public function getSeriesId($seriesId, $index)
    {
        // $allSeries = BookSeries::pluck('name', 'id')->toArray();

        // unset($allSeries[$seriesId]);
        // $this->series[$index] = $allSeries;
        $bookSeries = BookSeries::find($seriesId);

        if ($bookSeries && $bookSeries->class_subjects) {
            $classSubjects = json_decode($bookSeries->class_subjects, true);
            $classIds = array_column($classSubjects, 'class_id');

            // Set classes only for the current index
            $this->classOptions[$index] = Classes::whereIn('id', $classIds)
                ->where('is_active', 1)
                ->pluck('name', 'id')
                ->toArray();
        } else {
            $this->classOptions[$index] = [];
        }
    }


    public function removeCourseSet($index)
    {
        if (isset($this->courseSets[$index]['id'])) {
            $rowId = $this->courseSets[$index]['id'];
            FrontendCoursesView::find($rowId)?->delete(); // Safely delete the record
            session()->flash('message', 'Record deleted successfully.');
        }

        unset($this->courseSets[$index]);
        $this->courseSets = array_values($this->courseSets); // Reindex the array
    }


    // public function removeCourseSet($index)
    // {
    //     unset($this->courseSets[$index]);
    //     $this->courseSets = array_values($this->courseSets); // reset index
    // }
    public function render()
    {
        return view('livewire.frontend-courses');
    }
}
