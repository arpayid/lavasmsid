<?php

namespace App\Modules\UserManagement\Services;

use App\Core\Shared\DataTableQuery;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function queryDataTable(Request $request): LengthAwarePaginator
    {
        $query = User::with(['roles']);

        return DataTableQuery::make($query)
            ->search(['name', 'email', 'phone'], $request->input('search'))
            ->filter('is_active', $request->input('is_active'))
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $user->syncRoles($roles);

            return $user;
        });
    }

    public function update(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = User::findOrFail($id);
            $authUser = request()->user();

            // Prevent non-super-admin from modifying Super Admin
            if ($user->hasRole('Super Admin') && ! $authUser->hasRole('Super Admin')) {
                abort(403, 'Only Super Admin can modify Super Admin users.');
            }

            if (! empty($data['password'])) {
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

    public function delete(int $id): bool
    {
        $authUser = request()->user();

        // Prevent self-deletion
        if ($authUser && $authUser->id === $id) {
            abort(403, 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);

        return DB::transaction(function () use ($user) {
            $user->syncRoles([]);

            return $user->delete();
        });
    }

    public function findWithRelations(int $id): ?User
    {
        return User::with(['roles.permissions'])->find($id);
    }

    public function getAllRoles(): Collection
    {
        return Role::orderBy('name')->get();
    }
}
