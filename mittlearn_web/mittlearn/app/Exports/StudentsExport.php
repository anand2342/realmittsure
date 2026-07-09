<?php

namespace App\Exports;

use App\Models\SchoolClass;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $role;
    protected $parentId;
    protected $teacherAssignedClasses;

    public function __construct($role, $parentId, $teacherAssignedClasses = [])
    {
        $this->role = $role;
        $this->parentId = $parentId;
        $this->teacherAssignedClasses = $teacherAssignedClasses;
    }

    public function collection()
    {
        $users = User::with(['userAdditionalDetail', 'studentDetails'])
            ->whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_student')
                    ->where('school_id', $this->parentId);
            })
            ->when($this->role === 'school_teacher', function ($query) {
                $query->whereHas('studentDetails', function ($subQuery) {
                    $subQuery->whereIn('class', $this->teacherAssignedClasses);
                });
            })
            ->get();

        return $users->map(function ($user, $index) {
            return [
                'ID' => $index + 1,  // Custom index
                'Name' => $user->name,
                'Admission No' => $user->userAdditionalDetail->admission_no ?? 'N/A',
                'Admission Date' => $user->studentDetails->doj
                    ? \Carbon\Carbon::parse($user->studentDetails->doj)->format('Y-m-d')
                    : 'N/A',
                'Parent Mobile No.' => $user->mobile_no ?? 'N/A',
                'Class' => $user->studentDetails->class
                    ? SchoolClass::find($user->studentDetails->class)->name ?? 'N/A'
                    : 'N/A',
                'Date Of Birth' => $user->studentDetails->dob ?? 'N/A',
                'Section' => $user->studentDetails->section ?? 'N/A',
                'Status' => ($user->status === 1 ? 'Active' : ($user->status === 0 ? 'Inactive' : 'N/A')),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Admission No',
            'Admission Date',
            'Parent Mobile No.',
            'Class',
            'Date Of Birth',
            'Section',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Row 1 (header row)
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'FFFF00', // Yellow background color
                    ],
                ],
            ],
        ];
    }
}
