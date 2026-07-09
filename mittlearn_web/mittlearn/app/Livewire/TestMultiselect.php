<?php

namespace App\Livewire;

use Livewire\Component;

class TestMultiselect extends Component
{
    public array $selectedItems = [];

    public function render()
    {
        $options = [
            ['id' => 1, 'name' => 'Mathematics'],
            ['id' => 2, 'name' => 'Science'],
            ['id' => 3, 'name' => 'English'],
            ['id' => 4, 'name' => 'History'],
        ];

        return view('livewire.test-multiselect', compact('options'));
    }
}
