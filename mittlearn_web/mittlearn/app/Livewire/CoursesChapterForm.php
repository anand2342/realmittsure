<?php

namespace App\Livewire;

use Livewire\Component;

class CoursesChapterForm extends Component
{
    public $course_id;
    public $total_chapter = 1;  
    public $chapters = [];

    
    public function mount($course_id)
    {
        $this->course_id = $course_id;
        $this->updateChapters(); 
    }

    public function updateChapters(): void
    {
        $this->chapters = array_fill(0, $this->total_chapter, []); 
    }

    public function updatedTotalChapter()
    {
        // dd($value);
        // dd($this->total_chapter);
 
        $this->total_chapter = max(1, (int) $this->total_chapter);
        $this->updateChapters(); 
    }

    public function render()
    {
        return view('livewire.courses-chapter-form',['chapters' => $this->chapters]);
    }
}

