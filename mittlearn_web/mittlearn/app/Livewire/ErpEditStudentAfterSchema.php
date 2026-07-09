<?php

namespace App\Livewire;

use App\Models\StudentDetails;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

class ErpEditStudentAfterSchema extends Component
{
    use WithPagination;
    public $datalist;
    public $classes;
    public $editingId = null;
    public $editData = [];
    public $selectedClass = null;

    public function mount($datalist, $classes)
    {
        $this->datalist = $datalist;
        $this->classes = $classes;
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $item = $this->datalist->firstWhere('id', $id);
        $className = DB::connection('erp')
            ->table('class')
            ->where('id', $item->classid)
            ->value('name');
        $user = DB::connection('erp')
            ->table('all_user')
            ->where('name', $item->addNumber)
            ->select('name', 'password')
            ->first();
        $this->editData = [
            'name' => $item->fname ?? '',
            'mobile' => $item->fathersPhone ?? '',
            'username' => $user->name ?? '',
            'password' => $user->password ?? '',
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
        // Debug
        // dd($this->editData, $this->editingId, $this->selectedClass);

        if ($this->editingId) {
            $schoolId = User::where('erp_schid', $this->editData['schid'])->value('id') ?? null;
            $userId = User::where('erp_db_id', $this->editingId)->value('id') ?? null;
        }

        $this->validate([
            'editData.name' => 'required|string',
            'editData.mobile' => 'required|string',
            'editData.password' => 'required|string',
            'selectedClass' => 'required',
        ]);

        try {
            DB::beginTransaction(); // START TRANSACTION

            $user = User::updateOrCreate(
                ['id' => $userId],
                [
                    'name'               => $this->editData['name'],
                    'username'           => $this->editData['name'],
                    'email'              => $this->editData['email'] ?? null,
                    'mobile_no'          => $this->editData['mobile'],
                    'created_by'         => Auth::id(),
                    'password'           => Hash::make($this->editData['password']) ?? Hash::make('Mitt@123'),
                    'validate_string'    => $this->editData['password'] ?? 'Mitt@123',
                    'is_email_verified'  => 1,
                    'is_mobile_verified' => 1,
                    'is_from_erp'        => '1',
                    'erp_db_id'          => $this->editingId,
                ]
            );

            if (!$user) {
                DB::rollBack(); // ROLLBACK
                return session()->flash('error', config('constants.API_MSG.REC_ADD_FAILED'));
            }

            $userrole = UserRole::updateOrCreate(
                ['user_id' => $user->id],
                ['role_slug' => 'school_student']
            );

            if (!$userrole) {
                DB::rollBack();
                return session()->flash('error', config('constants.API_MSG.REC_ADD_FAILED'));
            }

            $studentdetail = StudentDetails::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id'   => $user->id,
                    'parent_id' => Auth::id(),
                    'school_id' => $schoolId,
                    'class'     => $this->selectedClass,
                ]
            );

            if (!$studentdetail) {
                DB::rollBack();
                return session()->flash('error', config('constants.API_MSG.REC_ADD_FAILED'));
            }

            $user_additional_detail = UserAdditionalDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'role'      => 'school_student',
                    'school_id' => $schoolId,
                    'user_id'   => $user->id,
                ]
            );

            if (!$user_additional_detail) {
                DB::rollBack();
                return session()->flash('error', config('constants.API_MSG.REC_ADD_FAILED'));
            }

            DB::commit(); // COMMIT if all went well
            return session()->flash('success', config('constants.FLASH_REC_UPDATE_1'));
        } catch (\Exception $e) {
            DB::rollBack(); // rollback on exception
            return session()->flash('error', config('constants.FLASH_TRY_CATCH'));
        }
    }


    public function render()
    {
        $page = request()->get('page', 1);
        $perPage = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $items = collect($this->datalist);


        $paginatedItems = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('livewire.erp-edit-student', [
            'students' => $paginatedItems
        ]);
    }
}
