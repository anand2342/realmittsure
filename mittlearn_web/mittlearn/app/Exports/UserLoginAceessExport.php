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

class UserLoginAceessExport implements FromCollection, WithHeadings, WithStyles
{
    protected $users;
    protected $userType;

    public function __construct($users, $userType)
    {
        $this->users = $users;
        $this->userType = $userType;
    }

    public function collection()
    {
        // Format your data based on user type
        if ($this->userType === 'student') {
            return $this->users->map(function ($user) {
                return [
                    'Name' => $user->name ?? '',
                    'Login Email' => $user->email ?? '',
                    'Login Mob. No.' => $user->mobile_no ?? '',
                    'Password' => $user->validate_string ?? '',
                ];
            });
        } else {
            return $this->users->map(function ($user) {
                return [
                    'Name' => $user->name ?? '',
                    'Login Email' => $user->email ?? '',
                    'Login Mob. No.' => $user->mobile_no ?? '',
                    'Password' => $user->validate_string ?? '',
                ];
            });
        }
    }

    public function headings(): array
    {
        if ($this->userType === 'student') {
            return [
                'Name',
                'Login Email',
                'Login Mob. No.',
                'Password',
            ];
        } else {
            return [
                'Name',
                'Login Email',
                'Login Mob. No.',
                'Password',
      
            ];
        }
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
