<?php

namespace App\Exports;

use App\Models\City;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\State;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeachersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        $users = User::with(['userAdditionalDetail', 'studentDetails'])
            ->whereHas('userAdditionalDetail', function ($query) {
                $query->where('role', 'school_teacher')
                    ->where('school_id', auth()->id());
            })
            ->get();

        return $users->map(function ($user, $index) {
            // Fetch readable class names
            $classIds = $user->userAdditionalDetail->assigned_classes ?? '';
            $classNames = 'N/A';
            if ($classIds) {
                $classIdsArray = explode(',', $classIds);
                $classNames = SchoolClass::whereIn('id', $classIdsArray)
                    ->pluck('name')
                    ->implode(', ');
            }

            // Fetch readable subject names
            $subjectIds = $user->userAdditionalDetail->assigned_subjects ?? '';
            $subjectNames = 'N/A';
            if ($subjectIds) {
                $subjectIdsArray = explode(',', $subjectIds);
                $subjectNames = Subject::whereIn('id', $subjectIdsArray)
                    ->pluck('name')
                    ->implode(', ');
            }
            $cityId = $user->userAdditionalDetail->city ?? '';
            $cityName = 'N/A';
            if ($cityId) {
                $cityName = City::where('id', $cityId)->value('city');
            }
            $stateId = $user->userAdditionalDetail->state ?? '';
            $stateName = 'N/A';
            if ($stateId) {
                $stateName = State::where('id', $stateId)->value('name');
            }

            return [
                'ID' => $index + 1,
                'Name' => $user->name ?? 'N/A',
                'Gender' => $user->userAdditionalDetail->gender ?? 'N/A',
                'Date Of Birth' => $user->userAdditionalDetail->dob ?? 'N/A',
                'Age' => $user->userAdditionalDetail->gender ?? 'N/A',
                'Country' => $user->userAdditionalDetail->country ?? 'N/A',
                'State' => $stateName,
                'City' => $cityName,
                'Email' => $user->email ?? 'N/A',
                'Mobile' => $user->mobile_no ?? 'N/A',
                'Status' => ($user->status === 1 ? 'Active' : ($user->status === 0 ? 'Inactive' : 'N/A')),
                'Address' => $user->userAdditionalDetail->address ?? 'N/A',
                'Qualification' => $user->userAdditionalDetail->qualification ?? 'N/A',
                'Classes' => $classNames ?? 'N/A',
                'Subjects' => $subjectNames ?? 'N/A',
                'Hire Date' => $user->userAdditionalDetail->created_at
                    ? \Carbon\Carbon::parse($user->userAdditionalDetail->created_at)->format('Y-m-d')
                    : 'N/A',
                'Experience' => $user->userAdditionalDetail->experience ?? 'N/A',
            ];
        });
    }



    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Gender',
            'Date Of Birth',
            'Age',
            'Country',
            'State',
            'City',
            'Email',
            'Mobile',
            'Status',
            'Address',
            'Qualification',
            'CLasses',
            'Subjects',
            'Hire Date',
            'Experience',
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
