<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use Livewire\Component;

class EditPlannerPage extends Component
{
    public array $planners = [];
    public  $academicSession;
    public  $batches;
    public  $plannerData;
    public  $academic_session;
    public $batchEndtDate;
    public $batchStartDate;
    public $readonly = false;

    public function mount($plannerData)
    {
        $this->academicSession = AcademicSession::where('is_active', 1)
            ->get()
            ->unique('name')
            ->pluck('name', 'id')
            ->toArray();

        if ($plannerData != null) {
            $this->plannerData = $plannerData;
            $academicSession = AcademicSession::find($plannerData->academic_session_id);
            if ($this->plannerData->type == 'monthly' || $this->plannerData->type == 'weekly') {
                $this->readonly = true;
            }
            $this->planners[] = [
                'academic_session_id' => $this->plannerData->academic_session_id,
                'batch_id' => $this->plannerData->batch_id,
                'allotted_days' => $this->plannerData->allotted_days,
                'total_periods' => $this->plannerData->total_periods,
                'start_date' => $this->plannerData->start_date,
                'completion_date' => $this->plannerData->completion_date,
                'is_main' => true,
                'batches' => AcademicSession::where('name', $academicSession->name ?? null)
                    ->pluck('batch_name', 'id')
                    ->toArray(), // <-- Batches saved INSIDE each planner
            ];
        }
    }

    public function blankPlanner(): array
    {
        if ($this->plannerData->type === 'daily') {
            return [
                'academic_session_id' => '',
                'batch_id' => '',
                'allotted_days' => '',
                'start_date' => '',
                'completion_date' => '',
                'total_periods' => '',
                'batches' => [],
                'is_main' => false, // <-- IMPORTANT
            ];
        } elseif ($this->plannerData->type === 'weekly') {
            return [
                'academic_session_id' => '',
                'batch_id' => '',
                'allotted_days' => 7,
                'start_date' => '',
                'completion_date' => '',
                'total_periods' => '',
                'batches' => [],
                'is_main' => false, // <-- IMPORTANT
            ];
        } else {
            return [
                'academic_session_id' => '',
                'batch_id' => '',
                'allotted_days' => 30,
                'start_date' => '',
                'completion_date' => '',
                'total_periods' => '',
                'batches' => [],
                'is_main' => false, // <-- IMPORTANT

            ];
        }
    }
    public function batchDate($index)
    {
        // For debugging
        // dd($this->planners[$index]['batch_id']);

        $batch = AcademicSession::find($this->planners[$index]['batch_id']);

        if ($batch) {
            $this->planners[$index]['batchStartDate'] = $batch->start_date;
            $this->planners[$index]['batchEndtDate'] = $batch->end_date;
        }
    }
    public function updateCompletionDate($index)
    {

        // For debugging
        // dd($this->planners[$index]['start_date']);

        // Clear any old error first
        $this->resetErrorBag("planners.$index.start_date");

        $startDate = $this->planners[$index]['start_date'];
        $startLimit = $this->planners[$index]['batchStartDate'] ?? null;
        $endLimit = $this->planners[$index]['batchEndtDate'] ?? null;

        // Custom validation
        if ($startDate < $startLimit || $startDate > $endLimit) {
            $this->addError("planners.$index.start_date", 'Please select a start date within the selected academic year.');
            $this->planners[$index]['completion_date'] = null;
            return;
        }

        if ($startDate && $this->planners[$index]['allotted_days']) {
            $this->planners[$index]['completion_date'] = calculatePlannerCompletionDate(
                $startDate,
                $this->planners[$index]['allotted_days'],
                true,
                false
            );
        } else {
            $this->planners[$index]['completion_date'] = null;
        }
    }
    public function batchUpdate($index)
    {
        $sessionId = $this->planners[$index]['academic_session_id'] ?? null;

        if (!$sessionId) {
            $this->planners[$index]['batches'] = [];
            return;
        }

        $academicSession = AcademicSession::find($sessionId);
        if (!$academicSession) {
            $this->planners[$index]['batches'] = [];
            return;
        }

        $this->planners[$index]['batches'] = AcademicSession::where('is_active', 1)
            ->where('name', $academicSession->name)
            ->pluck('batch_name', 'id')
            ->toArray();
    }

    // public function fetchBatch()
    // {
    //     // dd($this->academic_session);
    //     $sessionId = $this->academic_session_id ?? null;
    //     if (!$sessionId) {
    //         $this->batches = [];
    //         return;
    //     }

    //     $academicSession = AcademicSession::find($sessionId);

    //     if (!$academicSession) {
    //         $this->batches = [];
    //         return;
    //     }

    //     $this->batches = AcademicSession::where('is_active', 1)
    //         ->where('name', $academicSession->name)
    //         ->pluck('batch_name', 'id')
    //         ->toArray();
    // }
    public function addPlanner()
    {
        $this->planners[] = $this->blankPlanner();
    }

    public function removePlanner($index)
    {
        unset($this->planners[$index]);
        $this->planners = array_values($this->planners);
    }

    public function render()
    {
        return view('livewire.edit-planner-page');
    }
}
