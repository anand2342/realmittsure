<?php

namespace App\Livewire;

use Livewire\Component;

class AddSubCategory extends Component
{ public $subCategories = [];
    public $isEdit = false; 

    public function mount($subCategories = [], $isEdit = false)
    {
        $this->subCategories = $subCategories;
        $this->isEdit = $isEdit; 

    }

    public function saveSubCategory()
    {
        $this->subCategories[] = ''; 
    }

    public function render()
    {
        return view('livewire.add-sub-category');
    }
}
