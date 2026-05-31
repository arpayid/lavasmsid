<x-admin-layout heading="Profil Saya">
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Profile Info --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                <h2 class="mb-6 text-lg font-semibold text-slate-900">Informasi Profil</h2>

                <div class="space-y-5">
                    <x-admin.form-input name="name" label="Nama Lengkap" :value="$user->name" required />
                    <x-admin.form-input name="email" label="Email" type="email" :value="$user->email" required />
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </a>
                </div>
            </form>

            {{-- Change Password --}}
            <form method="POST" action="{{ route('admin.profile.password') }}" class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                @method('PUT')
                <h2 class="mb-6 text-lg font-semibold text-slate-900">Ubah Password</h2>

                <div class="space-y-5">
                    <x-admin.form-input name="current_password" label="Password Saat Ini" type="password" required />
                    <x-admin.form-input name="password" label="Password Baru" type="password" required />
                    <x-admin.form-input name="password_confirmation" label="Konfirmasi Password Baru" type="password" required />
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Avatar --}}
        <div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Foto Profil</h2>
                <div class="flex flex-col items-center">
                    <img class="h-28 w-28 rounded-full object-cover border-4 border-slate-100"
                         src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('storage/avatars/default.png') }}"
                         alt="{{ $user->name }}">
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="mt-4 w-full">
                        @csrf
                        @method('PUT')
                        <x-admin.form-input name="avatar" label="Upload Foto Baru" type="file" accept="image/*" />
                        <button type="submit" class="mt-3 w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                            Upload
                        </button>
                    </form>
                </div>
            </div>

            {{-- User Info Card --}}
            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Detail Akun</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Role</dt>
                        <dd class="font-medium text-slate-900">{{ $user->roles->first()->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Terdaftar</dt>
                        <dd class="font-medium text-slate-900">{{ $user->created_at?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Login Terakhir</dt>
                        <dd class="font-medium text-slate-900">{{ $user->last_login_at?->diffForHumans() ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin-layout>
