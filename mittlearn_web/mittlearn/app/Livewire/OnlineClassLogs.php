<?php

namespace App\Livewire;

use App\Models\OnlineClass;
use Livewire\Component;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UserAdditionalDetail;
use Illuminate\Support\Facades\Log;

class OnlineClassLogs extends Component
{

    public $logsOf = 'school';
    public $school_id;
    public $teacher_id;
    public $status = 'all';
    public $class_id;
    public $onlineClassLogs;

    public $schoolList = [];
    public $teachers = [];
    public $classes = [];


    public function mount($schoolList, $classes, $schoolId = null)
    {
        $this->schoolList = $schoolList;
        $this->classes = $classes;
        $this->school_id = $schoolId;
    }
    public function search()
    {
        $schoolId = $this->school_id;
        $teacherId = $this->teacher_id;
        $status = $this->status;
        $classId = $this->class_id;
        $this->filterLogs();

    }



    public function schoolChange($value)
    {
        $this->teachers = UserAdditionalDetail::with('user')->where('role','school_teacher')->where('school_id',$this->school_id)->get()
        ->pluck('user.name', 'user.id');
    }

    public function filterLogs()
    {
        $query = OnlineClass::with(['class', 'instructor']);

        if ($this->teacher_id) {
            $query->where('parent_id', $this->teacher_id);
        } elseif ($this->school_id) {
            $query->where('parent_id', $this->school_id);
        }
        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status); 
        }

        if ($this->class_id) {
            $query->where('class_id', $this->class_id);
        }

        if ($this->school_id) {
            $this->onlineClassLogs = $query->get();
        } else {
            $this->onlineClassLogs = [];
        }
    }

    public function render()
    {
        return view('livewire.online-class-logs');
    }
}
