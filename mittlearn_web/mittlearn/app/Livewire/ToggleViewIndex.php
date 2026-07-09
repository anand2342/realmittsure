<?php

namespace App\Livewire;

use Livewire\Component;

class ToggleViewIndex extends Component
{
    public $view;

    public function mount()
    {
        // Retrieve from session/cookie if exists, else default
        $this->view = session('toggle_state', 'index-academic');
    }

    public function toggleViewIndex()
    {
        // Toggle between views
        if ($this->view === 'index-academic') {
            $this->view = 'index-nonacademic';
        } else {
            $this->view = 'index-academic';
        }

        // Store the toggle state in the session or cookie
        session(['toggle_state' => $this->view]);
    }

    public function render()
    {
        return view('livewire.toggle-view-index');
    }
}
