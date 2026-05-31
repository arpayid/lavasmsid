<?php

namespace App\Modules\UserManagement\Services;

use App\Models\User;
use App\Core\Shared\DataTableQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Query DataTable dengan search, filter, pagination.
     */
    public function queryDataTable(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::with(['roles']);

        return DataTableQuery::make($query)
            ->search(['name', 'email', 'phone'], $request->input('search'))
            ->filter('is_active', $request->input('is_active'))
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));
    }

    /**
     * Create new user.
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            $data['password'] = Hash::make($data['password'] ?? 'password');

            $user = User::create($data);
            $user->syncRoles($roles);

            return $user;
        });
    }

    /**
     * Update user.
     */
    public function update(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = User::findOrFail($id);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
                unset($data['roles']);
            }

            $user->update($data);

            return $user;
        });
    }

    /**
     * Delete user (soft delete).
     */
    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);

        return DB::transaction(function () use ($user) {
            $user->syncRoles([]);
            return $user->delete();
        });
    }

    /**
     * Find user with relations.
     */
    public function findWithRelations(int $id): ?User
    {
        return User::with(['roles.permissions'])->find($id);
    }

    /**
     * Get all roles.
     */
    public function getAllRoles(): Collection
    {
        return Role::orderBy('name')->get();
    }
}
