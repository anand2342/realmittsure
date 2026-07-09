<?php

namespace App\Exports;

use App\Models\BookSeries;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CourseContentExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithCustomStartCell
{

    protected $seriesList;
    protected $classList;
    protected $subjectList;


    public function __construct()
    {
        $this->seriesList = BookSeries::pluck('name', 'id');
        $this->classList = SchoolClass::pluck('name', 'id');
        $this->subjectList = Subject::pluck('name', 'id');
    }

    public function collection()
    {
        return Course::with(['metadataValues', 'totalChapters.chapterListing'])
            ->where('category_id', 1)
            ->where('is_active', 1)
            ->get();
            // ->filter(function ($course) {
            //     $seriesId = $course->metadataValues->where('field_name', 'series')->pluck('field_value')->first();
            //     return $seriesId == 19;
            // })
            // ->values();
    }

    public function headings(): array
    {
        return ['Series Name', 'Class', 'Subject', 'Book Title', 'Chapter Count', 'Video Count'];
    }

    public function map($course): array
    {
        $seriesId = $course->metadataValues->where('field_name', 'series')->pluck('field_value')->first();
        $series = $this->seriesList[$seriesId] ?? '';
        $classId = $course->metadataValues->where('field_name', 'class')->pluck('field_value')->first();
        $class = $this->classList[$classId] ?? '';

        $subjectId = $course->metadataValues->where('field_name', 'subject')->pluck('field_value')->first();
        $subject = $this->subjectList[$subjectId] ?? '';
        $bookTitle = $course->course_name ?? '';

        $chapterCount = $course->totalChapters->count();

        $videoExtensions = [
            'mp4',
            'avi',
            'mov',
            'm4v',
            'm4p',
            'mpg',
            'mp2',
            'mpeg',
            'mpe',
            'mpv',
            'm2v',
            'wmv',
            'flv',
            'mkv',
            'webm',
            '3gp',
            'm2ts',
            'ogv',
            'ts',
            'mxf'
        ];

        $videoCount = $course->totalChapters->flatMap(function ($chapter) use ($videoExtensions) {
            return $chapter->chapterListing->filter(function ($file) use ($videoExtensions) {
                return in_array(strtolower($file->file_extension), $videoExtensions);
            });
        })->count();

        return [
            $series,
            $class,
            $subject,
            $bookTitle,
            $chapterCount,
            $videoCount
        ];
    }


    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            // Header row style
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'], // Yellow background
                ],
            ],
        ];
    }
}
