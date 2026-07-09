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
use App\Models\SchoolAssignedDigitalContent;
use App\Models\State;
use App\Models\Subject;
use App\Models\BookSeries;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AllSchoolDetailedExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'Schools Report';
    }

    /**
     * Main data collection — all queries batched, zero N+1
     */
    public function collection(): Collection
    {
        // ── 1. Base query: schools + users + details ──────────────────────────
        $schools = User::query()
            ->join('user_roles',              'users.id',            '=', 'user_roles.user_id')
            ->join('roles',                   'user_roles.role_slug', '=', 'roles.role_slug')
            ->leftJoin('schools',             'users.id',            '=', 'schools.user_id')
            ->leftJoin('user_additional_details', 'users.id',        '=', 'user_additional_details.user_id')
            ->where('roles.role_slug', 'school_admin')
            ->select([
                'users.id                                       as user_id',
                'roles.role_name                                as role',
                'schools.unique_id                              as unique_id',
                'schools.school_type                            as school_type',
                'schools.school_role                            as school_role',
                'users.name                                     as name',
                'users.status                                   as status',
                'users.username                                 as username',
                'users.email                                    as email',
                'users.mobile_no                                as mobile_no',
                'users.validate_string                          as validate_string',
                'users.created_at                               as created_at',
                'schools.state                                  as state_id',
                'schools.city                                   as city_id',
                'schools.postal_code                            as postal_code',
                'schools.address                                as address1',
                'schools.academic_session_id                    as academic_session_id',
                'schools.batch_id                               as batch_id',
                'user_additional_details.address                as address2',
                'user_additional_details.assign_to              as assign_to_id',
                'user_additional_details.assign_distributor     as assign_distributor_id',
                'user_additional_details.website                as website',
                'user_additional_details.decision_maker         as decision_maker',
                'user_additional_details.decision_maker_mobile_no as decision_maker_mobile_no',
                'user_additional_details.decision_maker_role    as decision_maker_role_id',
                'user_additional_details.school_board           as school_board_id',
                'user_additional_details.school_medium          as school_medium_id',
                'user_additional_details.strength               as strength',
                'user_additional_details.grade                  as grade_id',
                'user_additional_details.school_affiliation_no  as school_affiliation_no',
                'user_additional_details.school_registration_no as school_registration_no',
                'user_additional_details.incorporation_date     as incorporation_date',
                'user_additional_details.gst_no                 as gst_no',
                'user_additional_details.bank_name              as bank_name',
                'user_additional_details.acc_holder_name        as acc_holder_name',
                'user_additional_details.branch_name            as branch_name',
                'user_additional_details.acc_no                 as acc_no',
                'user_additional_details.ifsc_code              as ifsc_code',
                'user_additional_details.landmark               as landmark',
                'user_additional_details.customer_type          as customer_type',
                'user_additional_details.lead                   as lead',
                'user_additional_details.parent_school_name     as parent_school_name',
            ])
            ->orderBy('users.created_at', 'desc')
            ->get();

        if ($schools->isEmpty()) {
            return collect();
        }

        $schoolUserIds = $schools->pluck('user_id')->unique()->values();

        // ── 2. Lookup tables — single query each ──────────────────────────────
        $stateIds      = $schools->pluck('state_id')->filter()->unique();
        $cityIds       = $schools->pluck('city_id')->filter()->unique();
        $rmIds         = $schools->pluck('assign_to_id')->filter()->unique();
        $distIds       = $schools->pluck('assign_distributor_id')->filter()->unique();
        $dmRoleIds     = $schools->pluck('decision_maker_role_id')->filter()->unique();
        $boardIds      = $schools->pluck('school_board_id')->filter()->unique();
        $mediumIds     = $schools->pluck('school_medium_id')->filter()->unique();
        $gradeIds      = $schools->pluck('grade_id')->filter()->unique();
        $sessionIds    = $schools->pluck('academic_session_id')->filter()->unique();
        $batchIds      = $schools->pluck('batch_id')->filter()->unique();

        $states         = State::whereIn('id', $stateIds)->pluck('name', 'id');
        $cities         = City::whereIn('id', $cityIds)->pluck('city', 'id');
        $rmUsers        = User::whereIn('id', $rmIds)->pluck('name', 'id');
        $distUsers      = User::whereIn('id', $distIds)->pluck('name', 'id');
        $dmRoles        = Role::whereIn('id', $dmRoleIds)->pluck('role_name', 'id');
        $boards         = Board::whereIn('id', $boardIds)->pluck('name', 'id');
        $mediums        = Medium::whereIn('id', $mediumIds)->pluck('name', 'id');
        $grades         = Grade::whereIn('id', $gradeIds)->pluck('name', 'id');
        $sessions       = AcademicSession::whereIn('id', $sessionIds)->pluck('name', 'id');
        $batches        = AcademicSession::whereIn('id', $batchIds)->pluck('batch_name', 'id');

        // ── 3. Class assignments — grouped ───────────────────────────────────
        $classAssignments = SchoolAssignedClass::whereIn('school_id', $schoolUserIds)->get();
        $allClassIds      = $classAssignments->pluck('class_id')->unique();
        $classMap         = Classes::whereIn('id', $allClassIds)->pluck('name', 'id');

        // group: school_id → comma-separated class names
        $schoolClasses = $classAssignments
            ->groupBy('school_id')
            ->map(fn($rows) => $rows
                ->map(fn($r) => $classMap[$r->class_id] ?? null)
                ->filter()
                ->sort()
                ->implode(', ')
            );

        // ── 4. Digital content — single query, then grouped ──────────────────
        $digitalContent = SchoolAssignedDigitalContent::whereIn('school_id', $schoolUserIds)->get();

        $allSeriesIds  = $digitalContent->pluck('series_id')->unique();
        $allSubjectIds = $digitalContent
            ->flatMap(fn($r) => array_filter(array_map('trim', explode(',', $r->subject_id ?? ''))))
            ->unique();
        $allDcClassIds = $digitalContent->pluck('class_id')->unique();

        $seriesMap  = BookSeries::whereIn('id', $allSeriesIds)->pluck('name', 'id');
        $subjectMap = Subject::whereIn('id', $allSubjectIds)->pluck('name', 'id');
        $dcClassMap = Classes::whereIn('id', $allDcClassIds)->pluck('name', 'id');

        // group: school_id → [ [class, series, subjects], ... ]
        $schoolContent = $digitalContent
            ->groupBy('school_id')
            ->map(function ($rows) use ($dcClassMap, $seriesMap, $subjectMap) {
                return $rows->map(function ($r) use ($dcClassMap, $seriesMap, $subjectMap) {
                    $subjectIds   = array_filter(array_map('trim', explode(',', $r->subject_id ?? '')));
                    $subjectNames = array_filter(array_map(fn($id) => $subjectMap[$id] ?? null, $subjectIds));
                    return [
                        'class'    => $dcClassMap[$r->class_id]   ?? 'N/A',
                        'series'   => $seriesMap[$r->series_id]   ?? 'N/A',
                        'subjects' => implode(' | ', $subjectNames) ?: 'N/A',
                    ];
                })->values();
            });

        // ── 5. Build rows ────────────────────────────────────────────────────
        $rows    = collect();
        $counter = 1;

        foreach ($schools as $s) {
            $uid     = $s->user_id;
            $content = $schoolContent->get($uid, collect());
            $status  = (int) $s->status === 1 ? 'Active' : 'Inactive';

            $baseRow = [
                'sno'                    => $counter++,
                'role'                   => $s->role                    ?? 'N/A',
                'unique_id'              => $s->unique_id               ?? 'N/A',
                'name'                   => $s->name                    ?? 'N/A',
                'school_role'            => $s->school_role             ?? 'N/A',
                'school_type'            => $s->school_type             ?? 'N/A',
                'username'               => $s->username                ?? 'N/A',
                'email'                  => $s->email                   ?? 'N/A',
                'mobile_no'              => $s->mobile_no               ?? 'N/A',
                'password'               => $s->validate_string         ?? 'N/A',
                'status'                 => $status,
                'customer_type'          => $s->customer_type           ?? 'N/A',
                'lead'                   => $s->lead                    ?? 'N/A',
                'parent_school_name'     => $s->parent_school_name      ?? 'N/A',
                'academic_session'       => $sessions[$s->academic_session_id] ?? 'N/A',
                'batch'                  => $batches[$s->batch_id]      ?? 'N/A',
                'address1'               => $s->address1                ?? 'N/A',
                'address2'               => $s->address2                ?? 'N/A',
                'landmark'               => $s->landmark                ?? 'N/A',
                'state'                  => $states[$s->state_id]       ?? 'N/A',
                'city'                   => $cities[$s->city_id]        ?? 'N/A',
                'pincode'                => $s->postal_code             ?? 'N/A',
                'assign_rm'              => $rmUsers[$s->assign_to_id]  ?? 'N/A',
                'assign_distributor'     => $distUsers[$s->assign_distributor_id] ?? 'N/A',
                'website'                => $s->website                 ?? 'N/A',
                'decision_maker'         => $s->decision_maker          ?? 'N/A',
                'dm_mobile'              => $s->decision_maker_mobile_no ?? 'N/A',
                'dm_role'                => $dmRoles[$s->decision_maker_role_id] ?? 'N/A',
                'board'                  => $boards[$s->school_board_id] ?? 'N/A',
                'medium'                 => $mediums[$s->school_medium_id] ?? 'N/A',
                'strength'               => $s->strength                ?? 'N/A',
                'grade'                  => $grades[$s->grade_id]       ?? 'N/A',
                'affiliation_no'         => $s->school_affiliation_no   ?? 'N/A',
                'registration_no'        => $s->school_registration_no  ?? 'N/A',
                'incorporation_date'     => $s->incorporation_date      ?? 'N/A',
                'gst_no'                 => $s->gst_no                  ?? 'N/A',
                'classes_assigned'       => $schoolClasses->get($uid, 'N/A'),
                'bank_name'              => $s->bank_name               ?? 'N/A',
                'acc_holder_name'        => $s->acc_holder_name         ?? 'N/A',
                'branch_name'            => $s->branch_name             ?? 'N/A',
                'acc_no'                 => $s->acc_no                  ?? 'N/A',
                'ifsc_code'              => $s->ifsc_code               ?? 'N/A',
                'created_at'             => $s->created_at              ?? 'N/A',
            ];

            // ── One row per digital-content assignment ────────────────────────
            if ($content->isEmpty()) {
                $rows->push(array_merge($baseRow, [
                    'dc_class'    => 'N/A',
                    'dc_series'   => 'N/A',
                    'dc_subjects' => 'N/A',
                ]));
            } else {
                foreach ($content as $idx => $dc) {
                    // Repeat the school info on every content row;
                    // blank out S.No and identity columns for sub-rows to keep it readable
                    $row             = $baseRow;
                    $row['dc_class']   = $dc['class'];
                    $row['dc_series']  = $dc['series'];
                    $row['dc_subjects']= $dc['subjects'];

                    if ($idx > 0) {
                        // Sub-rows: blank S.No + key identifiers so the sheet isn't cluttered
                        $row['sno']       = '';
                        $row['unique_id'] = '';
                        $row['name']      = '';
                        $row['email']     = '';
                        $row['mobile_no'] = '';
                    }

                    $rows->push($row);
                }
            }
        }

        return $rows;
    }

    // ── Column headings ───────────────────────────────────────────────────────
    public function headings(): array
    {
        return [
            // School info
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
            'Customer Type',
            'Lead',
            'Parent School Name',
            'Academic Session',
            'Batch',
            'Address 1',
            'Address 2',
            'Landmark',
            'State',
            'District',
            'Pin Code',
            'Assign RM',
            'Assign Distributor',
            'Website',
            'Decision Maker',
            'DM Mobile No.',
            'DM Role',
            'Board',
            'Medium',
            'Strength',
            'Grade',
            'Affiliation No. / PAN',
            'Registration No.',
            'Incorporation Date',
            'GST No.',
            'Classes Assigned',
            'Bank Name',
            'Acc. Holder Name',
            'Branch Name',
            'Account No.',
            'IFSC Code',
            'Created At',
            // Digital content (repeated per series)
            'DC — Class',
            'DC — Series',
            'DC — Subjects',
        ];
    }

    // ── Styling ───────────────────────────────────────────────────────────────
    public function styles(Worksheet $sheet): array
    {
        $lastCol = 'AQ'; // 43 columns (A–AQ)
        $lastRow = $sheet->getHighestRow();

        // ── Header row ────────────────────────────────────────────────────────
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F3864'],   // dark navy
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '9E9E9E'],
                ],
            ],
        ]);

        // Freeze header row
        $sheet->freezePane('A2');

        // ── Digital content columns header — different accent ─────────────────
        $sheet->getStyle('AO1:AQ1')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D4C92'],  // bright blue
            ],
        ]);

        // ── Data rows: alternating row fill + borders ─────────────────────────
        if ($lastRow > 1) {
            for ($row = 2; $row <= $lastRow; $row++) {
                $fill = ($row % 2 === 0) ? 'F0F4FA' : 'FFFFFF';
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $fill],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_HAIR,
                            'color'       => ['rgb' => 'D0D7E3'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                    ],
                ]);
            }

            // ── Highlight digital-content columns in data rows ─────────────────
            $sheet->getStyle("AO2:AQ{$lastRow}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EBF2FB'],
                ],
            ]);
        }

        // ── Column widths (manual — faster than ShouldAutoSize on large sets) ──
        $widths = [
            'A'  => 6,   // S.No
            'B'  => 14,  // Role
            'C'  => 14,  // Unique ID
            'D'  => 28,  // Full Name
            'E'  => 16,  // School Role
            'F'  => 14,  // School Type
            'G'  => 18,  // Username
            'H'  => 30,  // Email
            'I'  => 16,  // Mobile
            'J'  => 14,  // Password
            'K'  => 10,  // Status
            'L'  => 14,  // Customer Type
            'M'  => 12,  // Lead
            'N'  => 24,  // Parent School
            'O'  => 18,  // Academic Session
            'P'  => 16,  // Batch
            'Q'  => 30,  // Address 1
            'R'  => 30,  // Address 2
            'S'  => 20,  // Landmark
            'T'  => 18,  // State
            'U'  => 18,  // District
            'V'  => 10,  // Pincode
            'W'  => 22,  // RM
            'X'  => 22,  // Distributor
            'Y'  => 22,  // Website
            'Z'  => 22,  // Decision Maker
            'AA' => 16,  // DM Mobile
            'AB' => 16,  // DM Role
            'AC' => 18,  // Board
            'AD' => 14,  // Medium
            'AE' => 12,  // Strength
            'AF' => 12,  // Grade
            'AG' => 24,  // Affiliation
            'AH' => 24,  // Registration
            'AI' => 18,  // Incorporation
            'AJ' => 18,  // GST
            'AK' => 28,  // Classes
            'AL' => 22,  // Bank
            'AM' => 22,  // Acc Holder
            'AN' => 18,  // Branch
            'AO' => 20,  // DC Class    ← digital content start
            'AP' => 24,  // DC Series
            'AQ' => 40,  // DC Subjects
        ];

        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Row height for header
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }
}