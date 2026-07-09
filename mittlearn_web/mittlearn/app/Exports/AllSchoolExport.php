<?php

namespace App\Exports;

use App\Models\AcademicSession;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllSchoolExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new SchoolDetailsSheet,
            new SchoolDigitalContentSheet,
        ];
    }
}