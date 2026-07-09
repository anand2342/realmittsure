<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\City;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\State;
use App\Models\UserAdditionalDetail;
use Livewire\Component;

class RoleForm extends Component
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
    public $verify;
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
    public $categories;
    public $categoriesForD2C;
    public $sessionBatches;
    public $courseData;
    public $selectedSession = null;
    public $selectedBatch = null;
    public $schoolRole;
    public $batches = [];
    public $courses = [];
    public $subCategories = [];
    public $selectedCategory = null;
    public $selectedSubCategory = null;
    public $selectedState = null;
    public $city          = null;
    public $query = '';
    public $slug;
    public $selectedSeachedSchool = null;
    public $filteredSchools = [];
    public $option_field = [];
    public $schoolName;
    public $a_checkbox1;
    public $a_checkbox2;

    public function mount($roles, $schoolList, $schools, $courseData, $users, $salesman, $distributors, $boards, $mediums, $classes, $subjects, $cities = [],  $states, $userData = null, $school_classes = null)
    {
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
        $this->courseData = $courseData;

        $this->academicSessions =  AcademicSession::select('id', 'name')
            ->where('is_active', 1)
            ->get()
            ->unique('name')
            ->pluck('name', 'id');
        $this->grades     = Grade::pluck('name', 'id')->toArray();
        $this->categories     = Category::where('status', 1)->where('id', 2)->pluck('name', 'id')->toArray();
        $this->categoriesForD2C     = Category::whereIn('id', [35, 36, 37])->pluck('name', 'id')->toArray();

        if ($userData) {
            $this->userData                = $userData;
            $this->schoolType = $userData->schoolDetails->school_type ?? null;
            $this->schoolRole = $userData->schoolDetails->school_role ?? null;
            $this->selectedRole            = $userData->userRole->role ?? old('role');
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
            if ($userData->studentDetails && $userData->studentDetails->school_state) {
                $this->selectedState = $userData->studentDetails->school_state;
                $this->city          = $userData->studentDetails->school_district;
                $this->stateChanged($this->selectedState);
            }
        } else {
            $this->userData      = null;
            $this->selectedRole  =  old('role');
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
    public function getSubCategories($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->subCategories = Category::where('status', 1)->where('parent_id', 2)->pluck('name', 'id')->toArray();
    }
    public function getClassesForD2C($categoryId)
    {
        $this->slug = Category::where('id', $categoryId)->value('slug');
        $classSubjectsRaw = BookSeries::where('slug', $this->slug)
            ->pluck('class_subjects')
            ->first();

        $classSubjects = json_decode($classSubjectsRaw, true);

        $classIds = collect($classSubjects)
            ->pluck('class_id')
            ->unique()
            ->values()
            ->all();

        $this->classes = SchoolClass::whereIn('id', $classIds)
            ->pluck('name', 'id') // returns [id => name]
            ->toArray();
    }
    public function getTalentCourses($subcategoryId)
    {
        $this->selectedSubCategory = $subcategoryId;
        $this->courses = Course::where('category_id', 2)->where('sub_category_id', $subcategoryId)->where('is_active', 1)
            ->pluck('course_name', 'id')
            ->toArray();
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

    public function searchSchools($query)
    {
        $this->filteredSchools = Schools::where('name', 'like', "%{$query}%")
            ->get()
            ->toArray();
    }

    public function addNewSchool($name)
    {
        $school = Schools::create(['name' => $name]);
        $this->selectedSeachedSchool = $school->id;
        $this->query = $school->name;
        $this->filteredSchools = [];
    }

    public function optionChanged()
    {
        // dd($this->option_field);
    }
    public function roleChanged() {}

    public function render()
    {
        return view('livewire.role-form', [
            'cities'  => $this->cities,
            'states'  => $this->states,
            'classes' => $this->classes,
            'batches' => $this->batches,
            'loadClasses' => $this->loadClasses,
            'uniqueId' => $this->schoolUniqueId,
            'verify' => $this->verify,
            'option_field' => $this->option_field,
        ]);
    }
}
