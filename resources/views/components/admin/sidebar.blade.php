{{-- Sidebar: desktop fixed, mobile slide-in, collapsible via Alpine --}}
@php
    $activeRoute = fn($pattern) => request()->routeIs($pattern) ? 'bg-slate-800 text-white' : '';
    $hasPeopleAccess = auth()->user()->can('student.view') || auth()->user()->can('teacher.view') || auth()->user()->can('staff.view') || auth()->user()->can('guardian.view') || auth()->user()->can('academic.view');
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-950 text-slate-300 transition-all duration-300 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    :style="sidebarCollapsed ? 'width:4.5rem' : ''"
    x-cloak
>
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

    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm" x-data="{ openGroup: '' }">
        <a href="{{ route('admin.dashboard') }}"
           class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : '' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>Dashboard</span>
        </a>

        @if($hasPeopleAccess)
        <div class="mt-3">
            <button @click="openGroup = openGroup === 'people' ? '' : 'people'" class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-500 transition hover:text-slate-300">
                <span x-show="!sidebarCollapsed" x-collapse>People</span>
                <svg class="h-4 w-4 shrink-0 transition-transform" :class="openGroup === 'people' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'people'" x-collapse>
                @if(auth()->user()->can('student.view'))
                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.students.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Data Siswa</span></a>
                @endif
                @if(auth()->user()->can('teacher.view'))
                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.teachers.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Data Guru</span></a>
                @endif
                @if(auth()->user()->can('staff.view'))
                <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.staff.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Staff</span></a>
                @endif
                @if(auth()->user()->can('guardian.view'))
                <a href="{{ route('admin.guardians.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.guardians.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Orang Tua/Wali</span></a>
                @endif

                @if(auth()->user()->can('academic.view'))
                <button @click="openGroup = openGroup === 'academic' ? '' : 'academic'" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-slate-400 transition hover:text-slate-200">
                    <span x-show="!sidebarCollapsed" x-collapse>Akademik</span>
                    <svg class="h-3 w-3 shrink-0 transition-transform" :class="openGroup === 'academic' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="openGroup === 'academic'" x-collapse class="ml-4 border-l border-slate-700 pl-2 space-y-1">
                    <a href="{{ route('admin.academic-years.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.academic-years.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Tahun Ajaran</span></a>
                    <a href="{{ route('admin.semesters.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.semesters.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Semester</span></a>
                    <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.departments.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Jurusan</span></a>
                    <a href="{{ route('admin.competencies.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.competencies.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Kompetensi</span></a>
                    <a href="{{ route('admin.classrooms.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.classrooms.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Kelas</span></a>
                    <a href="{{ route('admin.subjects.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.subjects.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Mata Pelajaran</span></a>
                </div>
                @endif

                @if(auth()->user()->can('schedule.view') || auth()->user()->can('attendance.view') || auth()->user()->can('grade.view') || auth()->user()->can('report.view') || auth()->user()->can('academic.view'))
                <button @click="openGroup = openGroup === 'ops' ? '' : 'ops'" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-slate-400 transition hover:text-slate-200">
                    <span x-show="!sidebarCollapsed" x-collapse>Operasional</span>
                    <svg class="h-3 w-3 shrink-0 transition-transform" :class="openGroup === 'ops' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="openGroup === 'ops'" x-collapse class="ml-4 border-l border-slate-700 pl-2 space-y-1">
                    @if(auth()->user()->can('schedule.view'))
                    <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.schedules.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Jadwal Pelajaran</span></a>
                    @endif
                    @if(auth()->user()->can('attendance.view'))
                    <a href="{{ route('admin.attendances.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.attendances.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Absensi</span></a>
                    <a href="{{ route('admin.attendances.recap') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.attendances.recap*') }}"><span x-show="!sidebarCollapsed" x-collapse>Rekap Absensi</span></a>
                    @endif
                    @if(auth()->user()->can('grade.view'))
                    <a href="{{ route('admin.grades.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.grades.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Nilai</span></a>
                    @endif
                    @if(auth()->user()->can('report.view'))
                    <a href="{{ route('admin.report-cards.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.report-cards.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Rapor</span></a>
                    @endif
                    @if(auth()->user()->can('academic.view'))
                    <a href="{{ route('admin.academic-events.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm transition hover:bg-slate-800 {{ $activeRoute('admin.academic-events.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Kalender Akademik</span></a>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(auth()->user()->can('ppdb.view'))
        <div class="mt-3">
            <a href="{{ route('admin.ppdb.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition hover:bg-slate-800 {{ $activeRoute('admin.ppdb.*') }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span x-show="!sidebarCollapsed" x-collapse>PPDB</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->can('finance.view'))
        <div class="mt-3">
            <button @click="openGroup = openGroup === 'finance' ? '' : 'finance'" class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-500 transition hover:text-slate-300">
                <span x-show="!sidebarCollapsed" x-collapse>Keuangan</span>
                <svg class="h-4 w-4 shrink-0 transition-transform" :class="openGroup === 'finance' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'finance'" x-collapse>
                <a href="{{ route('admin.finance.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.finance.dashboard') }}"><span x-show="!sidebarCollapsed" x-collapse>Dashboard</span></a>
                <a href="{{ route('admin.finance.invoices.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.finance.invoices.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Tagihan</span></a>
                <a href="{{ route('admin.finance.payments.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.finance.payments.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Pembayaran</span></a>
                <a href="{{ route('admin.finance.categories.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.finance.categories.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Kategori</span></a>
                <a href="{{ route('admin.finance.reports.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.finance.reports.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Laporan</span></a>
            </div>
        </div>
        @endif

        @if(auth()->user()->can('user.view') || auth()->user()->can('role.view') || auth()->user()->hasRole('Super Admin'))
        <div class="mt-3">
            <button @click="openGroup = openGroup === 'users' ? '' : 'users'" class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-500 transition hover:text-slate-300">
                <span x-show="!sidebarCollapsed" x-collapse>User Management</span>
                <svg class="h-4 w-4 shrink-0 transition-transform" :class="openGroup === 'users' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'users'" x-collapse>
                @if(auth()->user()->can('user.view') || auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('admin.user-management.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.user-management.users.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Users</span></a>
                @endif
                @if(auth()->user()->can('role.view') || auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('admin.user-management.roles.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-800 {{ $activeRoute('admin.user-management.roles.*') }}"><span x-show="!sidebarCollapsed" x-collapse>Roles</span></a>
                @endif
            </div>
        </div>
        @endif

        @if(auth()->user()->can('settings.view') || auth()->user()->hasRole('Super Admin'))
        <div class="mt-3">
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-slate-800 {{ $activeRoute('admin.settings.*') }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0"/></svg>
                <span x-show="!sidebarCollapsed" x-collapse>Pengaturan</span>
            </a>
        </div>
        @endif
    </nav>

    <div class="border-t border-slate-800 px-4 py-3 text-xs text-slate-500" x-show="!sidebarCollapsed" x-collapse>
        v1.0.0 — SMK Management
    </div>
</aside>
