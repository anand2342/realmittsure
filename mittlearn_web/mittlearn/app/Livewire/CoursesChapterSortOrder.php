<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CourseChapter;

class CoursesChapterSortOrder extends Component
{
    public $chapter;
    public $sortOrder;
    public $isEditing = false;

    public function mount($chapter)
    {
        $this->chapter = $chapter;
        $this->sortOrder = $chapter->sort_order;
    }

    public function updateSortOrder()
    {
        // Ensure the sort order is unique
        $sortOrder = $this->sortOrder;
        $courseId = $this->chapter->course_id;

        while (CourseChapter::where('course_id', $courseId)->where('sort_order', $sortOrder)->where('id', '!=', $this->chapter->id)->exists()) {
            $sortOrder++;
        }

        // Update the chapter with the unique sort order
        $this->chapter->update(['sort_order' => $sortOrder]);
        $this->sortOrder = $sortOrder; // Update local variable
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.courses-chapter-sort-order');
    }
}
