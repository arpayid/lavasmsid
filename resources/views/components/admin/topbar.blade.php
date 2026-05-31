{{-- Topbar: fixed height, user profile dropdown --}}
<header class="border-b border-slate-200 bg-white">
    <div class="flex items-center justify-between px-6 py-4">
        {{-- Page actions --}}
        <div class="flex items-center gap-3">
            {{-- Notifications --}}
            <div class="relative">
                <button @click="notificationsOpen = !notificationsOpen"
                        class="relative flex items-center justify-center h-10 w-10 rounded-md text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        x-data="{ notificationsOpen: false }">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.023-.595 1.436L4 17h5m6 0v1a3 3 0 11-3 3H6a3 3 0 01-3-3V6a3 3 0 003-3h6a3 3 0 013 3v6z"/></svg>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white" x-show="false">5</span>
                </button>
                {{-- Notification dropdown --}}
                <div x-show="notificationsOpen" x-cloak x-placement="bottom-end"
                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 pb-2">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdown-button">
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" role="menuitem">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H3m8 4H3m-4-4h12m-8 4h12M3 12l9-9 9 9"/></svg>
                                <div>
                                    <div class="font-medium">Notifikasi baru</div>
                                    <div class="text-xs text-slate-500">2 menit yang lalu</div>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" role="menuitem">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <div class="font-medium">Pembayaran SPP</div>
                                    <div class="text-xs text-slate-500">50 tagihan belum lunas</div>
                                </div>
                            </div>
                        </a>
                        <div class="-mx-3 my-2 px-3">
                            <a href="{{ Route::has('admin.notifications.index') ? route('admin.notifications.index') : '#' }}" class="block w-full text-center px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                                Lihat semua notifikasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="hidden lg:flex lg:items-center lg:gap-3">
                <button @click="quickActionsOpen = !quickActionsOpen"
                        class="relative flex items-center justify-center h-10 w-10 rounded-md text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        x-data="{ quickActionsOpen: false }">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4 2 2 0 000-4 2 2 0 100 4 2 2 0 100-4 2 2 0 100-4 2 2 0 100-4 2z"/></svg>
                </button>
                {{-- Quick actions dropdown --}}
                <div x-show="quickActionsOpen" x-cloak x-placement="bottom-end"
                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 pb-2">
                    <div class="py-1">
                        <a href="{{ route('admin.students.create') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                            Tambah Siswa
                        </a>
                        <a href="{{ route('admin.teachers.create') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                            Tambah Guru
                        </a>
                        <a href="{{ Route::has('admin.finance.invoices.create') ? route('admin.finance.invoices.create') : '#' }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                            Buat Tagihan
                        </a>
                        <a href="{{ Route::has('admin.attendance.today') ? route('admin.attendance.today') : '#' }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                            Absensi Hari Ini
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- User profile --}}
        <div class="hidden lg:flex lg:items-center lg:gap-4">
            <img class="h-10 w-10 rounded-full object-cover border-2 border-white"
                 src="{{ auth()->user()->avatar_path ?? asset('storage/avatars/default.png') }}"
                 alt="Profile">
            <div class="hidden lg:block">
                <div class="text-slate-900 font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-500 capitalize">{{ auth()->user()->roles->first()->name ?? '' }}</div>
            </div>
        </div>
        <button @click="userMenuOpen = !userMenuOpen"
                class="lg:hidden flex h-10 w-10 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                x-data="{ userMenuOpen: false }">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c9.504 0 14.847-5.003 14.847-11.201a1 1 0 11-1.96.28A11.916 11.916 0 0012 4.347c-5.174 0-8.971 3.467-9.77 6.65A1 1 0 108.15 14.466a11.937 11.937 0 00-3.029 3.345z"/></svg>
        </button>
        {{-- User menu dropdown --}}
        <div x-show="userMenuOpen" x-cloak x-placement="bottom-end"
             class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdown-button">
                <a href="{{ Route::has('admin.profile.edit') ? route('admin.profile.edit') : '#' }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" role="menuitem">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536M12 15l-3 3m0 0-3-3m3 3V9m0 4h.01M15.732 5.732l3.536 3.536m0 0-7.072-7.072M5.732 17.732l3.536 3.536m0 0-7.072-7.072"/></svg>
                    Profil Saya
                </a>
                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                    @csrf
                    <button type="submit"
                            class="block px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-100 w-full" role="menuitem">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
