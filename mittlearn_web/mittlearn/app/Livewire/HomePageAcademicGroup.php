<?php

namespace App\Livewire;

use App\Models\AdditionalDataRow;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomePageAcademicGroup extends Component
{
    use WithFileUploads;

    public $categories = [];
    public $rows = [];
    public $firstBannerAddtional; // Accepts passed data

    public function mount($firstBannerAddtional = null)
    {
        $this->loadAcademicCategories();
        $this->firstBannerAddtional = $firstBannerAddtional;

        if ($this->firstBannerAddtional && !$this->firstBannerAddtional->isEmpty()) {
            foreach ($this->firstBannerAddtional as $additionalDataRow) {
                $this->rows[] = [
                    'id' => $additionalDataRow->id,
                    'group_id' => $additionalDataRow->model_id,
                    'group_academic_title' => $additionalDataRow->title,
                    'group_academic_image' => $additionalDataRow->image,
                    'redirection_link' => $additionalDataRow->description,
                ];
            }
        } else {
            $this->addRow();
        }
    }


    public function loadAcademicCategories()
    {
        $this->categories = Category::where('status', 1)->where('parent_id', null)->pluck('name', 'id')->toArray();
    }

    public function addRow()
    {
        $this->rows[] = ['group_id' => null,'group_academic_title' => null, 'group_academic_image' => null, 'redirection_link' => null];
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
        return view('livewire.home-page-academic-group');
    }
}
