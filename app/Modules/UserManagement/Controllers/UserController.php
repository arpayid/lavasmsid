<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\UserManagement\Services\UserService;
use App\Modules\UserManagement\Requests\StoreUserRequest;
use App\Modules\UserManagement\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function index(Request $request): View
    {
        $users = $this->userService->queryDataTable($request);

        return view('modules.user-management.users.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        $roles = $this->userService->getAllRoles();

        return view('modules.user-management.users.create', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()
            ->route('admin.user-management.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $user = $this->userService->findWithRelations($id);

        if (!$user) {
            abort(404);
        }

        return view('modules.user-management.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(int $id): View
    {
        $user = $this->userService->findWithRelations($id);

        if (!$user) {
            abort(404);
        }

        $roles = $this->userService->getAllRoles();

        return view('modules.user-management.users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $this->userService->update($id, $request->validated());

        return redirect()
            ->route('admin.user-management.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->userService->delete($id);

        return redirect()
            ->route('admin.user-management.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
