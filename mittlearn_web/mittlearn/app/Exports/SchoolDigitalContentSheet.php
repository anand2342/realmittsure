<?php

namespace App\Exports;

use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\Subject;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolDigitalContentSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function title(): string
    {
        return 'Digital Content';
    }

    public function collection()
    {
        // School admins (to map school_id -> name/unique_id)
        $schools = User::query()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_slug', '=', 'roles.role_slug')
            ->leftJoin('schools', 'users.id', '=', 'schools.user_id')
            ->where('roles.role_slug', 'school_admin')
            ->where('schools.is_verified_by_admin', 1)
            ->where('users.status', 1)
            ->select([
                'users.id as user_id',
                'users.name as school_name',
                'schools.unique_id as unique_id',
            ])
            ->get()
            ->keyBy('user_id');

        $schoolIds = $schools->keys();

        // All assigned digital content rows for these schools
        $assignments = SchoolAssignedDigitalContent::whereIn('school_id', $schoolIds)
            ->orderBy('school_id')
            ->orderBy('class_id')
            ->get();

        // Lookups
        $classIds = $assignments->pluck('class_id')->unique();
        $seriesIds = $assignments->pluck('series_id')->unique();

        $classes = Classes::whereIn('id', $classIds)->get()->keyBy('id');
        $bookSeries = BookSeries::whereIn('id', $seriesIds)->get()->keyBy('id');

        // Collect all subject IDs from comma-separated subject_id strings
        $allSubjectIds = collect();
        foreach ($assignments as $assignment) {
            if (!empty($assignment->subject_id)) {
                $ids = array_filter(explode(',', $assignment->subject_id));
                $allSubjectIds = $allSubjectIds->merge($ids);
            }
        }
        $subjects = Subject::whereIn('id', $allSubjectIds->unique())->get()->keyBy('id');

        $rows = collect();
        $serial = 1;

        foreach ($assignments as $assignment) {
            $school = $schools[$assignment->school_id] ?? null;
            $className = $classes[$assignment->class_id]->name ?? 'N/A';
            $seriesName = $bookSeries[$assignment->series_id]->name ?? 'N/A';

            $subjectNames = 'N/A';
            if (!empty($assignment->subject_id)) {
                $subjectIds = array_filter(explode(',', $assignment->subject_id));
                $subjectNames = collect($subjectIds)
                    ->map(function ($id) use ($subjects) {
                        return $subjects[$id]->name ?? 'N/A';
                    })
                    ->implode(', ');
            }

            $rows->push([
                'S.No' => $serial++,
                'School Unique ID' => $school->unique_id ?? 'N/A',
                'School Name' => $school->school_name ?? 'N/A',
                'Class' => $className,
                'Book Series' => $seriesName,
                'Subjects' => $subjectNames,
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'School Unique ID',
            'School Name',
            'Class',
            'Book Series',
            'Subjects',
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
            'A:F' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
        ];
    }
}
