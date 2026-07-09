<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccessCodeEmbibeExport implements FromCollection, WithHeadings, WithStyles
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
                'School' => $accessCode->school->name ?? ' ',
                // 'Board' => $accessCode->board->name ?? ' ',
                'Board' => $accessCode->board ?? ' ',
                'Medium' => $accessCode->medium->name ?? ' ',
                'Book Series Name' => $accessCode->bookSeries->name ?? ' ',
                'Class' => $accessCode->class->name ?? ' ',
                // 'Access Code' => $accessCode->access_code,
                'Access Code' => $accessCode->licence_key,
                'Access Code Type' => $accessCodeType,
                'Start Date' => $accessCode->start_date ?? ' ',
                'Expired Date' => $accessCode->end_date ?? ' ',
                'Status' => $accessCode->status ?? ' ',
                'Used By' => $accessCode->usedBy->name ?? ' ',
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
