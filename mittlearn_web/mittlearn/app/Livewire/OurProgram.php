<?php

namespace App\Livewire;

use App\Models\AdditionalDataRow;
use Livewire\Component;
use Livewire\WithFileUploads;

class OurProgram extends Component
{
    use WithFileUploads;

    public $row = [];
    public $coreTitle;
    public $coreHeading;
    public $programs;

    public function mount($programs = null)
    {
        $this->programs = $programs;

        if ($this->programs && !$this->programs->isEmpty()) {
            foreach ($this->programs as $additionalDataRow) {
                $this->row[] = [
                    'id' => $additionalDataRow->id,
                    'title' => $additionalDataRow->title,
                    'image' => $additionalDataRow->image,
                    'description' => $additionalDataRow->description,
                    'url_redirection' => $additionalDataRow->url_redirection,
                ];
            }
        } else {
            $this->addRow();
        }
    }

    public function addRow()
    {
        // Add a new empty row for a new icon
        $this->row[] = ['title' => '', 'image' => '', 'description' => '', 'url_redirection' => ''];
    }

    public function removeRow($index)
    {
        if (isset($this->row[$index]['id'])) {
            $rowId = $this->row[$index]['id'];
            // Safely delete the record if it exists
            AdditionalDataRow::find($rowId)?->delete();
        }

        unset($this->row[$index]);
        $this->row = array_values($this->row); // Reindex the array
    }
    public function render()
    {
        return view('livewire.our-program');
    }
}
