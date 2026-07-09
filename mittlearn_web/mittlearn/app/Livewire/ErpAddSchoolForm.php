<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\City;
use App\Models\Grade;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\Schools;
use App\Models\State;
use App\Models\UserAdditionalDetail;
use Livewire\Component;

class ERPAddSchoolForm extends Component
{
    public $selectedRole            = null;
    public $selectedClasses         = [];
    public $selectedTeacherClasses  = [];
    public $selectedStudentClass;
    public $selectedSubjects        = [];
    public $selectedTeacherSubjects = [];
    public $selectedSchool = null;
    public $schoolList;
    public $schools;
    public $roles;
    public $grades;
    public $users;
    public $salesman;
    public $distributors;
    public $boards;
    public $mediums;
    public $classes;
    public $subjects;
    public $cities = [];
    public $states;
    public $schoolUniqueId;
    public $sections;
    public $userData;
    public $school_classes;
    public $flag;
    public $uniqueSchoolId;
    public $loadClasses = [];
    public $academicSessions = [];
    public $schoolId;
    public $viewOnly;
    public $schoolType;
    public $sessionBatches;
    public $selectedSession = null;
    public $selectedBatch = null;
    public $schoolRole;
    public $batches = [];
    public $selectedState = null;
    public $city          = null;
    public $erpData; // Add this property

    public function mount($roles, $schoolList, $schools, $users, $salesman, $distributors, $boards, $mediums, $classes, $subjects, $cities = [], $states, $userData = null, $school_classes = null, $erpData = null)
    {
        $this->erpData = $erpData;
        $this->roles      = $roles;
        $this->users      = $users;
        $this->salesman   = $salesman;
        $this->distributors   = $distributors;
        $this->boards     = $boards;
        $this->mediums    = $mediums;
        $this->classes    = $classes;
        $this->subjects   = $subjects;
        $this->cities     = $cities;
        $this->states     = $states;
        $this->schoolList = $schoolList;
        $this->schools = $schools;
        $this->academicSessions =  AcademicSession::select('id', 'name')
            ->where('is_active', 1)
            ->get()
            ->unique('name')
            ->pluck('name', 'id');
        $this->grades     = Grade::pluck('name', 'id')->toArray();

        if ($userData) {
            $this->userData                = $userData;
            $this->schoolType = $userData->schoolDetails->school_type ?? null;
            $this->schoolRole = $userData->schoolDetails->school_role ?? null;
            $this->selectedRole            = $userData->userRole->role ?? null;
            $this->selectedClasses         = SchoolAssignedClass::where('school_id', $userData->id ?? null)->pluck('class_id')->toArray() ?? null;
            $this->selectedSubjects        = UserAdditionalDetail::where('user_id', $userData->id ?? null)->pluck('assigned_subjects')->toArray() ?? null;
            if ($this->selectedRole->role_slug == 'school_admin') {
                $this->selectedSession = $userData->schoolDetails->academic_session_id;
                $this->selectedBatch = $userData->schoolDetails->batch_id;
                $this->getSessionBatches($this->selectedSession);
            }

            if ($this->selectedRole->role_slug == 'school_teacher') {
                $this->selectedTeacherClasses  = explode(',', $userData->userAdditionalDetail->assigned_classes) ?? null;
                $this->selectedTeacherSubjects = explode(',', $userData->userAdditionalDetail->assigned_subjects) ?? null;
                $this->selectedSchool = $userData->userAdditionalDetail->school_id;
                $this->schoolChanged($this->selectedSchool);
            }
            if ($this->selectedRole->role_slug == 'school_student') {
                $this->selectedStudentClass  =  $userData->studentDetails->class ?? null;
                $this->selectedSchool = $userData->userAdditionalDetail->school_id;
                $this->schoolChanged($this->selectedSchool);
            }
            if ($userData->userAdditionalDetail && $userData->userAdditionalDetail->state) {
                $this->selectedState = $userData->userAdditionalDetail->state;
                $this->city          = $userData->userAdditionalDetail->city;
                $this->stateChanged($this->selectedState);
            }
        } else {
            $this->userData      = null;
            $this->selectedRole  = null;
            $this->selectedState = null;

            $this->stateChanged($this->selectedState);
            $this->schoolChanged($this->selectedSchool);
            $this->selectedClasses         = [];
            $this->selectedTeacherClasses  = [];
            $this->selectedSchool  = null;
            $this->selectedTeacherSubjects = [];
        }
    }

    public function stateChanged($stateId)
    {
        $this->cities = City::where('state_id', $stateId)
            ->pluck('city', 'id')
            ->toArray();

        if ($stateId) {
            $state = State::find($stateId);
            if ($state) {
                $stateCode = strtoupper(substr($state->name, 0, 2));
                if ($this->userData) {
                    if (empty($this->userData->schoolDetails->unique_id)) {
                        do {
                            $randomNumber = mt_rand(1000, 9999);
                            $uniqueId = $stateCode . $randomNumber;
                            $existingSchool = Schools::where('unique_id', $uniqueId)->first();
                        } while ($existingSchool);
                        $this->schoolUniqueId = $uniqueId;
                        $this->uniqueSchoolId = $uniqueId;
                    } else {
                        $this->schoolUniqueId = $this->userData->unique_id;
                    }
                } else {
                    do {
                        $randomNumber = mt_rand(1000, 9999);
                        $uniqueId = $stateCode . $randomNumber;
                        $existingSchool = Schools::where('unique_id', $uniqueId)->first();
                    } while ($existingSchool);
                    $this->schoolUniqueId = $uniqueId;
                }
            }
        }
    }

    public function schoolChanged($schoolId)
    {
        $this->loadClasses = getUserSchoolClasses($schoolId);
    }

    public function getSchoolType($value)
    {
        $this->schoolType = $value;
    }
    public function getSchoolRole($value)
    {
        $this->schoolRole = $value;
    }

    public function getSessionBatches($sessionId)
    {
        if ($sessionId) {
            $sessionName = AcademicSession::where('id', $sessionId)->value('name');
            $this->batches = AcademicSession::where('name', $sessionName)
                ->pluck('batch_name', 'id')
                ->toArray();
        } else {
            $this->batches = [];
        }
    }

    public function roleChanged() {}

    public function render()
    {
        return view('livewire.erp-add-school-form', [
            'cities'  => $this->cities,
            'states'  => $this->states,
            'classes' => $this->classes,
            'batches' => $this->batches,
            'loadClasses' => $this->loadClasses,
            'uniqueId' => $this->schoolUniqueId,
        ]);
    }
}
