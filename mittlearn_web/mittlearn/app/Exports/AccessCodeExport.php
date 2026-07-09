<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccessCodeExport implements FromCollection, WithHeadings, WithStyles
{
    protected $accessCodes;

    public function __construct($accessCodes)
    {
        $this->accessCodes = $accessCodes;
    }

    // Data to export
    public function collection()
    {
        return $this->accessCodes->map(function ($accessCode, $index) {
            // Determine the Access Code Type
            $accessCodeType = 'Embibe'; // Default value
            if ($accessCode->type == 'digital_content') {
                $accessCodeType = 'Digital Content';
            } elseif ($accessCode->type == 'lumalearn') {
                $accessCodeType = 'Luma Learn';
            }

            return [
                'S.No' => $index + 1,
                'School' => $accessCode->school->name ?? 'N/A',
                'Board' => $accessCode->board->name ?? 'N/A',
                'Medium' => $accessCode->medium->name ?? 'N/A',
                'Book Series Name' => $accessCode->bookSeries->name ?? 'N/A',
                'Class' => $accessCode->class->name ?? 'N/A',
                'Access Code' => $accessCode->access_code,
                'Access Code Type' => $accessCodeType,
                'Start Date' => $accessCode->start_date ?? 'N/A',
                'Expired Date' => $accessCode->end_date ?? 'N/A',
                'Status' => $accessCode->status ?? 'N/A',
                'Used By' => $accessCode->usedBy->name ?? 'N/A',
            ];
        });
    }

    // Headings for Excel/CSV
    public function headings(): array
    {
        return [
            'S.No',
            'School',
            'Board',
            'Medium',
            'Book Series Name',
            'Class',
            'Access Code',
            'Access Code Type',
            'Start Date',
            'Expired Date',
            'Status',
            'Used By',
        ];
    }

    // Apply styles to the header
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
