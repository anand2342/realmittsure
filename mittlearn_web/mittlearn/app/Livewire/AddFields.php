<?php

namespace App\Livewire;

use Livewire\Component;

class AddFields extends Component
{
    public array $fields = [];
    public $categoryId;
    public $existingCustomFields;

    public function mount($categoryId, $existingCustomFields = [])
    {
        $this->categoryId = $categoryId;
        $this->existingCustomFields = $existingCustomFields;
        
        $this->existingCustomFields = is_array($existingCustomFields)
        ? collect($existingCustomFields)
        : $existingCustomFields;

        if ($this->existingCustomFields->isNotEmpty()) {
            foreach ($this->existingCustomFields as $field) {
                $this->fields[] = [
                    'id' => $field->id,
                    'field_name' => $field->field_name,
                    'field_label' => $field->field_label,
                    'field_placeholder' => $field->field_placeholder,
                    'field_type' => $field->field_type,
                    'sort_order' => $field->sort_order,
                    'is_active' => $field->is_active,
                ];
            }
        }
    }

    public function addField()
    {
        $this->fields[] = [
            'sort_order' => '',
            'field_name' => '',
            'field_label' => '',
            'field_placeholder' => '',
            'field_type' => '',
            'is_active' => 1,
        ];
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function render()
    {
        return view('livewire.add-fields');
    }
}
