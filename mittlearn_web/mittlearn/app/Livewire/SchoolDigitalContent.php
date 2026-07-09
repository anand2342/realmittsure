<?php

namespace App\Livewire;

use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\Schools;
use App\Models\Subject;
use Livewire\Component;
use App\Models\SchoolAssignedDigitalContent;

class SchoolDigitalContent extends Component
{
    public $data = [];
    public $selectedSeriesId = null;
    public $selectedClassId = null;
    public $schoolId = null;
    public $classSubjects;
    public $subjects = [];
    public $rows = []; // Tracks BookSeries & Subjects per class

    public function mount($id)
    {
        $this->data['id'] = $id;
        $this->schoolId = $id;
        $this->data['allClasses'] = Classes::where('is_active', 1)->where('is_available_to_assign', 1)->pluck('name', 'id');
        // $this->data['allClasses'] = Classes::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
        $this->data['bookSeries'] = BookSeries::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes'] = getUserSchoolClasses($id);
        $this->data['assignedClasses'] = array_keys($this->data['classes']);

        $this->data['existingData'] = SchoolAssignedDigitalContent::where('school_id', $id)
            ->whereIn('class_id', $this->data['assignedClasses'])
            ->get()
            ->groupBy('class_id')
            ->toArray();

        foreach ($this->data['assignedClasses'] as $classId) {
            $this->rows[$classId] = [];

            if (!empty($this->data['existingData'][$classId])) {
                foreach ($this->data['existingData'][$classId] as $data) {
                    $index = count($this->rows[$classId]);

                    $this->rows[$classId][$index] = [
                        'series_id' => $data['series_id'],
                        'subject_ids' => explode(',', $data['subject_id']),
                        'temp_id' => uniqid(),
                    ];

                    // Fetch subjects for the specific row
                    $assignedData = BookSeries::find($data['series_id']);
                    if ($assignedData) {
                        $classSubjects = json_decode($assignedData->class_subjects, true);
                        if (is_array($classSubjects)) {
                            foreach ($classSubjects as $entry) {
                                if ($entry['class_id'] == $classId) {
                                    $subjectIds = $entry['subject_ids'];
                                    $this->subjects[$classId][$index] = Subject::whereIn('id', $subjectIds)->pluck('name', 'id')->toArray();
                                    break;
                                }
                            }
                        }
                    } else {
                        $this->subjects[$classId][$index] = [];
                    }
                }
            } else {
                // If no existing data, add an empty row
                $index = count($this->rows[$classId]);
                $this->rows[$classId][$index] = [
                    'series_id' => null,
                    'subject_ids' => [],
                    'temp_id' => uniqid(),
                ];
                $this->subjects[$classId][$index] = [];
            }
        }
    }



    public function addRow($classId)
    {
        $this->rows[$classId][] = [
            'series_id' => null,
            'subject_ids' => [],
            'temp_id' => uniqid()
        ];
    }


    public function removeRow($classId, $index)
    {
        if (count($this->rows[$classId]) > 1) {
            unset($this->rows[$classId][$index]);
            $this->rows[$classId] = array_values($this->rows[$classId]); // Reindex array
        }
    }

    public function fetchSubjects($classId, $seriesId, $index)
    {
        if ($seriesId) {
            $assignedData = BookSeries::find($seriesId);
            if ($assignedData) {
                $classSubjects = json_decode($assignedData->class_subjects, true);
                if (is_array($classSubjects)) {
                    foreach ($classSubjects as $entry) {
                        if ($entry['class_id'] == $classId) {
                            $subjectIds = $entry['subject_ids'];

                            // Store subjects for specific series selection
                            $this->subjects[$classId][$index] = Subject::whereIn('id', $subjectIds)
                                ->pluck('name', 'id')
                                ->toArray();

                            return;
                        }
                    }
                }
            }
        }
        // If no series is selected, keep existing subjects instead of resetting them
        $this->subjects[$classId][$index] = $this->subjects[$classId][$index] ?? [];
    }




    public function render()
    {
        return view('livewire.school-digital-content', $this->data);
    }
}
