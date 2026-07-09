<?php

namespace App\Livewire;

use App\Models\AdditionalDataRow;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class OurOfferings extends Component
{
    use WithFileUploads;

    public $academicCategory = [];
    public $rows = [];
    public $ourOfferingsAddtional; // Accepts passed data

    public function mount($ourOfferingsAddtional = null)
    {
        $this->loadAcademicCategories();
        $this->ourOfferingsAddtional = $ourOfferingsAddtional;

        if ($this->ourOfferingsAddtional && !$this->ourOfferingsAddtional->isEmpty()) {
            foreach ($this->ourOfferingsAddtional as $additionalDataRow) {
                $linkAndDesc = json_decode($additionalDataRow->description);
                $this->rows[] = [
                    'id' => $additionalDataRow->id,
                    'our_offerings_title' => $additionalDataRow->title,
                    'our_offerings_image' => $additionalDataRow->image,
                    'redirection_link' => $linkAndDesc->redirection_link,
                    'ourOfferings_desc' => $linkAndDesc->ourOfferings_desc,
                    'our_offerings_sort_order' => $additionalDataRow->sort_order,
                ];
            }
        } else {
            $this->addRow();
        }
    }


    public function loadAcademicCategories()
    {
        $this->academicCategory = Category::where('status', 1)->whereIn('parent_id', [1, 2])->pluck('name', 'id')->toArray();
    }

    public function addRow()
    {
        $this->rows[] = ['our_offerings_title' => null, 'our_offerings_image' => null, 'redirection_link' => null, 'our_offerings_sort_order' => null];
    }

    public function removeRow($index)
    {
        if (isset($this->rows[$index]['id'])) {
            $rowId = $this->rows[$index]['id'];
            AdditionalDataRow::find($rowId)?->delete(); // Safely delete the record
        }

        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Reindex the array
    }


    public function render()
    {
        return view('livewire.our-offerings');
    }
}
