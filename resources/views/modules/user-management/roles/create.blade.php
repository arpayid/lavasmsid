<x-admin-layout heading="Tambah Role">
    <form method="POST" action="{{ route('admin.user-management.roles.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <h2 class="mb-6 text-lg font-semibold text-slate-900">Informasi Role</h2>

        <div class="space-y-5">
            <x-admin.form-input name="name" label="Nama Role" :value="old('name')" required placeholder="contoh: Guru, Bendahara, Panitia PPDB" />

            {{-- Permissions --}}
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-700">Permission</label>
                <div class="mt-2 max-h-96 overflow-y-auto rounded-lg border border-slate-300 p-4">
                    @foreach($permissions as $module => $perms)
                    <div class="mb-3">
                        <h4 class="mb-1 font-semibold text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $module) }}</h4>
                        <div class="ml-2 space-y-1">
                            @foreach($perms as $perm)
                            <label class="flex items-center gap-2 py-0.5">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(collect(old('permissions', []))->contains($perm->name))>
                                <span class="text-sm text-slate-600">{{ $perm->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('permissions')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                Simpan
            </button>
            <a href="{{ route('admin.user-management.roles.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
</x-admin-layout>
