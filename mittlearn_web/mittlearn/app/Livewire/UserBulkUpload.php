<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\UserController;
use App\Models\AcademicSession;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\City;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\Section;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\SubscriptionPurchase;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use App\Models\UserRole;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserBulkUpload extends Component
{
    use WithFileUploads;
    public $roles;
    public $redirectRole;
    public $file;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = []; // Store errors for specific rows and columns
    public function mount($roles = null)
    {
        $this->roles = $roles;
    }

    public function uploadUsers()
    {
        $this->resetImportState();

        $this->isLoading = true;
        if ($this->file) {

            try {
                // Convert the Excel file into an array
                $data = Excel::toArray([], $this->file)[0];

                if (empty($data) || !isset($data[0])) {
                    session()->flash('errorMsg', ['The file is empty or has invalid formatting.']);
                    return;
                }


                // Extract headers dynamically and remove empty ones
                $this->headers = array_values(array_filter($data[0], function ($header) {
                    return !empty($header);
                }));

                // Process the remaining rows dynamically
                $this->uploadedData = array_filter(array_map(function ($row) {
                    if (empty(array_filter($row))) {
                        return null; // Skip empty rows
                    }

                    // Match row with header count
                    $row = array_pad($row, count($this->headers), null);
                    $row = array_slice($row, 0, count($this->headers));

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }

                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)));

                // Remove null rows
                $this->uploadedData = array_values($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading = false;
            } catch (\Exception $e) {
                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
    }

    /**
     * Check if a column should be treated as a date
     */
    private function isDateColumn($columnName)
    {
        $dateColumns = ['Admission Date', 'Date of Birth', 'Joining Date', 'Enrollment Date', 'Incorporation Date', 'Dob']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }

    public function convertToSnakeCase($string)
    {
        $string = preg_replace('/\s+/', '_', $string);
        $string = str_replace('*', '', $string);
        $string = strtolower($string);
        return $string;
    }

    public function processSelectedData()
    {
        if (empty($this->selectedData)) {
            session()->flash('errorMsg', 'No data selected for processing.');
            return;
        }

        $this->rowErrors = []; // Reset row errors
        $successCount = 0;

        // Start a database transaction for the entire batch
        DB::beginTransaction();

        try {
            // First pass: Validate all selected rows
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->validateRow($convertedRow, $rowKey);
            }

            $redirectRole = $this->uploadedData[0]['Role*'] ?? null;

            // Second pass: Save all rows (only reaches here if all validation passed)
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }
                $convertedRow = $this->convertRowKeys($row);
                $this->saveDataBasedOnRole($convertedRow, $rowKey);
                $successCount++;
            }

            $role = Role::where('role_name', $redirectRole)->first();           
            $this->redirectRole = $role->role_slug ?? 'b2c_student';;

            DB::commit();

            if ($successCount > 0) {
                // session()->flash('successMsg', "{$successCount} row(s) successfully saved!");
                $this->file = null;
                $this->isModalOpen = false;
                return redirect()->route('user.index', ['role' => $this->redirectRole])->with('success', "{$successCount} row(s) successfully saved!"); // or ->to('/your-url')
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleImportError($e);
        }
    }
    private function convertRowKeys(array $row): array
    {
        $converted = [];
        foreach ($row as $key => $value) {
            $converted[$this->convertToSnakeCase($key)] = $value;
        }
        return $converted;
    }
    private function validateRow(array $data, string $rowKey)
    {
        // Validate role exists
        $role = Role::where('role_name', $data['role'])->first();
        if (!$role) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['role' => ["Role '{$data['role']}' does not exist."]]
            ]));
        }
        $data['role'] = $role->role_slug;
        // Role-specific validation
        switch ($data['role']) {
            case 'b2c_student':
                $this->validateUser($data, $rowKey);
                break;
            case 'super_admin':
            case 'admin':
            case 'school_admin':
                $this->validateSchoolAdmin($data, $rowKey);
                break;
            case 'school_teacher':
                $this->validateTeacher($data, $rowKey);
                break;
            case 'school_student':
                $this->validateStudent($data, $rowKey);
                break;
            case 'd2c_user':
                $this->validateD2cStudent($data, $rowKey);
                break;
            default:
                $this->validateDefaultUser($data, $rowKey);
                break;
        }
    }
    private function checkForDuplicateValues(array $uploadedData, string $field, string $errorKey)
    {
        $values = [];
        $duplicates = [];

        foreach ($uploadedData as $rowKey => $row) {
            $convertedRow = $this->convertRowKeys($row);
            $value = $convertedRow[$field] ?? null;

            if ($value !== null) {
                if (in_array($value, $values)) {
                    $duplicates[$rowKey] = $value;
                }
                $values[] = $value;
            }
        }

        if (!empty($duplicates)) {
            $errors = [];
            foreach ($duplicates as $rowKey => $value) {
                $errors[$rowKey] = ["The {$errorKey} '{$value}' is duplicated in the file"];
            }
            return $errors;
        }

        return null;
    }
    private function handleImportError(\Exception $e)
    {

        $errorData = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($errorData['row'])) {
            $this->rowErrors[$errorData['row']] = $errorData['errors'];
            session()->flash('errorMsg', 'Some rows have errors. No data was saved. Please review.');
        } else {
            session()->flash('errorMsg', 'Error during import: ' . $e->getMessage());
        }
    }
    /**
     * Save data based on the role type.
     */
    private function saveDataBasedOnRole(array $data, string $rowKey)
    {
        $role = Role::where('role_name', $data['role'])->first();
        if (!$role) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['role' => ["Role '{$data['role']}' does not exist."]]
            ]));
        }
        $data['role'] = $role->role_slug;
        switch ($data['role']) {
            case 'b2c_student':
                $this->saveUser($data, $rowKey);
                break;

            case 'super_admin':
            case 'admin':
            case 'school_admin':
                $this->saveSchoolAdmin($data, $rowKey);
                break;

            case 'school_teacher':
                $this->saveTeacher($data, $rowKey);
                break;

            case 'school_student':
                $this->saveStudent($data, $rowKey);
                break;

            case 'd2c_user':
                $this->saveD2cUser($data, $rowKey);
                break;

            default:
                $this->saveDefaultUser($data, $rowKey);
                break;
        }
    }

    /**
     * Save user data.
     */
    private function validateUser(array $data, string $rowKey)
    {
        // Check duplicate email
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'email', 'email')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['email' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        // Check duplicate mobile
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'mobile_no', 'mobile number')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mobile_no' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        // Validate base fields
        $validator = Validator::make($data, [
            'email' => 'nullable|email|unique:users,email',
            'role' => 'required|exists:roles,role_slug',
            'mobile_no' => 'required|numeric|digits:10|unique:users,mobile_no',
            'student_name' => 'required|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate class if provided
        if (!empty($data['class']) && !Classes::where('name', $data['class'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['class' => ['Class not found']]
            ]));
        }

        // Validate category-related logic
        if (!empty($data['category'])) {
            if (strtolower($data['category']) !== 'talent-skills') {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['category' => ["Please select only 'Talent-Skills'"]],
                ]));
            }

            // Validate category existence
            $categoryId = Category::where('status', 1)->whereNull('parent_id')
                ->whereRaw('LOWER(name) = ?', ['talent-skills'])
                ->value('id');

            if (!$categoryId) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['category' => ['Category not found']],
                ]));
            }

            // Validate sub-category
            if (empty($data['sub-category'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['sub-category' => ['Sub-Category is required when category is filled']],
                ]));
            }
            $subCategory = array_map('trim', explode(',', $data['sub-category']));

            $subCategoryId = Category::where('status', 1)->where('parent_id', $categoryId)
                ->whereIn('name', $subCategory)
                ->pluck('id')
                ->toArray();

            if (!$subCategoryId) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['sub-category' => ['Sub-Category not found']],
                ]));
            }

            // Validate courses
            if (empty($data['courses'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['courses' => ['Courses is required when category is filled']],
                ]));
            }

            $courseNames = array_map('trim', explode(',', $data['courses']));

            $matchedCourses = Course::where('category_id', $categoryId)
                ->whereIn('sub_category_id', $subCategoryId)
                ->whereIn('course_name', $courseNames)
                ->pluck('course_name')
                ->toArray();

            $unmatchedCourses = array_diff($courseNames, $matchedCourses);

            if (!empty($unmatchedCourses)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['courses' => ['These courses not found under the specified category and sub-category: ' . implode(', ', $unmatchedCourses)]],
                ]));
            }
        }
    }

    private function saveUser(array $data, string $rowKey)
    {
        $password = $data['password'] ?? 'Mitt@123';

        $user = User::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name' => $data['student_name'],
                'mobile_no' => $data['mobile_no'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'validate_string' => $password,
                'created_by' => Auth::id(),
                'is_email_verified' => 1,
                'is_mobile_verified' => 1,
            ]
        );

        UserRole::updateOrCreate(
            ['user_id' => $user->id],
            ['role_slug' => $data['role']]
        );
        $class = Classes::where('name', $data['class'])->value('id');
        StudentDetails::updateOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id, 'class' => $class ?? null]
        );

        UserAdditionalDetail::updateOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id, 'role_slug' => $data['role']]
        );

        if (!empty($data['category']) && strtolower($data['category']) === 'talent-skills') {
            $categoryId = Category::where('status', 1)->whereNull('parent_id')
                ->whereRaw('LOWER(name) = ?', ['talent-skills'])
                ->value('id');

            $subCategory = array_map('trim', explode(',', $data['sub-category']));

            $subCategoryId = Category::where('status', 1)->where('parent_id', $categoryId)
                ->whereIn('name', $subCategory)
                ->pluck('id')
                ->toArray();

            $courseNames = array_map('trim', explode(',', $data['courses']));

            $courses = Course::where('category_id', $categoryId)
                ->whereIn('sub_category_id', $subCategoryId)
                ->whereIn('course_name', $courseNames)
                ->get()
                ->groupBy('category_id');

            $academicCourses = $courses[1] ?? collect();
            $nonAcademicCourses = $courses[2] ?? collect();

            $mergedCourses = [
                'academic_courses' => $academicCourses->values()->toArray(),
                'non_academic_courses' => $nonAcademicCourses->values()->toArray(),
            ];

            $planJson = [
                'plan_id' => 3,
                'name' => 'Admin Assigned Plan',
                'plan_type' => 'custom',
                'currency' => 'INR',
                'description' => 'Courses assigned by admin',
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ];

            $existing = SubscriptionPurchase::where('user_id', $user->id)->where('plan_id', 3)->first();

            if ($existing) {
                $existing->update([
                    'courses_json' => json_encode($mergedCourses),
                    'end_date' => now()->addYear(),
                ]);
            } else {
                SubscriptionPurchase::create([
                    'user_id' => $user->id,
                    'plan_id' => 3,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                    'plan_json' => json_encode($planJson),
                    'courses_json' => json_encode($mergedCourses),
                    'transaction_id' => 'assigned_by_admin',
                    'status' => 'active',
                ]);
            }
        }
    }

    private function validateTeacher(array $data, string $rowKey)
    {
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'email', 'email')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['email' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'mobile_no', 'mobile number')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mobile_no' => $duplicateErrors[$rowKey]]
                ]));
            }
        }
        $validator = Validator::make($data, [
            'school_name' => 'required|max:255',
            'name' => 'required|max:255',
            'mobile_no' => 'required|numeric|unique:users,mobile_no',
            'email' => 'email|unique:users,email',
            'role' => 'required|exists:roles,role_slug',
            // 'country' => 'required|max:255',
            'assigned_class' => 'required|max:255',
            'assigned_subject' => 'required|max:255',
            'password' => 'required|min:8',
            'dob' => 'nullable|date_format:d/m/Y',

        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate school exists
        if (!Schools::where('name', $data['school_name'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['school_name' => ['School not found']]
            ]));
        }
        if ($data['gender']) {
            if (!in_array($data['gender'], ['Male', 'Female', 'Other'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['gender' => ['Only Accept "Male", "Female" and "Other"']]
                ]));
            }
        }
        // Validate state exists
        if ($data['state']) {
            $stateId = State::where('name', $data['state'])->value('id');
            if (!$stateId) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['state' => ['State not found']]
                ]));
            }
        }
        // Validate city exists
        if ($data['city'] && $stateId) {
            if (!City::where('state_id', $stateId)->where('city', $data['city'])->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['city' => ['City not found']]
                ]));
            }
        }

        // Validate classes
        $classes = explode(',', $data['assigned_class']);
        foreach ($classes as $class) {
            if (!Classes::where('name', $class)->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['assigned_class' => ["Class: {$class} not found"]]
                ]));
            }
        }
        if (!empty($data['school_name'])) {
            $school = Schools::where('name', $data['school_name'])->first();

            if (!$school) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['school_name' => ['School not found']]
                ]));
            }

            // Get school's assigned classes
            $schoolClasses = SchoolAssignedClass::where('school_id', $school->user_id)
                ->pluck('class_id')
                ->toArray();

            // Validate classes
            $classes = explode(',', $data['assigned_class']);
            foreach ($classes as $class) {
                $classdata = Classes::where('name', $class)->first();
                if (!in_array($classdata->id, $schoolClasses)) {
                    throw new \Exception(json_encode([
                        'row' => $rowKey,
                        'errors' => ['assigned_class' => ["Class: {$class} not Assgined for this school"]]
                    ]));
                }
            }
        }
        // Validate subjects
        $subjects = explode(',', $data['assigned_subject']);
        foreach ($subjects as $subject) {
            if (!Subject::where('name', $subject)->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['assigned_subject' => ["Subject: {$subject} not found"]]
                ]));
            }
        }
    }
    private function saveTeacher(array $data, string $rowKey)
    {
        $schoolId = Schools::where('name', $data['school_name'])->value('user_id');
        $stateId = State::where('name', $data['state'])->value('id');
        $cityId = City::where('state_id', $stateId)->where('city', $data['city'])->value('id');

        $classIds = [];
        foreach (explode(',', $data['assigned_class']) as $class) {
            $classIds[] = Classes::where('name', $class)->value('id');
        }

        $subjectIds = [];
        foreach (explode(',', $data['assigned_subject']) as $subject) {
            $subjectIds[] = Subject::where('name', $subject)->value('id');
        }

        $user = User::create([
            'name' => $data['name'] ?? null,
            'mobile_no' => $data['mobile_no'] ?? null,
            'email' => $data['email'] ?? null,
            'created_by' => Auth::id(),
            'password' => Hash::make($data['password']) ?? Hash::make('Mitt@123'),
            'validate_string' => $data['password'] ?? 'Mitt@123',
            'is_email_verified' => 1,
            'is_mobile_verified' => 1,
        ]);

        UserRole::create([
            'user_id' => $user->id ?? null,
            'role_slug' => $data['role'] ?? null,
        ]);



        UserAdditionalDetail::create([
            'role' => $data['role'] ?? null,
            'user_id' => $user->id ?? null,
            'school_id' => $schoolId ?? null,
            'gender' => $data['gender'] ?? null,
            'age' => $data['age'] ?? null,
            'dob' => !empty($data['dob']) && \Carbon\Carbon::hasFormat($data['dob'], 'd/m/Y')
                ? Carbon::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d')
                : null,
            'address' => $data['address'] ?? null,
            'city' => $cityId ?? null,
            'state' => $stateId ?? null,
            // 'country' => strtolower($data['country']),
            'qualification' => $data['qualification'] ?? null,
            'assigned_classes' => implode(',', $classIds) ?? null,
            'assigned_subjects' => implode(',', $subjectIds) ?? null,
            'experience' => $data['experience'] ?? null,
        ]);
    }
    private function validateDefaultUser(array $data, string $rowKey)
    {
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'email', 'email')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['email' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'mobile_no', 'mobile number')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mobile_no' => $duplicateErrors[$rowKey]]
                ]));
            }
        }
        $validator = Validator::make($data, [
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,role_slug',
            'mobile_no' => 'required|numeric|unique:users,mobile_no',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate role-specific fields
        if ($data['role'] === 'distributors' || $data['role'] === 'salesman') {
            if (empty($data['employeid_or_distributorid'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['employeid_or_distributorid' => ['ID is required for this role']]
                ]));
            }
        }
    }

    private function saveDefaultUser(array $data, string $rowKey)
    {
        $user = User::create([
            'name' => $data['name'],
            'mobile_no' => $data['mobile_no'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']) ?? Hash::make('Mitt@123'),
            'validate_string' => $data['password'] ?? 'Mitt@123',
            'created_by' => Auth::id(),
            'is_email_verified' => 1,
            'is_mobile_verified' => 1,
        ]);

        if (!$user) {
            throw new \Exception("Error creating user for row: {$rowKey}");
        }

        $userRole = UserRole::create([
            'user_id' => $user->id,
            'role_slug' => $data['role']
        ]);

        if (!$userRole) {
            throw new \Exception("Error creating user role for row: {$rowKey}");
        }

        $additionalData = [
            'user_id' => $user->id,
            'role_slug' => $data['role']
        ];

        // Add role-specific fields
        if ($data['role'] === 'distributors') {
            $additionalData['distributor_id'] = $data['employeid_or_distributorid'];
        } elseif ($data['role'] === 'salesman') {
            $additionalData['employee_id'] = $data['employeid_or_distributorid'];
        }

        $userAdditionalDetails = UserAdditionalDetail::create($additionalData);

        if (!$userAdditionalDetails) {
            throw new \Exception("Error creating additional details for row: {$rowKey}");
        }

        return "User data successfully saved for row: {$rowKey}";
    }

    private function validateSchoolAdmin(array $data, string $rowKey)
    {
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'email', 'email')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['email' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'mobile_no', 'mobile number')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mobile_no' => $duplicateErrors[$rowKey]]
                ]));
            }
        }
        $validator = Validator::make($data, [
            'school_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,role_slug',
            'district' => 'required|max:255',
            'password' => 'required|min:8',
            'school_type' => 'required',
            'academic_session' => 'required',
            'state' => 'required|max:255',
            'pin_code' => 'required|max:10',
            'class' => 'required|string',
            'school_board' => 'required|max:255',
            'school_medium' => 'required|max:255',
            'assign_to' => 'required|max:255',
            'decision_maker_mobile_no' => 'required|numeric|unique:users,mobile_no',
            'school_affiliation_number_or_pan_number' => 'required|max:255',
            'school_registration_number' => 'required|max:255',
            'incorporation_date' => 'nullable|date_format:d/m/Y',
            'assign_distributor' => 'required|max:255',
            'customer_type' => 'required',
        ], [
            'incorporation_date.date_format' => 'The incorporation date must be in the format dd/mm/yyyy.',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate state exists
        $state = State::where('name', $data['state'])->first();
        if (!$state) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['state' => ['State not found']]
            ]));
        }

        // Validate district exists
        if (!City::where('state_id', $state->id)->where('city', $data['district'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['district' => ['District not found']]
            ]));
        }

        // Validate academic session
        $academicSession = AcademicSession::where('start_date', 'like', '%' . explode('-', $data['academic_session'])[0] . '%')
            ->where('end_date', 'like', '%' . explode('-', $data['academic_session'])[1] . '%')
            ->first();
        if (!$academicSession) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['academic_session' => ['Academic Session not found']]
            ]));
        }

        // Validate school type
        $allowedTypes = config('constants.SCHOOL_TYPES');
        if (!in_array($data['school_type'], $allowedTypes)) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['school_type' => ["Choose only 'Individual', 'Group', or 'Demo'."]]
            ]));
        }

        $allowTypes = config('constants.CUSTOMER_TYPE');
        if (!in_array($data['customer_type'], $allowTypes)) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['customer_type' => ["Choose only 'Yes', 'No'."]]
            ]));
        }

        // Validate group school specific fields
        if ($data['school_type'] === 'Group') {
            if (empty($data['school_role'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['school_role' => ['School role is required for Group schools']]
                ]));
            }

            $allowedRoles = config('constants.SCHOOL_ROLE');
            if (!in_array($data['school_role'], $allowedRoles)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['school_role' => ["Choose only 'Parent/HO' or 'Branch'."]]
                ]));
            }

            if ($data['school_role'] === 'Branch' && empty($data['parent_school_name'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['parent_school_name' => ['Parent school is required for Branch schools']]
                ]));
            }
        }

        // Validate classes
        $classes = explode(',', $data['class']);
        foreach ($classes as $class) {
            if (!Classes::where('name', trim($class))->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['class' => ["Class: {$class} not found"]]
                ]));
            }
        }

        // Validate school board
        if (!Board::where('name', $data['school_board'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['school_board' => ['Board not found']]
            ]));
        }
        if (!Grade::where('name', $data['grade'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['grade' => ['Grade not found']]
            ]));
        }

        // Validate school medium
        if (!Medium::where('name', $data['school_medium'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['school_medium' => ['Medium not found']]
            ]));
        }

        // Validate assign_to user
        $assignToUser = User::where('name', $data['assign_to'])->first();
        if (!$assignToUser || getUserRoles($assignToUser->id) !== 'salesman') {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['assign_to' => ['Salesman not found! Please enter a valid salesman.']]
            ]));
        }

        // Validate distributor
        $distributor = User::where('name', $data['assign_distributor'])
            ->whereHas('userRole', fn($q) => $q->where('role_slug', 'distributors'))
            ->first();
        if (!$distributor) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['assign_distributor' => ['Distributor not found']]
            ]));
        }
    }

    private function saveSchoolAdmin(array $data, string $rowKey)
    {
        // dd($data);
        $state = State::where('name', $data['state'])->first();
        $stateCode = strtoupper(substr($state->name, 0, 2));
        do {
            $uniqueId = $stateCode . mt_rand(1000, 9999);
        } while (Schools::where('unique_id', $uniqueId)->exists());

        // Create user
        $user = User::create([
            'name' => $data['school_name'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'mobile_no'          => $data['decision_maker_mobile_no'] ?? null,
            'password' => Hash::make($data['password']) ?? Hash::make('Mitt@123'),
            'validate_string' => $data['password'] ?? 'Mitt@123',
            'created_by' => Auth::id(),
            'is_email_verified' => 1,
            'is_mobile_verified' => 1,
        ]);
        // Assign role
        UserRole::create([
            'user_id' => $user->id,
            'role_slug' => $data['role']
        ]);

        // Prepare school data
        $districtId = City::where('state_id', $state->id)
            ->where('city', $data['district'])
            ->value('id');


        $academicSession = AcademicSession::where('start_date', 'like', '%' . explode('-', $data['academic_session'])[0] . '%')
            ->where('end_date', 'like', '%' . explode('-', $data['academic_session'])[1] . '%')
            ->first();

        $schoolData = [
            'user_id' => $user->id,
            'name' => $data['school_name'],
            'address' => $data['address_line_1'] ?? null,
            'school_type' => array_search($data['school_type'], config('constants.SCHOOL_TYPES')),
            'city' => $districtId,
            'state' => $state->id,
            'unique_id' => $uniqueId,
            'contact_email' => $data['email'],
            'postal_code' => $data['pin_code'],
            'academic_session_id' => $academicSession->id,
            'is_verified_by_admin' => 1,
            'is_varified_by' => Auth::id(),
        ];

        // Handle group school specific fields
        if ($data['school_type'] === 'Group') {
            $schoolData['school_role'] = array_search($data['school_role'], config('constants.SCHOOL_ROLE'));

            if ($data['school_role'] === 'Branch') {
                $parentSchoolId = Schools::where('name', $data['parent_school_name'])
                    ->where('school_role', 'parent')
                    ->value('user_id');
            }
        }

        // Create school
        $school = Schools::create($schoolData);

        // Assign classes
        $classes = explode(',', $data['class']);
        foreach ($classes as $class) {
            $classId = Classes::where('name', trim($class))->value('id');
            SchoolAssignedClass::create([
                'school_id' => $user->id,
                'class_id' => $classId,
            ]);
        }

        // Prepare additional details
        $assignToUser = User::where('name', $data['assign_to'])->first();
        $distributor = User::where('name', $data['assign_distributor'])
            ->whereHas('userRole', fn($q) => $q->where('role_slug', 'distributors'))
            ->first();

        $additionalDetails = [
            'role' => $data['role'],
            'user_id' => $user->id,
            'school_id' => $user->id,
            'school_board' => Board::where('name', $data['school_board'])->value('id'),
            'school_medium' => Medium::where('name', $data['school_medium'])->value('id'),
            'grade' => Grade::where('name', $data['grade'])->value('id'),
            'assign_to' => $assignToUser->id,
            'city' => $districtId,
            'state' => $state->id,
            'school_affiliation_no' => $data['school_affiliation_number_or_pan_number'],
            'school_registration_no' => $data['school_registration_number'],
            'assign_distributor' => $distributor->id,
            'bank_name' => $data['bank_name'],
            'acc_holder_name' => $data['bank_account_holder_name'],
            'branch_name' => $data['branch_name'],
            'acc_no' => $data['bank_account_number'],
            'ifsc_code' => $data['ifsc_code'],
            'address' => $data['address_line_2'] ?? null,
            'parent_school_name' => $parentSchoolId ?? null,
            'customer_type' => strtolower($data['customer_type']) ?? null,
        ];

        // Add optional fields
        $optionalFields = [
            'lead',
            'website',
            'decision_maker',
            'decision_maker_mobile_no',
            'decision_maker_role',
            'strength',
            'incorporation_date',
            'gst_no',
            'landmark',
        ];

        foreach ($optionalFields as $field) {
            if (!empty($data[$field])) {
                if ($field === 'incorporation_date') {
                    $additionalDetails[$field] = Carbon::createFromFormat('d/m/Y', $data[$field])->format('Y-m-d');
                } elseif ($field === 'decision_maker_role') {
                    $additionalDetails[$field] = Role::where('role_name', $data[$field])->value('role_slug');
                } elseif ($field === 'on_board_for_erp') {
                    $additionalDetails['board_erp'] = $data[$field] === 'yes' ? 1 : 0;
                } else {
                    $additionalDetails[$field] = $data[$field];
                }
            }
        }

        // Create additional details
        UserAdditionalDetail::create($additionalDetails);
    }

    private function validateStudent(array $data, string $rowKey)
    {
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'email', 'email')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['email' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'mobile_no', 'mobile number')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mobile_no' => $duplicateErrors[$rowKey]]
                ]));
            }
        }
        $validator = Validator::make($data, [
            'student_name' => 'required|max:255',
            'parent_or_guardian_mobile_number' => 'required|numeric|unique:users,mobile_no',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,role_slug',
            'admission_date' => 'nullable|date_format:d/m/Y',
            'date_of_birth' => 'nullable|date_format:d/m/Y',
            'class' => 'required|exists:classes,name',
            'password' => 'required|min:8',
        ], [
            'admission_date.date_format' => 'The admission date must be in the format dd/mm/yyyy.',
            'date_of_birth.date_format' => 'The date of birth must be in the format dd/mm/yyyy.',
        ]);


        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate class exists


        // Validate school exists if provided
        if (!Classes::where('name', $data['class'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['class' => ['Class not found']]
            ]));
        }

        // Validate school exists and has the class assigned
        if (!empty($data['school_name'])) {
            $school = Schools::where('name', $data['school_name'])->first();

            if (!$school) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['school_name' => ['School not found']]
                ]));
            }

            // Get school's assigned classes
            $schoolClasses = SchoolAssignedClass::where('school_id', $school->user_id)
                ->pluck('class_id')
                ->toArray();

            // Get requested class ID
            $classId = Classes::where('name', $data['class'])->value('id');

            if (!in_array($classId, $schoolClasses)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['class' => ['Class not assigned to this school']]
                ]));
            }
        }
        if (!empty($data['section'])) {
            if (!Section::where('section_name', $data['section'])->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['section' => ['Section not found']]
                ]));
            }
        }
        // // Validate admission number uniqueness
        // if (UserAdditionalDetail::where('admission_no', $data['admission_no_or_sr_no'])->exists()) {
        //     throw new \Exception(json_encode([
        //         'row' => $rowKey,
        //         'errors' => ['admission_no_or_sr_no' => ['Admission number already exists']]
        //     ]));
        // }
    }


    private function saveStudent(array $data, string $rowKey)
    {

        $user = User::create([
            'name' => $data['student_name'] ?? null,
            'email' => $data['email'] ?? null,
            'mobile_no' => $data['parent_or_guardian_mobile_number'] ?? null,
            'created_by' => Auth::id(),
            'password' => Hash::make($data['password']) ?? Hash::make('Mitt@123'),
            'validate_string' => $data['password'] ?? 'Mitt@123',
            'is_email_verified' => 1,
            'is_mobile_verified' => 1,
        ]);

        // Assign role
        UserRole::create([
            'user_id' => $user->id,
            'role_slug' => $data['role']
        ]);

        $schoolId = null;
        $classId = Classes::where('name', $data['class'])->value('id');
        $sectionId = Section::where('section_name', $data['section'])->value('id');

        if (!empty($data['school_name'])) {
            $school = Schools::where('name', $data['school_name'])->first();
            $schoolId = $school->user_id;
        }


        // Create student details
        StudentDetails::create([
            'user_id' => $user->id ?? null,
            'school_id' => $schoolId ?? null,
            'parent_id' => Auth::id(),
            'parent_name' => $data['parent_name'] ?? null,
            'doj' => (!empty($data['admission_date']) && \Carbon\Carbon::hasFormat($data['admission_date'], 'd/m/Y'))
                ? Carbon::createFromFormat('d/m/Y', $data['admission_date'])->format('Y-m-d')
                : null,

            'dob' => (!empty($data['date_of_birth']) && \Carbon\Carbon::hasFormat($data['date_of_birth'], 'd/m/Y'))
                ? Carbon::createFromFormat('d/m/Y', $data['date_of_birth'])->format('Y-m-d')
                : null,

            'class' => $classId ?? null,
            'section' => $sectionId ?? null,
        ]);

        // Create additional details
        UserAdditionalDetail::create([
            'role' => $data['role'] ?? null,
            'user_id' => $user->id ?? null,
            'school_id' => $schoolId ?? null,
            'admission_no' => $data['admission_no_or_sr_no'] ?? null
        ]);
    }
    private function validateD2cStudent(array $data, string $rowKey)
    {
        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'email', 'email')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['email' => $duplicateErrors[$rowKey]]
                ]));
            }
        }

        if ($duplicateErrors = $this->checkForDuplicateValues($this->uploadedData, 'mobile_no', 'mobile number')) {
            if (isset($duplicateErrors[$rowKey])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['mobile_no' => $duplicateErrors[$rowKey]]
                ]));
            }
        }
        // dd($data);
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'mobile_number' => 'required|numeric|unique:users,mobile_no',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,role_slug',
            'class' => 'required|exists:classes,name',
            'category' => 'required',
        ]);


        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        if (!empty($data['school_state'])) {
            $state = State::where('name', $data['school_state'])->first();
            if (!$state) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['school_state' => ['School State not found']]
                ]));
            }
        }

        if (!empty($data['school_district'])) {
            if (!City::where('state_id', $state->id)->where('city', $data['school_district'])->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['school_district' => ['School District not found']]
                ]));
            }
        }

        // Validate school exists if provided
        if (!Classes::where('name', $data['class'])->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['class' => ['Class not found']]
            ]));
        }


        $fieldsToCheck = [
            'science',
            'study_material_required_sc',
            'mathematics',
            'study_material_required_math'
        ];

        foreach ($fieldsToCheck as $field) {
            if (!isset($data[$field])) {
                continue; // ignore if not present
            }

            $value = strtolower(trim($data[$field]));

            if ($value === '') {
                continue; // ignore if blank string
            }

            if (!in_array($value, ['yes', 'no'])) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => [$field => ["$field must be either 'yes' or 'no'"]],
                ]));
            }
        }


        // Validate category exists and class
        if (!empty($data['category'])) {
            $category = Category::where('name', $data['category'])->first();

            if (!$category) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['category' => ['Category not found']]
                ]));
            }

            $classSubjectsRaw = BookSeries::where('slug', $category->slug)
                ->pluck('class_subjects')
                ->first();
            $classSubjects = json_decode($classSubjectsRaw, true);

            $classIds = collect($classSubjects)
                ->pluck('class_id')
                ->unique()
                ->values()
                ->all();

            $classes = SchoolClass::whereIn('id', $classIds)
                ->pluck('id') // returns [id => name]
                ->toArray();

            // Get requested class ID
            $classId = Classes::where('name', $data['class'])->value('id');

            if (!in_array($classId, $classes)) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['class' => ['Class is Invalid']]
                ]));
            }
        }


        if (!empty($data['section'])) {
            if (!Section::where('section_name', $data['section'])->exists()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['section' => ['Section not found']]
                ]));
            }
        }
        // // Validate admission number uniqueness
        // if (UserAdditionalDetail::where('admission_no', $data['admission_no_or_sr_no'])->exists()) {
        //     throw new \Exception(json_encode([
        //         'row' => $rowKey,
        //         'errors' => ['admission_no_or_sr_no' => ['Admission number already exists']]
        //     ]));
        // }
    }
    private function saveD2cUser(array $data, string $rowKey)
    {
        $studyMaterialScience = strtolower(trim($data['study_material_required_sc'] ?? ''));
        $studyMaterialMath    = strtolower(trim($data['study_material_required_math'] ?? ''));

        $loginAllowed = ($studyMaterialScience === 'yes' || $studyMaterialMath === 'yes') ? 1 : 0;
        // Only hash password if login is allowed
        $password = $loginAllowed ? Hash::make($data['password'] ?? 'Mitt@123') : null;
        $plainPassword = $loginAllowed ? $data['password'] ?? 'Mitt@123' : null;

        $user = User::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'mobile_no' => $data['mobile_number'] ?? null,
            'created_by' => Auth::id(),
            'password' => $password,
            'validate_string' => $plainPassword,
            'is_email_verified' => 1,
            'is_mobile_verified' => 1,
            'can_login' => $loginAllowed,
        ]);

        // Assign role
        UserRole::create([
            'user_id' => $user->id,
            'role_slug' => $data['role']
        ]);

        $schoolId = null;
        $categoryId = null;
        $classId = Classes::where('name', $data['class'])->value('id');
        $sectionId = Section::where('section_name', $data['section'])->value('id');
        if (!empty($data['school_name'])) {
            $schoolSelected = Schools::where('name', $data['school_name'])->first();

            if ($schoolSelected) {
                $school  = $schoolSelected->name;
            } else {
                $school = $data['school_name'];
            }
        }
        if (!empty($data['category'])) {
            $category = Category::where('name', $data['category'])->first();
            $categoryId = $category->id;
        }

        $userClassData = [
            'user_id' => $user->id,
            'class_id' => $classId,
            'category_id' => $categoryId,
            'user_role' => $data['role'],
        ];
        $option_a = [];
        $option_b = [];

        // Normalize inputs
        $studyMaterialScience = strtolower($data['study_material_required_sc'] ?? '');
        $studyMaterialMath    = strtolower($data['study_material_required_math'] ?? '');
        $science              = strtolower($data['science'] ?? '');
        $math                 = strtolower($data['mathematics'] ?? '');

        // Science: value is always true/false → placed in A or B based on study_material_required_sc
        if ($studyMaterialScience === 'yes') {
            $option_a['science'] = $science === 'yes' ? true : false;
        } elseif ($studyMaterialScience === 'no') {
            $option_b['science'] = $science === 'yes' ? true : false;
        }
        // Mathematics: value is always true/false → placed in A or B based on study_material_required_math
        if ($studyMaterialMath === 'yes') {
            $option_a['mathematics'] = $math === 'yes' ? true : false;
        } elseif ($studyMaterialMath === 'no') {
            $option_b['mathematics'] = $math === 'yes' ? true : false;
        }
        if (!empty($data['school_state'])) {
            $state = State::where('name', $data['school_state'])->value('id');
            if (!empty($data['school_district'])) {
                $districtId = City::where('state_id', $state)
                    ->where('city', $data['school_district'])
                    ->value('id');
            }
        }
        UserClass::create($userClassData);

        // Create student details
        StudentDetails::create([
            'user_id' => $user->id ?? null,
            'school_id' => null,
            'parent_id' => Auth::id(),
            'parent_name' => $data['parent_/_guardian_name'] ?? null,
            'class' => $classId ?? null,
            'section' => $sectionId ?? null,
            'roll_number' =>  $data['roll_no.'] ?? null,
            'd2c_user_school_name' => $school ?? null,
            'school_pincode' => $data['school_pin_code'] ?? null,
            'school_state' => $state ?? null,
            'school_district' => $districtId ?? null,
            'school_address_1' => $data['school_address'] ?? null,
            'option_a' => !empty($option_a) ? json_encode($option_a) : null,
            'option_b' => !empty($option_b) ? json_encode($option_b) : null,
        ]);

        // Create additional details
        UserAdditionalDetail::create([
            'role' => $data['role'] ?? null,
            'user_id' => $user->id ?? null,
            'school_id' => $schoolId ?? null,
        ]);
    }

    public function closeModal()
    {
        $this->resetImportState();

        $this->isLoading = false;
        $this->file = null;
    }
    private function resetImportState()
    {
        $this->rowErrors = [];
        $this->uploadedData = [];
        $this->selectedData = [];
        $this->headers = [];
        $this->isModalOpen = false;
    }
    public function render()
    {
        return view('livewire.user-bulk-upload');
    }
}
