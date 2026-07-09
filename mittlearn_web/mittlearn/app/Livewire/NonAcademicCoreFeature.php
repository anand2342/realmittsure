<?php

namespace App\Livewire;

use App\Models\AdditionalDataRow;
use Livewire\Component;
use Livewire\WithFileUploads;

class NonAcademicCoreFeature extends Component
{
    use WithFileUploads;

    public $rows_2 = [];
    public $coreTitle;
    public $coreHeading;
    public $coreNonAcademicFeatureAddtional;

    public function mount($coreNonAcademicFeatureAddtional = null)
    {
        $this->coreNonAcademicFeatureAddtional = $coreNonAcademicFeatureAddtional;

        if ($this->coreNonAcademicFeatureAddtional && !$this->coreNonAcademicFeatureAddtional->isEmpty()) {
            foreach ($this->coreNonAcademicFeatureAddtional as $additionalDataRow) {
                $this->rows_2[] = [
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
        $this->rows_2[] = ['icon_title' => '', 'icon_image' => '', 'icon_description' => ''];
    }

    public function removeRow($index)
    {
        if (isset($this->rows_2[$index]['id'])) {
            $rowId = $this->rows_2[$index]['id'];
            AdditionalDataRow::find($rowId)?->delete();
        }

        unset($this->rows_2[$index]);
        $this->rows_2 = array_values($this->rows_2); // Reindex the array
    }


    public function render()
    {
        return view('livewire.non-academic-core-feature');
    }
}
