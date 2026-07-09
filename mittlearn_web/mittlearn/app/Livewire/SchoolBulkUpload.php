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
use App\Models\Board;
use App\Models\City;
use App\Models\Classes;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\Schools;
use App\Models\Section;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SchoolBulkUpload extends Component
{
    use WithFileUploads;

    public $roles;
    public $roleName;
    public $file;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = [];

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

            // Second pass: Save all rows (only reaches here if all validation passed)
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->saveDataBasedOnRole($convertedRow, $rowKey);
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                $this->isModalOpen = false;
                return redirect()->to(request()->header('Referer'))->with('success', "{$successCount} row(s) successfully saved!");
                $this->file = null;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleImportError($e);
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
        // dd($e);

        $errorData = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($errorData['row'])) {
            $this->rowErrors[$errorData['row']] = $errorData['errors'];
            session()->flash('errorMsg', 'Some rows have errors. No data was saved. Please review.');
        } else {
            session()->flash('errorMsg', 'Error during import: ' . $e->getMessage());
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
    /**
     * Save data based on the role type.
     */
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
            case 'school_teacher':
                $this->validateTeacher($data, $rowKey);
                break;
            case 'school_student':
                $this->validateStudent($data, $rowKey);
                break;
            default:
                break;
        }
    }
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
            case 'school_teacher':
                $this->saveTeacher($data, $rowKey);
                break;

            case 'school_student':
                $this->saveStudent($data, $rowKey);
                break;
            default:
                break;
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
        if (!empty($data['school_name'])) {
            $school = Schools::where('name', $data['school_name'])->first();
            // dd($school);

            if (!$school || $school->user_id != Auth::id()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => [
                        'school_name' => ['School not found OR Enter Your valid school name']
                    ]
                ]));
            }
        }
        // // Validate school exists
        // if (!Schools::where('name', $data['school_name'])->exists()) {
        //     throw new \Exception(json_encode([
        //         'row' => $rowKey,
        //         'errors' => ['school_name' => ['School not found']]
        //     ]));
        // }

        // Validate state exists
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
        $validator = Validator::make(
            $data,
            [
                'student_name' => 'required|max:255',
                'parent_or_guardian_mobile_number' => 'required|numeric|unique:users,mobile_no',
                'email' => 'nullable|email|unique:users,email',
                'role' => 'required|exists:roles,role_slug',
                'admission_date' => 'nullable|date_format:d/m/Y',
                'date_of_birth' => 'nullable|date_format:d/m/Y',
                'class' => 'required|exists:classes,name',
                'password' => 'required|min:8',
            ],
            [
                'admission_date.date_format' => 'The admission date must be in the format dd/mm/yyyy.',
                'date_of_birth.date_format' => 'The date of birth must be in the format dd/mm/yyyy.',
            ]
        );

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

            if (!$school || $school->user_id != Auth::id()) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => [
                        'school_name' => ['School not found OR Enter Your Valid school name']
                    ]
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

    public function closeModal()
    {
        $this->resetImportState(); // Clear all state when closing modal
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
        return view('livewire.school-bulk-upload');
    }
}
