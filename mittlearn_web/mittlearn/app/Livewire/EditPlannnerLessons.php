<?php

namespace App\Livewire;

use App\Models\AdditionalDataRow;
use App\Models\MediaFiles;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class EditPlannnerLessons extends Component
{
    use WithFileUploads;
    public $rowsByType = []; // Group rows by stepTypes
    public $showModal = false;
    public $plannerDataID;
    public $id;
    public $type;
    public $model_id;
    public $event_description;
    public $event_title;
    public $stepType;
    protected $rules = [
        'rowsByType.*.title' => 'required|string|max:1000',
        'rowsByType.*.description' => 'required|string',
        // 'rowsByType.*.image' => 'nullable|mimes:jpg,jpeg,png|max:2048', // Max 2MB image size
    ];

    protected $messages = [
        'rowsByType.*.title.required' => 'Title is required for each row.',
        'rowsByType.*.description.required' => 'Description is required for each row.',
        'rowsByType.*.title.max' => 'Title must not exceed 1000 characters.',
        'rowsByType.*.image.mimes' => 'Image must be a file of type: jpg, jpeg, png.',
        'rowsByType.*.image.max' => 'Image size must not exceed 2MB.',
    ];
    public function mount($plannerDataID, $stepType)
    {
        $this->plannerDataID = $plannerDataID;
        $this->stepType = $stepType;
        $this->addRow();
    }

    // Method to open modal and fetch rows based on stepType
    public function openModal($stepType)
    {
        $this->stepType = $stepType;
        // Fetch rows based on stepType
        $rows = AdditionalDataRow::where('model_id', $this->plannerDataID)
            ->where('type', $stepType)
            ->get();
        if ($this->stepType == 'event_function') {
            $event = AdditionalDataRow::where('model_id', $this->plannerDataID)
                ->where('type', 'event_function')
                ->first(); // Fetch only one record

            if ($event) {
                $this->model_id = $event->model_id;
                $this->type = $event->type;
                $this->id = $event->id;
                $this->event_title = $event->title;
                $this->event_description = $event->description;
            } else {
                $this->model_id = null;
                $this->type = null;
                $this->id = null;
                $this->event_title = null;
                $this->event_description = null;
            }
        }

        $this->rowsByType = $rows->map(function ($row) {
            return [
                'model_id' => $row->model_id,
                'type' => $row->type,
                'id' => $row->id,
                'title' => $row->title,
                'image' => $row->image,
                'description' => $row->description,
            ];
        })->toArray();

        // Show modal
        $this->showModal = true;
    }
    public function savePlannerLesson()
    {
        // Validate the input
        $this->validate();
        try {
            if ($this->type === 'event_function') {
                $event = AdditionalDataRow::find($this->id);
                $event->update([
                    'model_id' => $this->model_id,
                    'type' => $this->type,
                    'title' => $this->event_title,
                    'description' => $this->event_description,
                ]);
            } else {
                foreach ($this->rowsByType as $index => $rowData) {
                    if (!empty($rowData['id'])) {
                        $row = AdditionalDataRow::find($rowData['id']);
                        if ($row) {
                            if ($row) {
                                if (!empty($rowData['image'])) {
                                    $filename = $this->handleImageUpload($rowData, $index, $row);
                                } else {
                                    $filename = $row->image;
                                }
                            }
                            $row->update([
                                'model_id' => $rowData['model_id'],
                                'type' => $rowData['type'],
                                'title' => $rowData['title'],
                                'description' => $rowData['description'],
                                'image' => $filename,
                            ]);
                        }
                    } else {
                        $filename = $this->handleImageUpload($rowData, $index);
                        AdditionalDataRow::create([
                            'model_id' => $rowData['model_id'],
                            'type' => $rowData['type'],
                            'title' => $rowData['title'],
                            'description' => $rowData['description'],
                            'image' => $filename,
                        ]);
                    }
                }
            }
            // Provide feedback and close the modal
            return redirect()->to(request()->header('Referer'))
                ->with(['success' => config('constants.FLASH_REC_UPDATE_1')]);
        } catch (\Exception $e) {
            session()->flash('error', 'There was an error while saving the planner lesson.');
        }
    }

    protected function handleImageUpload($rowData, $index = null, $existingRow = null)
    {
        try {
            if (isset($rowData['image']) && $rowData['image'] instanceof \Illuminate\Http\UploadedFile) {
                $uploadedFile = $rowData['image'];
                $filename = time() . "_planner_files{$index}." . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs('uploads/planner-files/', $filename, 'public');
                if ($existingRow && $existingRow->image) {
                    Storage::disk('public')->delete('uploads/planner-files/' . $existingRow->image);
                }

                return $filename; // Return the new filename
            }
            return $existingRow ? $existingRow->image : null;
        } catch (\Exception $e) {
            return null; // Handle exceptions gracefully
        }
    }

    // Add a new row for the selected stepType
    public function addRow()
    {
        $this->rowsByType[] = [
            'model_id' => $this->plannerDataID,
            'id' => null,
            'type' => $this->stepType,
            'title' => '',
            'image' => '',
            'description' => ''
        ];
    }


    public function removeRow($index)
    {
        if (!empty($this->rowsByType[$index]['id'])) {
            $data = AdditionalDataRow::where('id', $this->rowsByType[$index]['id'])->first();
            // dd($data);
            if ($data->image) {
                if (Storage::disk('public')->exists('uploads/planner-files/' . $data->image)) {
                    Storage::disk('public')->delete('uploads/planner-files/' . $data->image);
                }
                $data->delete();
            }
        }
        unset($this->rowsByType[$index]);
        $this->rowsByType = array_values($this->rowsByType);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.edit-plannner-lessons');
    }
}
