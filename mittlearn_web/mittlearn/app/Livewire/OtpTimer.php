<?php
namespace App\Livewire;

use Livewire\Component;

class OtpTimer extends Component
{
    public $timeRemaining = 30; // Time in seconds

    public function mount()
    {
        // No action needed here
    }

    public function render()
    {
        return view('livewire.otp-timer');
    }

    public function decrementTimer()
    {
        if ($this->timeRemaining > 0) {
            $this->timeRemaining--;
        }
    }
}
