<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomePageNonAcademicGroup extends Component
{
    use WithFileUploads;

    public $nonAcademicCategory = [];
    public $rows = [];
    public $firstBanner; // Optional data passed to the component

    public function mount($firstBanner = null)
    {
        $this->firstBanner = $firstBanner;
        $this->loadNonAcademicCategories();

        if ($this->firstBanner && isset($this->firstBanner['group_non_academic_title']) && isset($this->firstBanner['non_academic_image'])) {
            $titles = explode(',', $this->firstBanner['group_non_academic_title']);
            $images = explode(',', $this->firstBanner['non_academic_image']);
            foreach ($titles as $key => $title) {
                $this->rows[] = [
                    'group_non_academic_title' => trim($title),
                    'group_non_academic_image' => isset($images[$key]) ? trim($images[$key]) : null,
                ];
            }
        } else {
            $this->addRow();
        }
    }

    public function loadNonAcademicCategories()
    {
        $this->nonAcademicCategory = Category::where('status', 1)->where('parent_id', 2)
            ->pluck('name', 'id')
            ->toArray();
    }

    public function addRow()
    {
        $this->rows[] = [
            'group_non_academic_title' => null,
            'group_non_academic_image' => null,
        ];
    }

    public function removeRow($index)
    {
        // Ensure at least one row remains
        if (count($this->rows) > 1) {
            // Clean up uploaded image if applicable
            if (isset($this->rows[$index]['group_non_academic_image']) && $this->rows[$index]['group_non_academic_image']) {
                // Clean up uploaded file if applicable
            }

            unset($this->rows[$index]);
            $this->rows = array_values($this->rows); // Reindex the array
        }
    }

    public function render()
    {
        return view('livewire.home-page-non-academic-group');
    }
}
