<?php

namespace App\Livewire;

use Livewire\Component;

class AcademicBatches extends Component
{
    public $batches = [];

    // public function mount($existingBatches = [])
    // {
    //     $this->batches = $existingBatches ?: [
    //         ['batch_name' => '', 'start_date' => '', 'end_date' => '']
    //     ];
    // }

    public function mount($existingBatches = [])
    {
        $this->batches = old(
            'batches',
            array_map(function ($batch) {
                return [
                    'batch_name' => $batch['batch_name'] ?? '',
                    'start_date' => $batch['start_date'] ?? '',
                    'end_date' => $batch['end_date'] ?? ''
                ];
            }, $existingBatches)
        ) ?: [['batch_name' => '', 'start_date' => '', 'end_date' => '']];
    }

    public function addBatch()
    {
        $this->batches[] = ['batch_name' => '', 'start_date' => '', 'end_date' => ''];
    }

    public function removeBatch($index)
    {
        unset($this->batches[$index]);
        $this->batches = array_values($this->batches);
    }

    public function render()
    {
        return view('livewire.academic-batches');
    }
}
