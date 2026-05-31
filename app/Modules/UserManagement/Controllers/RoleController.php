<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\UserManagement\Requests\StoreRoleRequest;
use App\Modules\UserManagement\Requests\UpdateRoleRequest;
use App\Modules\UserManagement\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
    ) {}

    public function index(Request $request): View
    {
        $roles = $this->roleService->queryDataTable($request);

        return view('modules.user-management.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function create(): View
    {
        $permissions = $this->roleService->getAllPermissionsGrouped();

        return view('modules.user-management.roles.create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->roleService->create($request->validated());

        return redirect()
            ->route('admin.user-management.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(string $name): View
    {
        $role = $this->roleService->findByName($name);

        if (! $role) {
            abort(404);
        }

        $permissions = $this->roleService->getAllPermissionsGrouped();

        return view('modules.user-management.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, string $name): RedirectResponse
    {
        $this->roleService->update($name, $request->validated());

        return redirect()
            ->route('admin.user-management.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $name): RedirectResponse
    {
        $this->roleService->delete($name);

        return redirect()
            ->route('admin.user-management.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
