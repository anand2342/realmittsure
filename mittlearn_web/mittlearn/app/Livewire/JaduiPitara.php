<?php

namespace App\Livewire;

use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\JaduiPitara as ModelsJaduiPitara;
use App\Models\Subject;
use Livewire\Component;

class JaduiPitara extends Component
{
    public $data = [];
    public $selectedSeriesId = null;
    public $selectedClassId = null;
    public $classSubjects;
    public $assignedClasses;
    public $bookSeries;
    public $allClasses;
    public $subjects = [];
    public $rows = []; // Tracks BookSeries & Subjects per class

    public function mount($id = null)
    {
        $this->allClasses = Classes::where('is_active', 1)->orderBy('created_at', 'asc')->pluck('name', 'id');
        $this->bookSeries = BookSeries::where('is_active', 1)->pluck('name', 'id');

        $assigned = ModelsJaduiPitara::pluck('jadui_pitara_classes_id')->toArray();
        $this->assignedClasses = !empty($assigned)
            ? $assigned
            : Classes::where('is_active', 1)->take(3)->pluck('id')->toArray();

        $existingData = ModelsJaduiPitara::whereIn('class_id', $this->assignedClasses)->get();

        foreach ($this->assignedClasses as $classId) {
            $classData = $existingData->where('class_id', $classId);

            if ($classData->isNotEmpty()) {
                $index = 0;
                foreach ($classData as $data) {
                    $this->rows[$classId][$index] = [
                        'series_id' => $data->series_id,
                        'subject_ids' => explode(',', $data->subject_id),
                        'temp_id' => uniqid(),
                    ];

                    // Load subjects based on series
                    $assignedData = BookSeries::find($data->series_id);
                    if ($assignedData) {
                        $classSubjects = json_decode($assignedData->class_subjects, true);
                        $subjectList = [];

                        if (is_array($classSubjects)) {
                            foreach ($classSubjects as $entry) {
                                if ($entry['class_id'] == $classId) {
                                    $subjectIds = $entry['subject_ids'];
                                    $subjectList = Subject::whereIn('id', $subjectIds)->pluck('name', 'id')->toArray();
                                    break;
                                }
                            }
                        }

                        $this->subjects[$classId][$index] = $subjectList;
                    } else {
                        $this->subjects[$classId][$index] = [];
                    }

                    $index++; // Move to next row
                }
            } else {
                // No existing data, create one blank row
                $this->rows[$classId][0] = [
                    'series_id' => null,
                    'subject_ids' => [],
                    'temp_id' => uniqid(),
                ];
                $this->subjects[$classId][0] = [];
            }
        }
    }








    public function render()
    {
        return view('livewire.jadui-pitara');
    }
}
