<?php

namespace App\Livewire;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ErpEditTeacher extends Component
{
    public $classes;
    public $subjects;
    public $datalist;
    public $editingId = null;
    public $editData = [];
    public $selectedClasses = [];
    public $selectedSubjects = [];

    public $currentPage = 1;
    public $perPage = 10;
    public $totalPages;
    public $paginatedData = [];

    public function mount($datalist)
    {
        $this->datalist = collect($datalist);
        $this->classes = SchoolClass::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
        $this->subjects = Subject::where('is_active', 1)->pluck('name', 'id');
		$this->perPage  = Session::get('per_page_records', config('constants.PAGINATION.default'));

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
        $this->editData = [
            'name' => $item->name ?? '',
            'mobile' => $item->mobile ?? '',
            'password' => $item->password ?? ''
        ];

        $this->dispatch('initSelect2');
    }

    public function cancel()
    {
        $this->editingId = null;
        $this->editData = [];
        $this->selectedClasses = [];
        $this->selectedSubjects = [];
    }

    public function render()
    {
        return view('livewire.erp-edit-teacher', [
            'teachers' => $this->paginatedData,
            'subjects' => $this->subjects,
            'classes' => $this->classes,
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
        ]);
    }
}