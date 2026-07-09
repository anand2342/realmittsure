<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class DynamicFormTest extends Component
{
    public $row = [];
    public function mount()
    {
        $this->row[] = [
            'name' => '',
            'email' => '',
            'phone' => ''
        ];
    }
    public function addRow()
    {
        $this->row[] = [
            'name' => '',
            'email' => '',
            'phone' => '',
        ];
    }
    public function removeRow($index)
    {
        unset($this->row[$index]);
        $this->row = array_values($this->row);
    }


    public function save()
    {
        $this->validate([
            'rows.*.name' => 'required|string|min:3',
            'row.*.email' => 'required|email'
        ]);
        foreach ($this->row as $row) {
            User::create($row);
        }
        session()->flash('success', 'saved');
    }
    public function render()
    {
        return view('livewire.dynamic-form-test');
    }
}
