<?php

namespace App\Livewire;

use App\Models\Classes;
use App\Models\User;
use Livewire\Component;
use App\Models\UserAdditionalDetail;

class SchoolUsers extends Component
{

    public $logsOf = 'school';
    public $school_id;
    public $status = '1';
    public $role;
    public $schoolUsers;
    public $filteredUsers;
    public $class_names;

    public $schoolList = [];
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
        $role = $this->role;
        // dd($this->status);
        $status = $this->status;
        $this->filterLogs();
    }

    public function filterLogs()
    {
        $query = UserAdditionalDetail::with(['school', 'user.schoolDetails', 'user.schoolDetails.className'])->where('school_id', $this->school_id);

        if ($this->role && in_array($this->role, ['school_teacher', 'school_student'])) {
            $query->where('role', $this->role);
        } elseif ($this->role == 'all') {
            $query->whereIn('role', ['school_teacher', 'school_student']);
        }
        
        if ($this->status ) {
            $query->whereHas('user', function ($query) {
                $query->where('status', $this->status);
            });
        }else{
            $query->whereHas('user', function ($query) {
                $query->where('status', $this->status);
            });
        }

        $this->schoolUsers = $query->get();
        $userIds = $this->schoolUsers->pluck('user_id');
        $this->filteredUsers = User::whereIn('id', $userIds)
            ->get()
            ->map(function ($user) {
                $userAdditionalDetail = $this->schoolUsers->firstWhere('user_id', $user->id);

                if ($userAdditionalDetail) {
                    if ($userAdditionalDetail->role == 'school_student') {
                        $studentDetail = $userAdditionalDetail->user->studentDetails;
                        if ($studentDetail && $studentDetail->className->name) {
                            $className = Classes::find($studentDetail->className->id) ?? 'No class assigned';
                            $user->class_names = $className->name;
                        } else {
                            $user->class_names = 'No class assigned';
                        }
                    }
                    elseif ($userAdditionalDetail->role == 'school_teacher') {
                        if ($userAdditionalDetail->assigned_classes) {
                            $assignedClassIds = explode(',', $userAdditionalDetail->assigned_classes);
                            $classes = Classes::whereIn('id', $assignedClassIds)
                                ->pluck('name')
                                ->implode(', '); 
                            $user->class_names = $classes ?: 'No classes assigned';
                        } else {
                            $user->class_names = 'No classes assigned';
                        }
                    }
                }
                return $user;
            });
    }


    public function render()
    {
        
        return view('livewire.school-users');
    }
}
