<?php

namespace App\Exports;

use App\Models\AcademicSession;
use App\Models\Board;
use App\Models\City;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\State;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllSchoolExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // First get all the main data
        $query = User::query()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
            ->leftJoin('schools', 'users.id', '=', 'schools.user_id')
            ->leftJoin('user_additional_details', 'users.id', '=', 'user_additional_details.user_id')
            ->where('roles.role_slug', 'school_admin')
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
                'schools.state as state_id',
                'schools.city as city_id',
                'schools.postal_code as postal_code',
                'schools.address as address1',
                'user_additional_details.address as address2',
                'schools.academic_session_id as academic_session_id',
                'schools.batch_id as batch_id',
                'user_additional_details.assign_to as assign_to_id',
                'user_additional_details.website as website',
                'user_additional_details.decision_maker as decision_maker',
                'user_additional_details.decision_maker_mobile_no as decision_maker_mobile_no',
                'user_additional_details.decision_maker_role as decision_maker_role_id',
                'user_additional_details.school_board as school_board_id',
                'user_additional_details.school_medium as school_medium_id',
                'user_additional_details.strength as strength',
                'user_additional_details.grade as grade_id',
                'user_additional_details.school_affiliation_no as school_affiliation_no',
                'user_additional_details.school_registration_no as school_registration_no',
                'user_additional_details.incorporation_date as incorporation_date',
                'user_additional_details.assign_distributor as assign_distributor_id',
                'user_additional_details.gst_no as gst_no',
                'user_additional_details.bank_name as bank_name',
                'user_additional_details.acc_holder_name as acc_holder_name',
                'user_additional_details.branch_name as branch_name',
                'user_additional_details.acc_no as acc_no',
                'user_additional_details.ifsc_code as ifsc_code',
                'users.created_at',
            ]);

        $data = $query->get();

        // Collect all IDs needed for related data
        $stateIds = $data->pluck('state_id')->filter()->unique()->values();
        $cityIds = $data->pluck('city_id')->filter()->unique()->values();
        $academicSessionIds = $data->pluck('academic_session_id')->filter()->unique()->values();
        $batchIds = $data->pluck('batch_id')->filter()->unique()->values();
        $assignToIds = $data->pluck('assign_to_id')->filter()->unique()->values();
        $decisionMakerRoleIds = $data->pluck('decision_maker_role_id')->filter()->unique()->values();
        $boardIds = $data->pluck('school_board_id')->filter()->unique()->values();
        $mediumIds = $data->pluck('school_medium_id')->filter()->unique()->values();
        $gradeIds = $data->pluck('grade_id')->filter()->unique()->values();
        $assignDistributorIds = $data->pluck('assign_distributor_id')->filter()->unique()->values();
        $schoolIds = $data->pluck('user_id')->filter()->unique()->values();

        // Load all related data in bulk
        $states = State::whereIn('id', $stateIds)->get()->keyBy('id');
        $cities = City::whereIn('id', $cityIds)->get()->keyBy('id');
        $academicSessions = AcademicSession::whereIn('id', $academicSessionIds)->get()->keyBy('id');
        $batches = AcademicSession::whereIn('id', $batchIds)->get()->keyBy('id');
        $assignToUsers = User::whereIn('id', $assignToIds)->get()->keyBy('id');
        $decisionMakerRoles = Role::whereIn('id', $decisionMakerRoleIds)->get()->keyBy('id');
        $boards = Board::whereIn('id', $boardIds)->get()->keyBy('id');
        $mediums = Medium::whereIn('id', $mediumIds)->get()->keyBy('id');
        $grades = Grade::whereIn('id', $gradeIds)->get()->keyBy('id');
        $assignDistributors = User::whereIn('id', $assignDistributorIds)->get()->keyBy('id');

        // Preload class assignments for all schools
        $schoolClassAssignments = SchoolAssignedClass::whereIn('school_id', $schoolIds)
            ->get()
            ->groupBy('school_id');

        $classIds = $schoolClassAssignments->flatten()->pluck('class_id')->unique();
        $classes = Classes::whereIn('id', $classIds)->get()->keyBy('id');

        // Map the data with all related information
        return $data->map(function ($item, $index) use (
            $states,
            $cities,
            $academicSessions,
            $batches,
            $assignToUsers,
            $decisionMakerRoles,
            $boards,
            $mediums,
            $grades,
            $assignDistributors,
            $schoolClassAssignments,
            $classes
        ) {
            $status = $item->status === 1 ? 'Active' : 'Inactive';

            // Get class names for this school
            $classNames = null;
            if ($schoolClassAssignments->has($item->user_id)) {
                $classNames = $schoolClassAssignments[$item->user_id]
                    ->map(function ($assignment) use ($classes) {
                        return $classes[$assignment->class_id]->name ?? 'N/A';
                    })
                    ->filter()
                    ->implode(', ');
            }

            return [
                'S.No' => $index + 1,
                'Role' => $item->role ?? 'N/A',
                'Unique ID' => $item->unique_id ?? 'N/A',
                'Full Name' => $item->name ?? 'N/A',
                'School Role' => $item->school_role ?? 'N/A',
                'School Type' => $item->school_type ?? 'N/A',
                'Username' => $item->username ?? 'N/A',
                'Email' => $item->email ?? 'N/A',
                'Mobile No.' => $item->mobile_no ?? 'N/A',
                'Password' => $item->validate_string ?? 'N/A',
                'Status' => $status,
                'Academic Session' => $academicSessions[$item->academic_session_id]->name ?? 'N/A',
                'Batch Name' => $batches[$item->batch_id]->batch_name ?? 'N/A',
                'Address 1' => $item->address1 ?? 'N/A',
                'Address 2' => $item->address2 ?? 'N/A',
                'State' => $states[$item->state_id]->name ?? 'N/A',
                'District' => $cities[$item->city_id]->city ?? 'N/A',
                'Pin Code' => $item->postal_code ?? 'N/A',
                'Assign To' => $assignToUsers[$item->assign_to_id]->name ?? 'N/A',
                'Website' => $item->website ?? 'N/A',
                'Decision Maker' => $item->decision_maker ?? 'N/A',
                'Decision Maker Mobile No.' => $item->decision_maker_mobile_no ?? 'N/A',
                'Decision Maker Role' => $decisionMakerRoles[$item->decision_maker_role_id]->role_name ?? 'N/A',
                'Board' => $boards[$item->school_board_id]->name ?? 'N/A',
                'Medium' => $mediums[$item->school_medium_id]->name ?? 'N/A',
                'Strenght' => $item->strength ?? 'N/A',
                'Grade' => $grades[$item->grade_id]->name ?? 'N/A',
                'School Affiliation Number/PAN Number' => $item->school_affiliation_no ?? 'N/A',
                'School Registration Number' => $item->school_registration_no ?? 'N/A',
                'Incorporation Date' => $item->incorporation_date ?? 'N/A',
                'Assign Distributor' => $assignDistributors[$item->assign_distributor_id]->name ?? 'N/A',
                'GST No.' => $item->gst_no ?? 'N/A',
                'Classes' => $classNames,
                'Bank Name' => $item->bank_name ?? 'N/A',
                'Bank Account Holder Name' => $item->acc_holder_name ?? 'N/A',
                'Branch Name' => $item->branch_name ?? 'N/A',
                'Bank Account Number' => $item->acc_no ?? 'N/A',
                'IFSC Code' => $item->ifsc_code ?? 'N/A',
                'Created At' => $item->created_at ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Role',
            'Unique ID',
            'Full Name',
            'School Role',
            'School Type',
            'Username',
            'Email',
            'Mobile No.',
            'Password',
            'Status',
            'Academic Session',
            'Batch Name',
            'Address 1',
            'Address 2',
            'State',
            'District',
            'Pin Code',
            'Assign To',
            'Website',
            'Decision Maker',
            'Decision Maker Mobile No.',
            'Decision Maker Role',
            'Board',
            'Medium',
            'Strenght',
            'Grade',
            'School Affiliation Number/PAN Number',
            'School Registration Number',
            'Incorporation Date',
            'Assign Distributor',
            'GST No.',
            'Classes',
            'Bank Name',
            'Bank Account Holder Name',
            'Branch Name',
            'Bank Account Number',
            'IFSC Code',
            'Created At'
        ];
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
            'A:AK' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
        ];
    }
}
