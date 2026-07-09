<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CategoryIndex extends Component
{
    use WithFileUploads;

    public $category;
    public $category_name, $description, $featured_image, $icon, $status, $categoryId;
    public $name;
    public $showModal = false;

    public function add($categoryId)
    {
        $this->categoryId = $categoryId;
        $hierarchy = $this->getCategoryHierarchy($categoryId);
        $this->name = implode(' -> ', $hierarchy);
        $this->category_name = '';
        $this->description = '';
        $this->status = 1;
        $this->showModal = true;
    }

    public function getCategoryHierarchy($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return [];
        }
        $hierarchy = [];
        $hierarchy[] = $category->name;
        while ($category->parent_id) {
            $category = Category::find($category->parent_id);
            if ($category) {
                array_unshift($hierarchy, $category->name);
            } else {
                break;
            }
        }

        return $hierarchy;
    }

    public function updateCategory()
    {
        $validatedData = $this->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        $slug = generateUniqueSlug($this->category_name, Category::class, 'slug');

        $category = new Category();
        $category->name = $this->category_name;
        $category->slug = $slug;
        $category->description = $this->description;
        $category->status = $this->status;
        $category->parent_id = $this->categoryId;

        if ($this->icon) {
            $filename = time() . '.' . $this->icon->getClientOriginalExtension();
            $this->icon->storeAs('uploads/categories/icon', $filename, 'public');
            $category->icon = $filename;
        }

        $category->save();
        $this->reset(['category_name', 'description', 'icon', 'status', 'showModal']);
        return redirect()->to(request()->header('Referer'));
    }


    public function render()
    {
        return view('livewire.category-index');
    }
}
