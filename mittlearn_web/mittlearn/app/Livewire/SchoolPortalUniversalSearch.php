<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\OnlineClass;
use App\Models\Planner;
use App\Models\User;
use Livewire\Component;

class SchoolPortalUniversalSearch extends Component
{
    public $search  = '';
    public $results = []; // Store search results

    public function updatedSearch()
    {
        if (strlen($this->search) > 1) {
            $this->results = [
                'students'    => User::whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'school_student');
                })->where('name', 'like', '%' . $this->search . '%')->limit(5)->get(),

                'teachers'    => User::whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'school_teacher');
                })->where('name', 'like', '%' . $this->search . '%')->limit(5)->get(),

                'courses'     => Course::where('course_name', 'like', '%' . $this->search . '%')->limit(5)->get(),
                'OnlineClass' => OnlineClass::where('title', 'like', '%' . $this->search . '%')->limit(5)->get(),

                'planners'    => Planner::where('type', 'like', '%' . $this->search . '%')->limit(5)->get(),
            ];
        } else {
            $this->results = [];
        }
    }
    public function getRoute($category, $item)
    {
        switch ($category) {
            case 'students':
                return route('sp.student.manager');

            case 'teachers':
                return route('sp.teacher.manager');

            case 'courses':
                return route('sp.my.courses');
                // return route('sp.courses.details', ['id' => $item->id]);

            case 'OnlineClass':
                return route('online.class');

            case 'planners':
                return route('daily.planner');

            default:
                return '#';
        }
    }

    public function render()
    {
        return view('livewire.school-portal-universal-search');
    }
}
