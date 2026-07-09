<?php

namespace App\Exports;

use App\Models\AccessCode;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class ActiveAccessCodesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $accessCode = AccessCode::where('school_id', Auth::id())
            ->with('class')
            ->select('class_id')
            ->selectRaw('
                count(*) as total_codes,
                count(case when user_id is not null then 1 end) as used_codes,
                count(case when user_id is null then 1 end) as unused_codes
            ')
            ->groupBy('class_id')
            ->orderBy('class_id', 'asc')
            ->get();
        return $accessCode->isNotEmpty() ? $accessCode : collect([]);
    }

    public function headings(): array
    {
        return [
            'Class',
            'Total Codes',
            'Occupied',
            'Remaining',
        ];
    }

    public function map($row): array
    {
        return [
            $row->class?->name ?? 'N/A',
            $row->total_codes ?? 0,
            $row->used_codes ?? 0,
            $row->unused_codes ?? 0,
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
