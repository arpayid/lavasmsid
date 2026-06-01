{{-- Sidebar: desktop fixed, mobile slide-in, collapsible via Alpine --}}
@php
    $activeRoute = fn($pattern) => request()->routeIs($pattern) ? 'bg-slate-800 text-white' : '';
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-950 text-slate-300 transition-all duration-300 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    :style="sidebarCollapsed ? 'width:4.5rem' : ''"
    x-cloak
>
    {{-- Logo --}}
    <div class="flex h-16 items-center justify-between border-b border-slate-800 px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 overflow-hidden">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-sm font-black text-white">LS</div>
            <span class="text-lg font-bold text-white" x-show="!sidebarCollapsed" x-collapse>LavaSMSID</span>
        </a>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden rounded-lg p-1.5 hover:bg-slate-800 lg:block">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <button @click="sidebarOpen = false" class="rounded-lg p-1.5 hover:bg-slate-800 lg:hidden">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm" x-data="{ openGroup: '' }">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : '' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>Dashboard</span>
        </a>

        {{-- === Master Data === --}}
        @if(auth()->user()->can('student.view') || auth()->user()->can('teacher.view') || auth()->user()->can('academic.view'))
        <div class="mt-3">
            <button @click="openGroup = openGroup === 'master' ? '' : 'master'"
                    class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-500 transition hover:text-slate-300">
                <span x-show="!sidebarCollapsed" x-collapse>Master Data</span>
                <svg class="h-4 w-4 shrink-0 transition-transform" :class="openGroup === 'master' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'master'" x-collapse>
                @if(auth()->user()->can('student.view'))
                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.students.*') }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="!sidebarCollapsed" x-collapse>Data Siswa</span>
                </a>
                @endif
                @if(auth()->user()->can('teacher.view'))
                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.teachers.*') }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span x-show="!sidebarCollapsed" x-collapse>Data Guru</span>
                </a>
                @endif
                @if(auth()->user()->can('academic.view'))
                {{-- Sub-group: Akademik Master Data --}}
                <button @click="openGroup = openGroup === 'academic' ? '' : 'academic'" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-slate-400 transition hover:text-slate-200">
                    <span x-show="!sidebarCollapsed" x-collapse>Akademik</span>
                    <svg class="h-3 w-3 shrink-0 transition-transform" :class="openGroup === 'academic' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="openGroup === 'academic'" x-collapse class="ml-4 border-l border-slate-700 pl-2 space-y-1">
                    <a href="{{ route('admin.academic-years.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.academic-years.*') }}">
                        <span x-show="!sidebarCollapsed" x-collapse>Tahun Ajaran</span>
                    </a>
                    <a href="{{ route('admin.semesters.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.semesters.*') }}">
                        <span x-show="!sidebarCollapsed" x-collapse>Semester</span>
                    </a>
                    <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.departments.*') }}">
                        <span x-show="!sidebarCollapsed" x-collapse>Jurusan</span>
                    </a>
                    <a href="{{ route('admin.competencies.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.competencies.*') }}">
                        <span x-show="!sidebarCollapsed" x-collapse>Kompetensi</span>
                    </a>
                    <a href="{{ route('admin.classrooms.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.classrooms.*') }}">
                        <span x-show="!sidebarCollapsed" x-collapse>Kelas</span>
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.subjects.*') }}">
                        <span x-show="!sidebarCollapsed" x-collapse>Mata Pelajaran</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- === User Management === --}}
        @if(auth()->user()->can('user.view') || auth()->user()->can('role.view') || auth()->user()->hasRole('Super Admin'))
        <div class="mt-3">
            <button @click="openGroup = openGroup === 'users' ? '' : 'users'"
                    class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-500 transition hover:text-slate-300">
                <span x-show="!sidebarCollapsed" x-collapse>User Management</span>
                <svg class="h-4 w-4 shrink-0 transition-transform" :class="openGroup === 'users' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'users'" x-collapse>
                @if(auth()->user()->can('user.view') || auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('admin.user-management.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.user-management.users.*') }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197V21"/></svg>
                    <span x-show="!sidebarCollapsed" x-collapse>Users</span>
                </a>
                @endif
                @if(auth()->user()->can('role.view') || auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('admin.user-management.roles.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.user-management.roles.*') }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span x-show="!sidebarCollapsed" x-collapse>Roles</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- === Settings === --}}
        @if(auth()->user()->can('settings.view') || auth()->user()->hasRole('Super Admin'))
        <div class="mt-3">
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-slate-800 {{ $activeRoute('admin.settings.*') }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarCollapsed" x-collapse>Pengaturan</span>
            </a>
        </div>
        @endif

        {{-- Divider: placeholders for Phase 2+ --}}
        <div class="my-3 border-t border-slate-800"></div>
        <p class="px-3 text-[10px] uppercase tracking-wider text-slate-600" x-show="!sidebarCollapsed" x-collapse>Coming Soon</p>

        {{-- Keuangan --}}
        @if(auth()->user()->can('finance.view'))
        <a href="{{ Route::has('admin.finance.dashboard') ? route('admin.finance.dashboard') : '#' }}"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-500 transition hover:bg-slate-800">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>Keuangan</span>
        </a>
        @endif

        {{-- PPDB --}}
        @if(auth()->user()->can('ppdb.view'))
        <a href="{{ Route::has('admin.ppdb.index') ? route('admin.ppdb.index') : '#' }}"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-500 transition hover:bg-slate-800">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>PPDB</span>
        </a>
        @endif

        {{-- Laporan --}}
        @if(auth()->user()->can('report.view'))
        <a href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : '#' }}"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-500 transition hover:bg-slate-800">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>Laporan</span>
        </a>
        @endif
    </nav>

    {{-- Footer --}}
    <div class="border-t border-slate-800 px-4 py-3 text-xs text-slate-500" x-show="!sidebarCollapsed" x-collapse>
        v1.0.0 — SMK Management
    </div>
</aside>
