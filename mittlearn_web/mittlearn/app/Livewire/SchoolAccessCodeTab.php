<?php

namespace App\Livewire;

use App\Models\AccessCode;
use App\Models\Schools;
use Livewire\Component;

class SchoolAccessCodeTab extends Component
{
    public $tab = 'schoolList';
    public $schools;
    public $selectedSchool;
    public $accessCodes;

    public function mount()
    {
        $this->schools = Schools::all();
    }

    public function showAccessCodes($schoolId)
    {

        $this->selectedSchool = $schoolId;
        if ($this->selectedSchool) {
            $this->accessCodes = AccessCode::where('school_id', $schoolId)->get();
            $this->tab = 'accessCode'; 
        }
    }


    public function render()
    {
        return view('livewire.school-access-code-tab');
    }
}
