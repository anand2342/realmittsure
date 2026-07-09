<?php

namespace App\Livewire;

use App\Models\AccessCode;
use App\Models\AccessCodeLog;
use Livewire\Component;

class AssignAccessCode extends Component
{
    public $selectedCodes = [];
    public $selectedUser = [];
    public $selectAll = false;

    public $totalAccessCodes;
    public $remainingAccessCodes;
    public $users;

    public $isOpenModal = false;

    public function mount($remainingAccessCodes, $users)
    {

        $this->remainingAccessCodes = $remainingAccessCodes;
        $this->users = $users;
        $this->totalAccessCodes = count($remainingAccessCodes);
    }

    public function openModel()
    {
        $this->isOpenModal = true;
    }


    public function assignAccessCode()
    {
        try {
            foreach ($this->selectedCodes as $selectedCode) {
                $selectedCode = unserialize($selectedCode);
                $codeId = $selectedCode['code_id'];
                $userId = $selectedCode['user_id'];
                $code = collect($this->remainingAccessCodes)->firstWhere('id', $codeId);
                $user = collect($this->users)->firstWhere('id', $userId);

                if ($code && $user) {
                    $accessCode = AccessCode::where('access_code', $code['access_code'])->first();

                    if ($accessCode) {
                        $accessCode->update([
                            'user_id' => $user['id'],
                        ]);
                    }

                    $accessCodeLog = AccessCodeLog::where('user_id', $user['id'])->first();
                    if (!$accessCodeLog) {
                        AccessCodeLog::create([
                            'user_id' => $user['id'],
                            'title' => 'Access Code Activated',
                            'action_as' => 'user_access_code_activated_by_school',
                            'action_by' => auth()->id(),
                            'json_data' => json_encode([$accessCode]),
                        ]);
                    }
                }
            }

            $this->selectedCodes = [];
            $this->selectAll = false;
            $this->isOpenModal = false;

            return redirect()->to(request()->header('Referer'))->with(['success' => config('constants.FLASH_ASSIGN_CODE_USER')]);
        } catch (\Exception $e) {
            return redirect()->to(request()->header('Referer'))->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function closeModal()
    {
        $this->isOpenModal = false;
    }

    // public function updatedSelectAll($value)
    // {
    //     if ($value) {
    //         $this->selectedCodes = collect($this->remainingAccessCodes)->pluck('id')->toArray();
    //     } else {
    //         $this->selectedCodes = [];
    //     }
    // }

    // public function updatedSelectedCodes()
    // {
    //     $this->selectAll = count($this->selectedCodes) === count($this->remainingAccessCodes);
    // }

    public function render()
    {
        return view('livewire.assign-access-code');
    }
}
