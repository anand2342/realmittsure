<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\Schools;
use App\Models\StudentDetails;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class UsersImport implements ToModel, WithHeadingRow
{
    public $skippedEntries = [];
    private $errors = [];
    private $requiredColumnsUser = ['sl_no', 'role_name', 'student_name', 'mobile_no', 'password'];
    private $requiredColumnsTeacher =  ['sl_no', 'role_name', 'first_name', 'last_name', 'gender', 'age', 'email', 'mobile_no', 'address', 'city', 'state', 'country', 'qualification', 'classes_assigned', 'experience'];
    private $requiredColumnsStudent = [
        'sl_no',
        'role_name',
        'admission_no',
        'admission_date',
        'student_name',
        'class',
        'section',
        'guardian_mobile_number'
    ];
    private $requiredColumnsSchoolAdmin = [
        'sl_no',
        'role_name',
        'assign_to',
        'lead',
        'school_name',
        'parent_school_name',
        'email',
        'website',
        'decision_maker',
        'decision_maker_mobile_no',
        'decision_maker_role',
        'school_board',
        'school_medium',
        'grade',
        'school_affiliation_number',
        'school_registration_number',
        'incorporation_date',
        'assign_distributor',
        'pin_code',
        'state',
        'district',
        'address_line_1',
        'address_line_2',
        'landmark',
        'bank_name',
        'bank_account_holder_name',
        'branch_name',
        'bank_account_number',
        'ifsc_code'
    ];


    /**
     * Map the data from the row and check for duplicates.
     * If duplicate is found, skip the entry, otherwise save the user.
     *
     * @param array $row
     * @return User|null
     */
    public function getErrors()
    {
        return $this->errors;
    }
    private function validateEmailAndMobile($email, $mobile)
    {
        $emailPattern = '/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/';
        $mobilePattern = '/^\d{10}$/';
        $isEmailValid = preg_match($emailPattern, $email) === 1;
        $isMobileValid = preg_match($mobilePattern, $mobile) === 1;
        return [
            'email' => $isEmailValid,
            'mobile' => $isMobileValid,
        ];
    }

    public function model(array $row)
    {
        $role = Role::where('role_name', $row['role_name'])->first();

        switch ($role->role_slug) {

            case 'user':
                foreach ($this->requiredColumnsUser as $column) {
                    if (!array_key_exists($column, $row)) {
                        $this->errors[] = "The Excel file is missing the required column: '{$column}'. Please use the sample file's heading to organize your data and try uploading again.";
                        return null;
                    }
                }
                $validationResults = $this->validateEmailAndMobile($row['email_id'], $row['mobile_no']);
                if (!$validationResults['email']) {
                    $this->errors[] = "Line:{$row['sl_no']} Invalid email format. {$row['email_id']}";
                    return null;
                }
                if (!$validationResults['mobile']) {
                    $this->errors[] = "Line:{$row['sl_no']} Invalid mobile number format. {$row['mobile_no']}";
                    return null;
                }
                $userExists = User::where('email', $row['email_id'])
                    ->orWhere('mobile_no', $row['mobile_no'])
                    ->exists();

                if ($userExists) {
                    $this->errors[] = "Line:{$row['sl_no']} This entry is already exist {$row['email_id']} and {$row['mobile_no']}";
                    return null;
                }
                return User::create([
                    'name' => $row['student_name'],
                    'email' => $row['email_id'],
                    'mobile_no' => $row['mobile_no'],
                    'password' => Hash::make($row['password']),
                ]);
            case 'school_admin':
                foreach ($this->requiredColumnsSchoolAdmin as $column) {
                    if (!array_key_exists($column, $row)) {
                        $this->errors[] = "The Excel file is missing the required column: '{$column}'. Please use the sample file's heading to organize your data and try uploading again.";
                        return null;
                    }
                }

                $validationResults = $this->validateEmailAndMobile($row['email'], $row['decision_maker_mobile_no']);
                if (!$validationResults['email']) {
                    $this->errors[] = "Line:{$row['sl_no']} Invalid email format. {$row['email_id']}";
                    return null;
                }

                if (!$validationResults['mobile']) {
                    $this->errors[] =  "Line:{$row['sl_no']} Invalid mobile number format. {$row['mobile_no']}";
                }

                $userExists = User::where('email', $row['email'])->exists();

                if ($userExists) {
                    $this->errors[] = "Line:{$row['sl_no']} This entry is already exist {$row['email_id']} and {$row['mobile_no']}";
                    return null;
                }

                $user = User::updateOrCreate(

                    [
                        'name' => $row['school_name'],
                        'email' => $row['email'],
                    ]
                );

                Schools::updateOrCreate(
                    [
                        'name' => $row['school_name'],
                        'address' => $row['address_line_1'],
                        'city' => $row['district'],
                        'state' => $row['state'],
                        'postal_code' => $row['pin_code'],
                    ]
                );

                UserAdditionalDetail::updateOrCreate(
                    [
                        'role' => $role->role_name,
                        'user_id' => $user->id,
                        'assign_to' => $row['assign_to'],
                        'lead' => $row['lead'],
                        'school_name' => $row['school_name'],
                        'parent_school_name' => $row['parent_school_name'],
                        'city' => $row['district'], // City
                        'state' => $row['state'],
                        'website' => $row['website'],
                        'decision_maker' => $row['decision_maker'],
                        'decision_maker_mobile_no' => $row['decision_maker_mobile_no'],
                        'decision_maker_role' => $row['decision_maker_role'],
                        'school_board' => $row['school_board'],
                        'school_medium' => $row['school_medium'],
                        'strength' => $row['strength'] ?? null, // Strength is optional
                        'grade' => $row['grade'],
                        'school_affiliation_no' => $row['school_affiliation_number'],
                        'school_registration_no' => $row['school_registration_number'],
                        'incorporation_date' => $row['incorporation_date'],
                        'assign_distributor' => $row['assign_distributor'],
                        'gst_no' => $row['gst_no'] ?? null, // GST Number is optional
                        'board_erp' => $row['board_erp'] ?? null, // ERP system, optional
                        'address' => $row['address_line_2'],
                        'landmark' => $row['landmark'],
                        'bank_name' => $row['bank_name'],
                        'acc_holder_name' => $row['bank_account_holder_name'],
                        'branch_name' => $row['branch_name'],
                        'acc_no' => $row['bank_account_number'],
                        'ifsc_code' => $row['ifsc_code'],
                    ]
                );

                return $user;


            case 'teacher':
                foreach ($this->requiredColumnsTeacher as $column) {
                    if (!array_key_exists($column, $row)) {
                        $this->errors[] = "The Excel file is missing the required column: '{$column}'. Please use the sample file's heading to organize your data and try uploading again.";
                        return null;
                    }
                }

                $validationResults = $this->validateEmailAndMobile($row['email'], $row['mobile_no']);
                if (!$validationResults['email']) {
                    $this->errors[] = "Line:{$row['sl_no']} Invalid email format. {$row['email']}";
                    return null;
                }

                if (!$validationResults['mobile']) {
                    $this->errors[] =  "Line:{$row['sl_no']} Invalid mobile number format. {$row['mobile_no']}";
                    return null;
                }

                $userExists = User::where('email', $row['email'])
                    ->orWhere('mobile_no', $row['mobile_no'])
                    ->exists();

                if ($userExists) {
                    $this->errors[] = "Line:{$row['sl_no']} This entry is already exist {$row['email']} and {$row['mobile_no']}";
                    return null;
                }

                $user = User::create([
                    'name' => $row['first_name'] . ' ' . $row['last_name'],
                    'mobile_no' => $row['mobile_no'],
                    'email' => $row['email'],
                ]);

                UserAdditionalDetail::create([
                    'user_id' => $user->id,
                    'role' => $role->role_name,
                    'last_name' => $row['last_name'],
                    'gender' => $row['gender'],
                    'age' => $row['age'],
                    'address' => $row['address'],
                    'city' => $row['city'],
                    'state' => $row['state'],
                    'country' => $row['country'],
                    'qualification' => $row['qualification'],
                    'class_assigned' => $row['classes_assigned'],
                    'experience' => $row['experience'],
                ]);
                return $user;
            case 'student':
                foreach ($this->requiredColumnsStudent as $column) {
                    if (!array_key_exists($column, $row)) {
                        $this->errors[] = "The Excel file is missing the required column: '{$column}'. Please use the sample file's heading to organize your data and try uploading again.";
                        return null;
                    }
                }

                // Parse and format admission date
                try {
                    $admissionDate = Carbon::createFromFormat('d/m/Y', $row['admission_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->errors[] = "Line:{$row['sl_no']} Invalid date format for admission date. Please use the format DD/MM/YYYY.";
                    return null;
                }

                // Validate mobile number format
                $validationResults = $this->validateEmailAndMobile(null, $row['parent_or_guardian_mobile_number']);

                if (!$validationResults['mobile']) {
                    $this->errors[] = "Line:{$row['sl_no']} Invalid mobile number format: {$row['parent_or_guardian_mobile_number']}";
                    return null;
                }

                $userExists = User::where('mobile_no', $row['parent_or_guardian_mobile_number'])->exists();

                if ($userExists) {
                    $this->errors[] = "Line:{$row['sl_no']} This entry already exists with mobile number {$row['parent_or_guardian_mobile_number']}";
                    return null;
                }

                $user = User::updateOrCreate(
                    [
                        'name' => $row['student_name'],
                        'mobile_no' => $row['parent_or_guardian_mobile_number'],
                    ]
                );

                StudentDetails::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'doj' => $admissionDate, // Use formatted date here
                        'dob' => isset($row['date_of_birth']) ? Carbon::createFromFormat('d/m/Y', $row['date_of_birth'])->format('Y-m-d') : null,
                        'class' => $row['class'],
                        'section' => $row['section'],
                    ]
                );

                UserAdditionalDetail::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'role' => $row['role_name'],
                        'admission_no' => $row['admission_no_or_sr_no']
                    ]
                );

                return $user;
        }
    }
}
