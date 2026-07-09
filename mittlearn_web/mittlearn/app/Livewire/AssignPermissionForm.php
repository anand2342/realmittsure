<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AssignPermissionForm extends Component
{
    public $permissions = null;
    public $rolesList = [];
    public $categoryList = [];
    public $usersList = [];
    public $assignedPermissions = [];
    public $filterPermissionGroup = null;
    public $filterPermissionName = '';
    public $filterPermissionAccessible = null;

    public $isClearPermissionFilters = 'yes';
    public $selectedAssignTo = 'role';
    public $selectedRole = '';
    public $selectedRoleId = '';
    public $selectedUserId = '';
    public $selectedCategoryId = '';
    public $isVisibleAllPermission = false;

    public function mount($permissions)
    {
        $this->permissions = $permissions;
        $this->rolesList = getRoles();
        $this->categoryList = Category::where('status', 1)->where('parent_id', 1)
            ->whereNotIn('slug', ['academic-digital-content', 'academic_activities'])
            ->pluck('name', 'id')
            ->toArray();
        $this->getPermissionList();
    }

    public function handleAssignToTypeChange($value)
    {
        $this->selectedAssignTo = $value;
        $this->selectedRole = '';
        $this->selectedRoleId = '';
        $this->selectedUserId = '';
        $this->selectedCategoryId = '';
        $this->usersList = [];

        Log::info('handleAssignToTypeChange::', [$value]);
    }

    public function handleChangeRole($value)
    {
        $roleData = Role::find($value);
        if ($roleData) {
            $this->selectedRoleId = $roleData->id;
            $this->selectedRole = $roleData->role_slug;
            if ($this->selectedAssignTo == 'user') {
                if ($this->selectedRole == 'd2c_user') {
                    $this->usersList = [];
                } else {
                    $this->getUsersByRole();
                }
            }
            $this->getAssignedPermissions();

            $this->isVisibleAllPermission = true;
        }
    }

    public function handleChangeCategory($value)
    {
        $this->selectedCategoryId = $value;

        if ($this->selectedAssignTo == 'user' && $this->selectedRole == 'd2c_user') {
            $this->getUsersByCategory();
        }
    }

    public function handleChangeUser($value)
    {
        $this->selectedUserId = $value;
        $this->getAssignedPermissions();
    }


    public function handleFilterPermissions($value, $fieldName)
    {
        if ($fieldName == 'clear') {
            $this->isClearPermissionFilters = 'yes';
            $this->filterPermissionGroup = null;
            $this->filterPermissionName = '';
            $this->filterPermissionAccessible = null;
        } else {
            $this->isClearPermissionFilters = 'no';
            if ($fieldName == 'permission_group')
                $this->filterPermissionGroup = $value ?? null;
            if ($fieldName == 'permission_name')
                $this->filterPermissionName = $value ?? null;
            if ($fieldName == 'accessable_for') {
                $this->filterPermissionAccessible = $value ?? null;
            }
        }
        $this->getPermissionList();
    }
    public function render()
    {
        return view('livewire.assign-permission-form');
    }

    function getUsersByRole()
    {
        $users = UserRole::with('user')
            ->whereRoleSlug($this->selectedRole)
            ->whereHas('user')
            ->get()
            ->pluck('user.name', 'user.id');

        $this->usersList = $users ?? [];
    }

    function getUsersByCategory()
    {
        $d2cUserIds = UserRole::where('role_slug', 'd2c_user')
            ->pluck('user_id')
            ->toArray();

        $users = User::whereIn('id', $d2cUserIds)
            ->where('category_id', $this->selectedCategoryId)
            ->pluck('name', 'id');

        $this->usersList = $users ?? [];
    }


    function getAssignedPermissions()
    {
        $this->assignedPermissions = getAssignedPermissionsByRoleUser($this->selectedRoleId, $this->selectedUserId);
    }
    function getPermissionList()
    {
        $permissionQuery = Permission::where('status', 1)->select('id', 'parent_id', 'slug', 'title', 'description', 'category');
        if ($this->filterPermissionGroup) {
            $permissionQuery->where('category', $this->filterPermissionGroup);
        }
        if ($this->filterPermissionAccessible) {
            $permissionQuery->where('accessable_for', $this->filterPermissionAccessible);
        }
        if ($this->filterPermissionName) {
            $permissionQuery->where('title', 'LIKE', '%' . $this->filterPermissionName . '%');
        }
        $pData = $permissionQuery->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'parent_id' => $item->parent_id,
                        'slug'      => $item->slug,
                        'title'     => $item->title,
                        'description' => $item->description,
                    ];
                })->toArray();
            })
            ->toArray();

        $this->permissions = $pData;
    }
}
