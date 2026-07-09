<?php

namespace App\Livewire;

use App\Models\StudentDetails;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ErpEditStudent extends Component
{
    public $datalist;
    public $classes;
    public $editingId = null;
    public $editData = [];
    public $selectedClass = null;

    public $currentPage = 1;
    public $perPage;
    public $totalPages;
    public $paginatedData = [];

    public function mount($datalist, $classes)
    {
        $this->datalist = collect($datalist);
        $this->classes = $classes;
        $this->perPage = Session::get('per_page_records', config('constants.PAGINATION.default', 20));

        $this->updatePagination();
    }

    public function updatePagination()
    {
        $totalItems = $this->datalist->count();
        $this->totalPages = ceil($totalItems / $this->perPage);
        $this->paginatedData = $this->datalist
            ->slice(($this->currentPage - 1) * $this->perPage, $this->perPage)
            ->values();
    }

    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
            $this->updatePagination();
        }
    }

    public function nextPage()
    {
        $this->goToPage($this->currentPage + 1);
    }

    public function previousPage()
    {
        $this->goToPage($this->currentPage - 1);
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $item = $this->datalist->firstWhere('id', $id);

        $user = DB::connection('erp')->table('all_user')->where('name', $item->addNumber)->first();

        $this->editData = [
            'name' => $item->fname ?? '',
            'mobile' => $item->fathersPhone ?? '',
            'username' => $user->name ?? '',
            'password' => $user->password ?? '',
            'currentAddress' => $user->currentAddress ?? '',
            'fathersname' => $user->fathersname ?? '',
            'schid' => $item->schid ?? '',
        ];

        $this->selectedClass = $item->class_id ?? null;
    }

    public function cancel()
    {
        $this->editingId = null;
        $this->editData = [];
        $this->selectedClass = null;
    }

    public function update()
    {
        $this->validate([
            'editData.name' => 'required',
            'editData.username' => 'required',
            'editData.mobile' => 'required',
            'editData.password' => 'required',
            'selectedClass' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $schoolId = User::where('erp_schid', $this->editData['schid'])->value('id');
            $userId = User::where('erp_db_id', $this->editingId)->value('id');

            $user = User::updateOrCreate(
                ['id' => $userId],
                [
                    'name' => $this->editData['name'],
                    'username' => $this->editData['username'],
                    'email' => $this->editData['email'] ?? null,
                    'mobile_no' => $this->editData['mobile'],
                    'password' => Hash::make($this->editData['password']),
                    'validate_string' => $this->editData['password'],
                    'created_by' => Auth::id(),
                    'is_email_verified' => 1,
                    'is_mobile_verified' => 1,
                    'is_from_erp' => 1,
                    'erp_db_id' => $this->editingId,
                    'source' => 'from-erp',
                ]
            );

            UserRole::updateOrCreate(['user_id' => $user->id], ['role_slug' => 'school_student']);

            StudentDetails::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'parent_id' => Auth::id(),
                    'school_id' => $schoolId,
                    'class' => $this->selectedClass,
                    'address' => $this->editData['currentAddress'],
                    'parent_name' => $this->editData['fathersname'],
                ]
            );

            UserAdditionalDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'role' => 'school_student',
                    'school_id' => $schoolId,
                    'user_id' => $user->id,
                ]
            );

            DB::commit();
            session()->flash('success', config('constants.FLASH_REC_UPDATE_1'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', config('constants.FLASH_TRY_CATCH'));
        }
    }

    public function render()
    {
        return view('livewire.erp-edit-student', [
            'students' => $this->paginatedData,
            'perPage' => $this->perPage,
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
        ]);
    }
}
