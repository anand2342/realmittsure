<?php

namespace App\Http\Controllers\admin\roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\LoginAsUserLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PermissionController extends Controller
{
    private array $data = [];

    public function __construct() {}

    public function index(Request $request): View
    {
        $page                      = $request->get('page', 1);
        $this->data['permissions'] = Permission::orderBy('id', 'DESC')->get();
        return view('admin.permissions.index', $this->data)->with('i', ($page - 1) * 5);
    }

    public function create(): View
    {
        $this->data['permissions'] = Permission::get();
        return view('admin.permissions.create', $this->data);
    }
    public function store(PermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated());

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }
    public function show($id): View
    {
        $this->data['permission'] = Permission::findOrFail($id);
        return view('admin.permissions.show', $this->data);
    }

    public function edit($id): View
    {
        $this->data['permission'] = Permission::findOrFail($id);
        return view('admin.permissions.edit', $this->data);
    }

    public function update(PermissionRequest $request, $id): RedirectResponse
    {
        $permission = Permission::findOrFail($id);
        $permission->update($request->validated());

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        Permission::findOrFail($id)->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }

    public function assignPermissions(): View
    {
        $permissions = Permission::where('status', 1)->select('id', 'parent_id', 'slug', 'title', 'description', 'category')->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id'          => $item->id,
                        'parent_id'   => $item->parent_id,
                        'slug'        => $item->slug,
                        'title'       => $item->title,
                        'description' => $item->description,
                    ];
                })->toArray();
            })
            ->toArray();
        $this->data['permissions'] = $permissions;
        return view('admin.permissions.assign', $this->data);
    }
    public function saveAssigndPermissions(Request $request)
    {
        $rolePermissionArr = $userPermissionArr = [];
        if ($request->assign_to == 'role' && $request->role_id) {
            RolePermission::whereRoleId($request->role_id)->delete();
        }
        if ($request->assign_to == 'user' && $request->user_id) {
            UserPermission::whereUserId($request->user_id)->delete();
        }

        foreach ($request->permissions as $k => $val) {
            if ($val == 'on') {
                if ($request->assign_to == 'role' && $request->role_id) {
                    $rolePermissionArr[] = ['role_id' => $request->role_id, 'permission_id' => $k];
                }
                if ($request->assign_to == 'user' && $request->user_id) {
                    $userPermissionArr[] = ['user_id' => $request->user_id, 'permission_id' => $k];
                }
            }
        }

        if (count($rolePermissionArr)) {
            RolePermission::insert($rolePermissionArr);
        }
        if (count($userPermissionArr)) {
            UserPermission::insert($userPermissionArr);
        }
        return redirect()->back()->with('success', config('constants.FLASH_REC_UPDATE_1'));
    }

    public function assignToRole(Request $request): RedirectResponse
    {
        $request->validate([
            'role_id'          => 'required|exists:roles,id',
            'permission_ids'   => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permission_ids);

        return redirect()->back()->with('success', 'Permissions assigned to role successfully.');
    }
    public function assignToUser(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'permission_ids'   => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->permissions()->sync($request->permission_ids); // Syncing permissions

        return redirect()->back()->with('success', 'Permissions assigned to user successfully.');
    }

    //by ashmit
    public function addPermission(Request $request)
    {
        $permissionCategory = Permission::pluck('category', 'category')->toArray();
        return view('admin.permissions.addPermission', ['data' => $permissionCategory]);
    }

    public function allPermissions(Request $request)
    {
        $data = Permission::paginate(10);
        return view('admin.permissions.allPermission', ['data' => $data]);
    }
    public function newPermissionSave(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'category'        => 'required',
            'slug'            => "required|unique:permissions,slug,{$request->id}",
            'permission_type' => 'required',
            'accessable_for'  => 'required',
            'title'           => 'required',
        ]);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }
        $res = Permission::updateOrCreate(
            ['id' => $request->id],
            [
                'category'        => $request->category,
                'slug'            => $request->slug,
                'permission_type' => $request->permission_type,
                'accessable_for'  => $request->accessable_for,
                'title'           => $request->title,
                'description'     => $request->description,
            ]
        );
        if ($res) {
            return redirect()->route('permissions.add')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }

    public function permissionDelete($id)
    {
        $data = Permission::where('id', $id)->first();
        $data->delete();
        return redirect()->route('permissions.all')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }

    public function editPermission($id)
    {
        $data = Permission::where('id', $id)->first();
        return view('admin.permissions.addPermission', ['data' => $data]);
    }

    public function loginAsUser($id, Request $request)
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->back()->withErrors('User not found.');
        }

        // Save current admin ID in session
        Session::put('admin_id', Auth::id());

        // Log in as the user
        Auth::login($user);

        $routeAction  = $request->route()->action;
        $actionMethod = $request->route()->methods[0];
        $params       = null;
        if ($actionMethod == 'POST' || $actionMethod == 'PUT') {
            $params = json_encode($request->all());
        }
        $where = [
            "user_id"   => Auth::user()->id,
            "action_as" => $routeAction['as'],
            "method"    => "GET",
            "log_date"  => date('Y-m-d'),
        ];
        $logData = [
            "user_id"    => Auth::user()->id,
            "uri"        => $request->route()->uri,
            "action_as"  => $routeAction['as'],
            "controller" => $routeAction['controller'],
            "method"     => $actionMethod,
            "json_data"  => $params,
            "log_date"   => date('Y-m-d'),
        ];

        LoginAsUserLog::updateOrCreate($where, $logData);

        return redirect()->route('sp.dashboard')->with('success', 'Logged in as ' . $user->name);
    }
    public function backToAdmin()
    {
        $adminId = Session::get('admin_id');

        if (! $adminId) {
            return redirect()->route('login')->withErrors('Session expired. Please log in again.');
        }

        // Log in as the admin
        $admin = User::find($adminId);

        if (! $admin) {
            return redirect()->route('login')->withErrors('Admin not found.');
        }

        Auth::login($admin);

        // Remove admin_id from session
        Session::forget('admin_id');
        $parentSchool = Session::get('parent_school_id');
        if ($parentSchool) {
            Session::forget('parent_school_id');
        }
        // return redirect()->route('dashboard')->with('success', 'Returned to admin dashboard.');
        return redirect('/admin/user/index?role=school_admin')->with('success', 'Returned to admin dashboard.');
    }
}
