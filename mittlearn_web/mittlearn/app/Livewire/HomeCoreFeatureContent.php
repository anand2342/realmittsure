<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\AdditionalDataRow;

class HomeCoreFeatureContent extends Component
{
    use WithFileUploads;

    public $rows_1 = [];
    public $coreTitle;
    public $coreHeading;
    public $coreAcademicFeatureAddtional;

    public function mount($coreAcademicFeatureAddtional = null)
    {
        $this->coreAcademicFeatureAddtional = $coreAcademicFeatureAddtional;

        if ($this->coreAcademicFeatureAddtional && !$this->coreAcademicFeatureAddtional->isEmpty()) {
            foreach ($this->coreAcademicFeatureAddtional as $additionalDataRow) {
                $this->rows_1[] = [
                    'id' => $additionalDataRow->id,
                    'icon_title' => $additionalDataRow->title,
                    'icon_image' => $additionalDataRow->image,
                    'icon_description' => $additionalDataRow->description,
                ];
            }
        } else {
            $this->addRow();
        }
    }

    public function addRow()
    {
        // Add a new empty row for a new icon
        $this->rows_1[] = ['icon_title' => '', 'icon_image' => '', 'icon_description' => ''];
    }

    public function removeRow($index)
    {
        if (isset($this->rows_1[$index]['id'])) {
            $rowId = $this->rows_1[$index]['id'];
            // Safely delete the record if it exists
            AdditionalDataRow::find($rowId)?->delete();
        }

        unset($this->rows_1[$index]);
        $this->rows_1 = array_values($this->rows_1); // Reindex the array
    }

    public function render()
    {
        return view('livewire.home-core-feature-content');
    }
}
