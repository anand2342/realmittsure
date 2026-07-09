<?php

namespace App\Livewire;

use App\Models\Schools;
use Livewire\Component;

class Section extends Component
{
    public $schools, $school_id, $assginedClass, $class_id, $flag, $data, $selectedClass, $selectedSchool;

    public function mount($data = null)
    {
        $this->schools = Schools::pluck('name', 'user_id')->toArray();
        $this->assginedClass = [];
        if (!empty($data)) {
            $this->school_id = $data->school_id;
            $this->assginedClass = getUserSchoolClasses($data->school_id);
            $this->selectedClass = $data->class_id;
        } else {
            $this->selectedSchool = null;
            $this->selectedClass = null;
        }
    }
    public function fetchClasses()
    {
        $this->assginedClass = getUserSchoolClasses($this->school_id);
    }
    public function render()
    {
        return view('livewire.section');
    }
}
