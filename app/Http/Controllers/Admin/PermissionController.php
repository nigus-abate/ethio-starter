<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{

    public function __construct() {
        $this->middleware('can:view permissions')->only('index', 'show');
        $this->middleware('can:create permissions')->only('create', 'store');
        $this->middleware('can:edit permissions')->only('edit', 'update');
        $this->middleware('can:delete permissions')->only('destroy');
    }
    
    public function index()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
            'guard_name' => 'web'
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update([
            'name' => $request->name,
            'group' => $request->group
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->is_protected) {
            return redirect()->back()
            ->with('error', 'Protected permissions cannot be deleted.');
        }
        $permission->delete();
        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}