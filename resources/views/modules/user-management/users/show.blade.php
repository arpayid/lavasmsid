<x-admin-layout heading="Detail User">
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Info --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Informasi User</h2>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">Nama</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Email</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Telepon</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $user->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Status</dt>
                        <dd class="mt-1"><x-admin.badge :label="$user->is_active ? 'Aktif' : 'Nonaktif'" :variant="$user->is_active ? 'success' : 'danger'" /></dd>
                    </div>
                </dl>
            </div>

            {{-- Roles --}}
            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Role & Permission</h2>
                @if($user->roles->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <x-admin.badge :label="$role->name" variant="info" />
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-500">Belum ada role yang ditetapkan.</p>
                @endif
            </div>
        </div>

        {{-- Avatar + Actions --}}
        <div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col items-center">
                    <img class="h-28 w-28 rounded-full object-cover border-4 border-slate-100"
                         src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('storage/avatars/default.png') }}" alt="">
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-2">
                @can('user.update')
                <a href="{{ route('admin.user-management.users.edit', $user) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Edit User
                </a>
                @endcan
                <a href="{{ route('admin.user-management.users.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
