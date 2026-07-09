<?php
namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    private array $data = [];

    public function __construct()
    {}

    public function index(Request $request): View
    {
        $page                = $request->get('page', 1);
        $this->data['roles'] = Role::orderBy('id', 'DESC')->paginate(10);
        return view('admin.roles.index', $this->data)->with('i', ($page - 1) * 5);
    }

    public function create(): View
    {
        $this->data['permissions'] = Permission::all();
        return view('admin.roles.create', $this->data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }
        // Check if role exists and has associated users
        if ($request->id) {
            $role = Role::with('users')->find($request->id);
            if ($role && $request->is_active == 0 && $role->users()->exists()) {
                return redirect()->back()->withErrors(['error' => 'This role has associated users. Please relook before deactivating.']);
            }
        }

        $roleSlug = Role::where('id', $request->id)->select('role_slug')->first();
        $slug     = isset($request->id) && $roleSlug ? $roleSlug->role_slug : generateUniqueSlug($request->role_name, Role::class, 'role_slug', $request->id);

        $res = Role::updateOrCreate(['id' => $request->id], ['role_name' => $request->role_name, 'is_active' => $request->is_active, 'description' => $request->description, 'role_slug' => $slug]);
        if ($res) {
            return redirect()->route('roles.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }

    public function show(int $id): View
    {
        $this->data['role'] = Role::with('permissions')->findOrFail($id);
        return view('admin.roles.show', $this->data);
    }

    public function edit(int $id): View
    {
        $this->data['role'] = Role::findOrFail($id);
        return view('admin.roles.create', $this->data);
    }

    public function update(RoleRequest $request, int $id): RedirectResponse
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());
        return redirect()->route('roles.index')->with('success', __('Role updated successfully!'));
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        // Check if the role has associated users
        $associatedUsers = UserRole::where('role_slug', $role->role_slug)->count();

        if ($associatedUsers > 0) {
            return response()->json([
                'success' => false,
                'message' => 'This role is assigned to users. Please delete the associated users first.',
            ]);
        }

        // Proceed to delete the role
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully!',
        ]);
    }
}
