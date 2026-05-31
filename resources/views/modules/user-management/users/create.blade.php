<x-admin-layout heading="Tambah User">
    <form method="POST" action="{{ route('admin.user-management.users.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <h2 class="mb-6 text-lg font-semibold text-slate-900">Informasi User</h2>

        <div class="space-y-5">
            <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name')" required />
            <x-admin.form-input name="email" label="Email" type="email" :value="old('email')" required />
            <x-admin.form-input name="phone" label="No. Telepon" :value="old('phone')" />
            <x-admin.form-input name="password" label="Password" type="password" />
            <x-admin.form-input name="password_confirmation" label="Konfirmasi Password" type="password" />

            {{-- Roles --}}
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-700">Role <span class="text-red-500">*</span></label>
                <div class="mt-2 max-h-48 overflow-y-auto rounded-lg border border-slate-300 p-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-2 py-1">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(collect(old('roles', []))->contains($role->name))>
                        <span class="text-sm text-slate-700">{{ $role->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('roles')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- is_active --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active', true))>
                <label for="is_active" class="text-sm text-slate-700">User Aktif</label>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                Simpan
            </button>
            <a href="{{ route('admin.user-management.users.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
</x-admin-layout>
