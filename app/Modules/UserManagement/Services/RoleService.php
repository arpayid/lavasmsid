<?php

namespace App\Modules\UserManagement\Services;

use App\Core\Shared\DataTableQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Query DataTable dengan search, filter, pagination.
     */
    public function queryDataTable(Request $request): LengthAwarePaginator
    {
        $query = Role::withCount('users');

        return DataTableQuery::make($query)
            ->search(['name'], $request->input('search'))
            ->orderBy('name', 'asc')
            ->paginate($request->input('per_page', 15));
    }

    /**
     * Create new role.
     */
    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $permissions = $data['permissions'] ?? [];
            unset($data['permissions']);

            $role = Role::create($data);
            $role->syncPermissions($permissions);

            return $role;
        });
    }

    /**
     * Update role.
     */
    public function update(string $name, array $data): Role
    {
        return DB::transaction(function () use ($name, $data) {
            $role = Role::findByName($name);

            if (isset($data['name']) && $data['name'] !== $name) {
                $role->name = $data['name'];
                $role->guard_name = $data['guard_name'] ?? 'web';
                $role->save();
            }

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role->fresh();
        });
    }

    /**
     * Delete role.
     */
    public function delete(string $name): bool
    {
        $role = Role::findByName($name);

        return DB::transaction(function () use ($role) {
            $role->syncPermissions([]);

            return $role->delete();
        });
    }

    /**
     * Find role by name.
     */
    public function findByName(string $name): ?Role
    {
        return Role::with('permissions')->findByName($name);
    }

    /**
     * Get all permissions grouped by module.
     */
    public function getAllPermissionsGrouped(): Collection
    {
        return Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);

            return $parts[0] ?? 'general';
        });
    }

    /**
     * Get all permissions.
     */
    public function getAllPermissions(): Collection
    {
        return Permission::orderBy('name')->get();
    }
}
