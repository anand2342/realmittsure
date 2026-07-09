<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryForm extends Component
{
    public $categoryId;
    public $name;
    public $description;
    public $status;

    protected $listeners = ['openModal', 'closeModal'];

    public function openModal($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->status = $category->status;
        }
    }

    public function closeModal()
    {
        $this->resetInputFields();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $category = Category::find($this->categoryId);
        if ($category) {
            $category->name = $this->name;
            $category->description = $this->description;
            $category->status = $this->status;
            $category->save();
        }

        session()->flash('message', 'Category updated successfully.');
        $this->dispatchBrowserEvent('closeModal');
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
        $this->status = '';
    }

    public function render()
    {
        return view('livewire.category-modal');
    }
}
