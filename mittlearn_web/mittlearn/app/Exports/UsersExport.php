<?php

namespace App\Exports;

use App\Models\AcademicSession;
use App\Models\Board;
use App\Models\Category;
use App\Models\City;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\State;
use App\Models\Subject;
use App\Models\SubscriptionPurchase;
use App\Models\User;
use App\Models\UserClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{
    protected $roleSlug;

    protected $filters;

    public function __construct($roleSlug = null, $filters = [])
    {
        $this->roleSlug = $roleSlug;
        $this->filters = $filters;
    }
    public function collection()
    {
        if (!$this->roleSlug) {
            return collect([]);
        }

        $query = User::query();

        switch ($this->roleSlug) {
            case 'school_admin':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('schools', 'users.id', '=', 'schools.user_id')
                    ->leftJoin('school_classes', 'schools.id', '=', 'school_classes.school_id') // ✅ Added this
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->where('schools.is_verified_by_admin', 1)
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'schools.unique_id as unique_id',
                        'schools.school_type as school_type',
                        'users.name',
                        'users.status',
                        'schools.school_role as school_role',
                        'users.id as user_id',
                        'users.username',
                        'users.email',
                        'users.mobile_no',
                        'users.validate_string',
                        'schools.state as state',
                        'schools.city as city',
                        'schools.postal_code as postal_code',
                        'schools.address as address1',
                        'user_additional_details.address as address2',
                        'schools.academic_session_id as academic_session_id',
                        'schools.batch_id as batch_id',
                        'user_additional_details.assign_to as assign_to',
                        'user_additional_details.website as website',
                        'user_additional_details.decision_maker as decision_maker',
                        'user_additional_details.decision_maker_mobile_no as decision_maker_mobile_no',
                        'user_additional_details.decision_maker_role as decision_maker_role',
                        'user_additional_details.school_board as school_board',
                        'user_additional_details.school_medium as school_medium',
                        'user_additional_details.strength as strength',
                        'user_additional_details.grade as grade',
                        'user_additional_details.school_affiliation_no as school_affiliation_no',
                        'user_additional_details.school_registration_no as school_registration_no',
                        'user_additional_details.incorporation_date as incorporation_date',
                        'user_additional_details.assign_distributor as assign_distributor',
                        'user_additional_details.gst_no as gst_no',
                        'user_additional_details.bank_name as bank_name',
                        'user_additional_details.acc_holder_name as acc_holder_name',
                        'user_additional_details.branch_name as branch_name',
                        'user_additional_details.acc_no as acc_no',
                        'user_additional_details.ifsc_code as ifsc_code',
                        'user_additional_details.customer_type as customer_type',
                        'users.created_at',
                    ]);
                $this->applyCommonFilters($query);

                if (isset($this->filters['school_type'])) {
                    $query->where('schools.school_type', $this->filters['school_type']);
                }
                break;
            case 'school_teacher':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->leftJoin('schools', 'user_additional_details.school_id', '=', 'schools.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'schools.unique_id as unique_id',
                        'schools.name as school_name',
                        'users.validate_string',
                        'user_additional_details.gender as gender',
                        'user_additional_details.age as age',
                        'user_additional_details.address as address',
                        'user_additional_details.state as state',
                        'user_additional_details.city as city',
                        'user_additional_details.qualification as qualification',
                        'user_additional_details.dob as dob',
                        'user_additional_details.assigned_classes as assigned_classes',
                        'user_additional_details.assigned_subjects as assigned_subjects',
                        'user_additional_details.experience as experience',
                    ]);

                // Apply filters for school_teacher
                $this->applyCommonFilters($query);

                if (isset($this->filters['school_name'])) {
                    $query->where('schools.name', 'like', '%' . $this->filters['school_name'] . '%');
                }
                break;
            case 'school_student':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('student_details', 'users.id', '=', 'student_details.user_id')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->leftJoin('schools', 'student_details.school_id', '=', 'schools.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'schools.unique_id as unique_id',
                        'schools.name as school_name',
                        'users.validate_string',
                        'student_details.parent_name as parent_name',
                        'user_additional_details.admission_no as admission_no',
                        'student_details.doj as doj',
                        'student_details.dob as dob',
                        'student_details.class as class',
                        'student_details.section as section',
                    ]);

                $this->applyCommonFilters($query);
                if (isset($this->filters['school_name'])) {
                    $query->where('schools.name', 'like', '%' . $this->filters['school_name'] . '%');
                }
                break;
            case 'b2c_student':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('student_details', 'users.id', '=', 'student_details.user_id')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->leftJoin('schools', 'student_details.school_id', '=', 'schools.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.id',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'users.validate_string',
                        'student_details.class as class',
                        'users.created_at',
                    ]);
                $this->applyCommonFilters($query);

                break;
            case 'd2c_user':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('student_details', 'users.id', '=', 'student_details.user_id')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->leftJoin('user_classes', 'users.id', '=', 'user_classes.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.id',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'users.validate_string',
                        'user_classes.class_id as class',
                        'student_details.section as section',
                        'student_details.roll_number as roll_number',
                        'student_details.option_a as option_a',
                        'student_details.option_b as option_b',
                        'student_details.d2c_user_school_name as school_name',
                        'student_details.school_pincode as school_pincode',
                        'student_details.school_state as school_state',
                        'student_details.school_district as school_district',
                        'student_details.school_address_1 as school_address_1',
                    ]);

                $this->applyCommonFilters($query);
                if (isset($this->filters['category'])) {
                    $catId = Category::where('slug', 'like', '%' . $this->filters['category'] . '%')->value('id');
                    $query->where('user_classes.category_id', $catId);
                }
                break;
            case 'leaders':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.id',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'users.validate_string',
                        'user_additional_details.gender as gender',
                        'user_additional_details.age as age',
                        'user_additional_details.designation as designation',
                        'user_additional_details.about as about',
                        'user_additional_details.facebook as facebook',
                        'user_additional_details.instagram as instagram',
                        'user_additional_details.linkedin as linkedin',
                        'user_additional_details.twitter as twitter',
                    ]);

                $this->applyCommonFilters($query);
                break;
            case 'salesman':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.id',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'user_additional_details.employee_id as employee_id',
                        'user_additional_details.city as city',
                        'user_additional_details.state as state',
                        'user_additional_details.address as address',
                    ]);

                $this->applyCommonFilters($query);
                break;
            case 'distributors':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.id',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'user_additional_details.distributor_id as distributor_id',
                        'user_additional_details.city as city',
                        'user_additional_details.state as state',
                        'user_additional_details.address as address',
                    ]);

                $this->applyCommonFilters($query);
                break;
            case 'instructor':
                $query->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
                    ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
                    ->where('roles.role_slug', $this->roleSlug)
                    ->select([
                        'roles.role_name as role',
                        'users.name',
                        'users.id',
                        'users.email',
                        'users.mobile_no',
                        'users.status',
                        'user_additional_details.about as about',
                        'user_additional_details.designation as designation',
                        'user_additional_details.city as city',
                        'user_additional_details.state as state',
                        'user_additional_details.address as address',
                    ]);

                $this->applyCommonFilters($query);
                break;

            default:
                collect([]);
        }

        $data = $query->get();
        return $data->map(function ($item, $index) {
            if ($this->roleSlug === 'school_admin') {
                $state = State::find($item->state);
                $city = City::find($item->city);
                $academicSession = AcademicSession::find($item->academic_session_id);
                $batchName = AcademicSession::find($item->batch_id);
                $assignTo = User::find($item->assign_to);
                $decisionMakerRole = Role::find($item->decision_maker_role);
                $board = Board::find($item->school_board);
                $medium = Medium::find($item->school_medium);
                $grade = Grade::find($item->grade);
                $assignDistributor = User::find($item->assign_distributor);
                $schoolClaases = SchoolAssignedClass::where('school_id', $item->user_id)->pluck('class_id');
                $classNames = Classes::whereIn('id', $schoolClaases)->pluck('name');
                if ($item->status === 1) {
                    $status = 'Active';
                } else {
                    $status = "Inactive";
                }
                return [
                    'S.No'         => $index + 1,
                    'Role'         => $item->role ?? 'N/A',
                    'Unique ID'    => $item->unique_id ?? 'N/A',
                    'Full Name'         => $item->name ?? 'N/A',
                    'School Role'         => $item->school_role ?? 'N/A',
                    'School Type'    => $item->school_type ?? 'N/A',
                    'Username'     => $item->username ?? 'N/A',
                    'Email'        => $item->email ?? 'N/A',
                    'Mobile No.'    => $item->mobile_no ?? 'N/A',
                    'Password' => $item->validate_string ?? 'N/A',
                    'Status' => $status ?? 'N/A',
                    'Academic Session' => $academicSession->name ?? 'N/A',
                    'Batch Name' => $batchName->batch_name ?? 'N/A',
                    'Address 1' => $item->address1 ?? 'N/A',
                    'Address 2' => $item->address2 ?? 'N/A',
                    'State' => $state->name ?? 'N/A',
                    'District' => $city->city ?? 'N/A',
                    'Pin Code' => $item->postal_code ?? 'N/A',
                    'Assign To' => $assignTo->name ?? 'N/A',
                    'Website' => $item->website ?? 'N/A',
                    'Customer Type' => $item->customer_type ?? 'N/A',
                    'Decision Maker' => $item->decision_maker ?? 'N/A',
                    'Decision Maker Mobile No.' => $item->decision_maker_mobile_no ?? 'N/A',
                    'Decision Maker Role' => $decisionMakerRole->role_name ?? 'N/A',
                    'Board' => $board->name ?? 'N/A',
                    'Medium' => $medium->name ?? 'N/A',
                    'Strenght' => $item->strength ?? 'N/A',
                    'Grade' => $grade->name ?? 'N/A',
                    'School Affiliation Number/PAN Number' => $item->school_affiliation_no ?? 'N/A',
                    'School Registration Number' => $item->school_registration_no ?? 'N/A',
                    'Incorporation Date' => $item->incorporation_date ?? 'N/A',
                    'Assign Distributor' => $assignDistributor->name ?? 'N/A',
                    'GST No.' => $item->gst_no ?? 'N/A',
                    'Classes' => $classNames->implode(', ') ?? 'N/A',
                    'Bank Name' => $item->bank_name ?? 'N/A',
                    'Bank Account Holder Name' => $item->acc_holder_name ?? 'N/A',
                    'Branch Name' => $item->branch_name ?? 'N/A',
                    'Bank Account Number' => $item->acc_no ?? 'N/A',
                    'IFSC Code' => $item->ifsc_code ?? 'N/A',
                    'Created At'   => $item->created_at ?? 'N/A',
                ];
            } elseif ($this->roleSlug === 'school_teacher') {
                $state = State::find($item->state);
                $city = City::find($item->city);
                $classNames = Classes::whereIn('id', explode(',', $item->assigned_classes))->pluck('name');
                $subjectNames = Subject::whereIn('id', explode(',', $item->assigned_subjects))->pluck('name');
                if ($item->status === 1) {
                    $status = 'Active';
                } else {
                    $status = "Inactive";
                }
                return [
                    'S.No'         => $index + 1,
                    'Role'         => $item->role ?? 'N/A',
                    'Full Name'         => $item->name ?? 'N/A',
                    'Email'        => $item->email ?? 'N/A',
                    'Mobile No.'    => $item->mobile_no ?? 'N/A',
                    'School ID' => $item->unique_id ?? 'N/A',
                    'School' => $item->school_name ?? 'N/A',
                    'Status' => $status ?? 'N/A',
                    'Password' => $item->validate_string ?? 'N/A',
                    'Gender' => $item->gender ?? 'N/A',
                    'Age' => $item->age ?? 'N/A',
                    'Address' => $item->address ?? 'N/A',
                    'State' => $state->name ?? 'N/A',
                    'City' => $city->city ?? 'N/A',
                    'Qualification' => $item->qualification ?? 'N/A',
                    'Date Of Birth' => \Carbon\Carbon::parse($item->dob)->format('d-m-Y') ?? 'N/A',
                    'Assgined Classes' => $classNames->implode(', ') ?? 'N/A',
                    'Assgined Subject' => $subjectNames->implode(', ') ?? 'N/A',
                    'Experience' => $item->experience ?? 'N/A',
                ];
            } elseif ($this->roleSlug === 'school_student') {
                $state = State::find($item->state);
                $city = City::find($item->city);
                $className = Classes::where('id',  $item->class)->value('name');
                $sectionName = Section::where('id',  $item->section)->value('section_name');
                if ($item->status === 1) {
                    $status = 'Active';
                } else {
                    $status = "Inactive";
                }
                return [
                    'S.No'         => $index + 1,
                    'Role'         => $item->role ?? 'N/A',
                    'Full Name'         => $item->name ?? 'N/A',
                    'Email'        => $item->email ?? 'N/A',
                    'Parent/Guardian Mobile Number'    => $item->mobile_no ?? 'N/A',
                    'School ID' => $item->unique_id ?? 'N/A',
                    'School' => $item->school_name ?? 'N/A',
                    'Status' => $status ?? 'N/A',
                    'Password' => $item->validate_string ?? 'N/A',
                    'Admission No./Sr.No.' => $item->admission_no ?? 'N/A',
                    'Admission Date' => \Carbon\Carbon::parse($item->doj)->format('d-m-Y') ?? 'N/A',
                    'Date Of Birth' => \Carbon\Carbon::parse($item->dob)->format('d-m-Y') ?? 'N/A',
                    'Class' => $className ?? 'N/A',
                    'Section' => $sectionName ?? 'N/A',
                ];
            } elseif ($this->roleSlug === 'b2c_student') {
                $subscription = SubscriptionPurchase::where('user_id', $item->id)->first();

                $academicCourses = [];
                $nonAcademicCourses = [];

                if ($subscription) {
                    $courses = json_decode($subscription->courses_json);

                    if (!empty($courses->academic_courses)) {
                        $academicCourses = collect($courses->academic_courses)->pluck('course_name')->toArray();
                    }

                    if (!empty($courses->non_academic_courses)) {
                        $nonAcademicCourses = collect($courses->non_academic_courses)->pluck('course_name')->toArray();
                    }
                }

                $className = Classes::where('id',  $item->class)->value('name');

                $status = $item->status === 1 ? 'Active' : 'Inactive';

                return [
                    'S.No'                    => $index + 1,
                    'Role'                    => $item->role ?? 'N/A',
                    'Full Name'               => $item->name ?? 'N/A',
                    'Email'                   => $item->email ?? 'N/A',
                    'Mobile No.'              => $item->mobile_no ?? 'N/A',
                    'Class'                   => $className ?? 'N/A',
                    'Status'                  => $status ?? 'N/A',
                    'Password'                => $item->validate_string ?? 'N/A',
                    'Academic Courses'        => implode(', ', $academicCourses) ?? 'N/A',
                    'Non-Academic Courses'    => implode(', ', $nonAcademicCourses) ?? 'N/A',
                    'Created At'   => $item->created_at ?? 'N/A',
                ];
            } elseif ($this->roleSlug === 'd2c_user') {
                $subscription = SubscriptionPurchase::where('user_id', $item->id)->first();

                $academicCourses = [];
                $nonAcademicCourses = [];

                if ($subscription) {
                    $courses = json_decode($subscription->courses_json);

                    if (!empty($courses->academic_courses)) {
                        $academicCourses = collect($courses->academic_courses)->pluck('course_name')->toArray();
                    }

                    if (!empty($courses->non_academic_courses)) {
                        $nonAcademicCourses = collect($courses->non_academic_courses)->pluck('course_name')->toArray();
                    }
                }
                $sectionName = Section::where('id',  $item->section)->value('section_name');

                $className = Classes::where('id',  $item->class)->value('name');
                $categoryIds = UserClass::where('user_id', $item->id)->where('class_id', $item->class)
                    ->where('user_role', $this->roleSlug)
                    ->pluck('category_id');

                $categoryNames = Category::whereIn('id', $categoryIds)->pluck('name')->toArray();
                $state = State::find($item->school_state);
                $city = City::find($item->school_district);
                $status = $item->status === 1 ? 'Active' : 'Inactive';

                // Decode options
                $optionA = json_decode($item->option_a ?? '{}');
                $optionB = json_decode($item->option_b ?? '{}');

                // Check if both options are empty/null
                $hasOptions = !empty((array) $optionA) || !empty((array) $optionB);

                $scienceSelected  = $hasOptions ? (!empty($optionA->science) || !empty($optionB->science) ? 'Yes' : 'No') : '';
                $mathSelected     = $hasOptions ? (!empty($optionA->mathematics) || !empty($optionB->mathematics) ? 'Yes' : 'No') : '';
                $requiredSci      = $hasOptions ? (!empty($optionA->science) ? 'Yes' : 'No') : '';
                $requiredMath     = $hasOptions ? (!empty($optionA->mathematics) ? 'Yes' : 'No') : '';

                return [
                    'S.No'                    => $index + 1,
                    'Role'                    => $item->role ?? 'N/A',
                    'Full Name'               => $item->name ?? 'N/A',
                    'Email'                   => $item->email ?? 'N/A',
                    'Mobile No.'              => $item->mobile_no ?? 'N/A',
                    'Categories'              => implode(', ', $categoryNames) ?? 'N/A',
                    'Class'                   => $className ?? 'N/A',
                    'Status'                  => $status ?? 'N/A',
                    'Password'                => $item->validate_string ?? 'N/A',
                    'Section'                 => $sectionName ?? 'N/A',
                    'Roll No.'                => $item->roll_number ?? 'N/A',
                    'School Name'             => $item->school_name ?? 'N/A',
                    'School Pin Code'         => $item->school_pincode ?? 'N/A',
                    'School State'            => $state->name ?? 'N/A',
                    'School District'         => $city->city ?? 'N/A',
                    'School Address'          => $item->school_address_1 ?? 'N/A',
                    'Science'                 => $scienceSelected,
                    'Study Material Required Sc.'  => $requiredSci,
                    'Mathematics'             => $mathSelected,
                    'Study Material Required Math' => $requiredMath,
                ];
            } elseif ($this->roleSlug === 'leaders') {

                $status = $item->status === 1 ? 'Active' : 'Inactive';

                return [
                    'S.No'                    => $index + 1,
                    'Role'                    => $item->role ?? 'N/A',
                    'Full Name'               => $item->name ?? 'N/A',
                    'Email'                   => $item->email ?? 'N/A',
                    'Mobile No.'              => $item->mobile_no ?? 'N/A',
                    'Status'                  => $status ?? 'N/A',
                    'Password'                => $item->validate_string ?? 'N/A',
                    'Gender'                => $item->gender ?? 'N/A',
                    'Age'                => $item->age ?? 'N/A',
                    'Designation'                => $item->designation ?? 'N/A',
                    'About Leader'                => $item->designation ?? 'N/A',
                    'Facebook'                => $item->facebook ?? 'N/A',
                    'Instagram'                => $item->instagram ?? 'N/A',
                    'LinkedIn'                => $item->linkedin ?? 'N/A',
                    'Twitter'                => $item->twitter ?? 'N/A',
                ];
            } elseif ($this->roleSlug === 'salesman') {
                $state = State::find($item->state);
                $city = City::find($item->city);
                $status = $item->status === 1 ? 'Active' : 'Inactive';

                return [
                    'S.No'                    => $index + 1,
                    'Role'                    => $item->role ?? 'N/A',
                    'Employee ID'             => $item->employee_id ?? 'N/A',
                    'Full Name'               => $item->name ?? 'N/A',
                    'Email'                   => $item->email ?? 'N/A',
                    'Mobile No.'              => $item->mobile_no ?? 'N/A',
                    'Status'                  => $status ?? 'N/A',
                    'Address'                 => $item->address ?? 'N/A',
                    'State'                   => $state->name ?? 'N/A',
                    'City'                    => $city->city ?? 'N/A',
                ];
            } elseif ($this->roleSlug === 'distributors') {
                $state = State::find($item->state);
                $city = City::find($item->city);
                $status = $item->status === 1 ? 'Active' : 'Inactive';

                return [
                    'S.No'                    => $index + 1,
                    'Role'                    => $item->role ?? 'N/A',
                    'Distributor ID'             => $item->distributor_id ?? 'N/A',
                    'Full Name'               => $item->name ?? 'N/A',
                    'Email'                   => $item->email ?? 'N/A',
                    'Mobile No.'              => $item->mobile_no ?? 'N/A',
                    'Status'                  => $status ?? 'N/A',
                    'Address'                 => $item->address ?? 'N/A',
                    'State'                   => $state->name ?? 'N/A',
                    'City'                    => $city->city ?? 'N/A'
                ];
            } elseif ($this->roleSlug === 'instructor') {
                $state = State::find($item->state);
                $city = City::find($item->city);
                $status = $item->status === 1 ? 'Active' : 'Inactive';

                return [
                    'S.No'                    => $index + 1,
                    'Role'                    => $item->role ?? 'N/A',
                    'Full Name'               => $item->name ?? 'N/A',
                    'Email'                   => $item->email ?? 'N/A',
                    'Mobile No.'              => $item->mobile_no ?? 'N/A',
                    'Status'                  => $status ?? 'N/A',
                    'About Instructor'                 => $item->about ?? 'N/A',
                    'Instructor Post/Designation'                 => $item->designation ?? 'N/A',
                    'Address'                 => $item->address ?? 'N/A',
                    'State'                   => $state->name ?? 'N/A',
                    'City'                    => $city->city ?? 'N/A'
                ];
            }
        });
    }
    protected function applyCommonFilters($query)
    {
        if (isset($this->filters['name'])) {
            $query->where('users.name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (isset($this->filters['email'])) {
            $query->where('users.email', 'like', '%' . $this->filters['email'] . '%');
        }

        if (isset($this->filters['mobile_no'])) {
            $query->where('users.mobile_no', 'like', '%' . $this->filters['mobile_no'] . '%');
        }

        if (isset($this->filters['status'])) {
            $query->where('users.status', $this->filters['status']);
        }
    }
    public function headings(): array
    {
        switch ($this->roleSlug) {
            case 'school_admin':
                return ['S.No', 'Role', 'Unique ID', 'Full Name', 'School Role',  'School Type', 'Username', 'Email', 'Mobile No.', 'Password', "Status",  'Academic Session', 'Batch Name', 'Address 1', 'Address 2',  'State', 'District',  'Pin Code', 'Assign To', 'Website',  'Customer Type', 'Decision Maker', 'Decision Maker Mobile No.', 'Decision Maker Role', 'Board', 'Medium', 'Strenght', 'Grade', 'School Affiliation Number/PAN Number', 'School Registration Number', 'Incorporation Date', 'Assign Distributor', 'GST No.', 'Classes', 'Bank Name', 'Bank Account Holder Name',  'Branch Name',  'Bank Account Number', 'IFSC Code', 'Created At'];
            case 'school_teacher':
                return ['S.No', 'Role', 'Full Name', 'Email', 'Mobile No.', 'School ID', 'School', 'Status', 'Password', 'Gender', 'Age', 'Address', 'State', 'city', 'Qualification', 'Date Of Birth', 'Assgined Classes', 'Assgined Subject', 'Experience'];
            case 'school_student':
                return ['S.No', 'Role', 'Full Name', 'Email', 'Parent/Guardian Mobile Number', 'School ID', 'School', 'Status', 'Password', 'Admission No./Sr.No.', 'Admission Date', 'Date Of Birth', 'Class', 'Section'];
            case 'b2c_student':
                return ['S.No', 'Role', 'Full Name', 'Email', 'Mobile No.', 'Class', 'Status', 'Password',  'Academic Courses', 'Non-Academic Courses', 'Created At'];
            case 'd2c_user':
                return ['S.No', 'Role', 'Full Name', 'Email', 'Mobile No.', 'Category', 'Class', 'Status', 'Password',  'Section', 'Roll No.',    'School Name', 'School Pin Code', 'School State', 'School District', 'School Address', 'Science', 'Study Material Required Sc.', 'Mathematics', 'Study Material Required Math',];
            case 'leaders':
                return ['S.No',  'Role', 'Full Name', 'Email', 'Mobile No.',  'Status', 'Password', 'Gender', 'Age', 'Designation', 'About Leader', 'Facebook', 'Instagram', 'LinkedIn', 'Twitter'];
            case 'salesman':
                return ['S.No',   'Role', 'Employee ID', 'Full Name', 'Email', 'Mobile No.',  'Status', 'Address', 'State', 'City'];
            case 'distributors':
                return ['S.No',   'Role', 'Distributor ID', 'Full Name', 'Email', 'Mobile No.',  'Status', 'Address', 'State', 'City'];
            case 'instructor':
                return ['S.No',   'Role', 'Full Name', 'Email', 'Mobile No.',  'Status',   'About Instructor', 'Instructor Post/Designation', 'Address', 'State', 'City'];
            default:
                return [];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
            ],
            'A:Z' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
        ];
    }
}
