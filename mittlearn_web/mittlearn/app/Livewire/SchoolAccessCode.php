<?php

namespace App\Livewire;

use App\Models\AccessCode;
use Livewire\Component;

class SchoolAccessCode extends Component
{
    public $infoId;
    public $schoolInfo;
    public $isOpenModal = false;

    public function mount($infoId)
    {
        $this->infoId = $infoId;
    }

    public function loadSchoolInfo()
    {
        $this->schoolInfo = AccessCode::with(['usedBy', 'user', 'board', 'class', 'medium'])->find($this->infoId);
        $this->isOpenModal = true; // Open the modal
    }

    public function closeModal()
    {
        $this->isOpenModal = false; // Close the modal
    }
    public function render()
    {
        return view('livewire.school-access-code');
    }
}
