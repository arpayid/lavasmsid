@php
    $activeRoute = fn($pattern) => request()->routeIs($pattern) ? 'bg-blue-50 text-blue-700 font-semibold border-r-2 border-blue-600' : 'text-slate-600 hover:bg-slate-100';
    $activeGroup = fn($pattern) => request()->routeIs($pattern) ? 'text-blue-700' : 'text-slate-500';
    $hasPeopleAccess = auth()->user()->can('student.view') || auth()->user()->can('teacher.view') || auth()->user()->can('staff.view') || auth()->user()->can('guardian.view') || auth()->user()->can('academic.view');
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white border-r border-slate-200 transition-all duration-300 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    :style="sidebarCollapsed ? 'width:4.5rem' : ''"
    x-cloak
>
    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200 px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white shadow-sm">LS</div>
            <span class="text-base font-bold text-slate-800" x-show="!sidebarCollapsed" x-collapse>LavaSMSID</span>
        </a>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 lg:block" title="Collapse">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <button @click="sidebarOpen = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 lg:hidden">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5 text-sm space-y-1" x-data="{ openGroup: '' }">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>Dashboard</span>
        </a>

        @if(auth()->user()->can('website.view'))
        <div>
            <button @click="openGroup = openGroup === 'website' ? '' : 'website'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.website.*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>Website CMS</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'website' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'website'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                @foreach([
                    ['route' => 'admin.website.dashboard', 'label' => 'Dashboard CMS'],
                    ['route' => 'admin.website.settings.edit', 'label' => 'Profil Sekolah'],
                    ['route' => 'admin.website.news.index', 'label' => 'Berita'],
                    ['route' => 'admin.website.events.index', 'label' => 'Agenda'],
                    ['route' => 'admin.website.gallery.index', 'label' => 'Galeri'],
                    ['route' => 'admin.website.achievements.index', 'label' => 'Prestasi'],
                    ['route' => 'admin.website.facilities.index', 'label' => 'Fasilitas'],
                    ['route' => 'admin.website.pages.index', 'label' => 'Halaman'],
                ] as $item)
                <a href="{{ route($item['route']) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute($item['route'].'*') }}">{{ $item['label'] }}</a>
                @endforeach
            </div>
        </div>
        @endif

        @if($hasPeopleAccess)
        <div>
            <button @click="openGroup = openGroup === 'people' ? '' : 'people'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.(students|teachers|staff|guardians|academic|schedule|attendance|grade|report-cards).*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>People</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'people' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'people'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                @can('student.view')<a href="{{ route('admin.students.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.students.*') }}">Data Siswa</a>@endcan
                @can('teacher.view')<a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.teachers.*') }}">Data Guru</a>@endcan
                @can('staff.view')<a href="{{ route('admin.staff.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.staff.*') }}">Staff</a>@endcan
                @can('guardian.view')<a href="{{ route('admin.guardians.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.guardians.*') }}">Orang Tua/Wali</a>@endcan

                @can('academic.view')
                <div>
                    <button @click="openGroup = openGroup === 'academic' ? '' : 'academic'" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50 transition">
                        <span x-show="!sidebarCollapsed" x-collapse>Akademik</span>
                        <svg class="h-3 w-3 shrink-0 transition-transform duration-200" :class="openGroup === 'academic' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div x-show="openGroup === 'academic'" x-collapse class="ml-2 space-y-0.5">
                        @foreach([
                            'admin.academic-years.index' => 'Tahun Ajaran', 'admin.semesters.index' => 'Semester',
                            'admin.departments.index' => 'Jurusan', 'admin.competencies.index' => 'Kompetensi',
                            'admin.classrooms.index' => 'Kelas', 'admin.subjects.index' => 'Mapel',
                        ] as $route => $label)
                        <a href="{{ route($route) }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm transition {{ $activeRoute(str_replace('.index', '.*', $route)) }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
                @endcan

                @if(auth()->user()->can('schedule.view') || auth()->user()->can('attendance.view') || auth()->user()->can('grade.view') || auth()->user()->can('report.view') || auth()->user()->can('academic.view'))
                <div>
                    <button @click="openGroup = openGroup === 'ops' ? '' : 'ops'" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50 transition">
                        <span x-show="!sidebarCollapsed" x-collapse>Operasional</span>
                        <svg class="h-3 w-3 shrink-0 transition-transform duration-200" :class="openGroup === 'ops' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div x-show="openGroup === 'ops'" x-collapse class="ml-2 space-y-0.5">
                        @can('schedule.view')<a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm transition {{ $activeRoute('admin.schedules.*') }}">Jadwal</a>@endcan
                        @can('attendance.view')<a href="{{ route('admin.attendances.index') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm transition {{ $activeRoute('admin.attendances.*') }}">Absensi</a>@endcan
                        @can('grade.view')<a href="{{ route('admin.grades.index') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm transition {{ $activeRoute('admin.grades.*') }}">Nilai</a>@endcan
                        @can('report.view')<a href="{{ route('admin.report-cards.index') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm transition {{ $activeRoute('admin.report-cards.*') }}">Rapor</a>@endcan
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        @can('ppdb.view')
        <a href="{{ route('admin.ppdb.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition {{ $activeRoute('admin.ppdb.*') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>PPDB</span>
        </a>
        @endcan

        @can('finance.view')
        <div>
            <button @click="openGroup = openGroup === 'finance' ? '' : 'finance'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.finance.*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>Keuangan</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'finance' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'finance'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                @foreach(['dashboard' => 'Dashboard', 'invoices.index' => 'Tagihan', 'payments.index' => 'Pembayaran', 'categories.index' => 'Kategori', 'reports.index' => 'Laporan'] as $r => $l)
                <a href="{{ route('admin.finance.'.$r) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.finance.'.$r.'*') }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endcan

        @can('internship.view')
        <div>
            <button @click="openGroup = openGroup === 'internship' ? '' : 'internship'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.internships.*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>PKL / Internship</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'internship' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'internship'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                <a href="{{ route('admin.internships.dashboard') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.internships.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.internships.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.internships.*') }}">Data PKL</a>
                @can('industry.view')<a href="{{ route('admin.industry-partners.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.industry-partners.*') }}">Mitra Industri</a>@endcan
            </div>
        </div>
        @endcan

        @can('bkk.view')
        <div>
            <button @click="openGroup = openGroup === 'bkk' ? '' : 'bkk'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.bkk.*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>BKK / Alumni</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'bkk' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'bkk'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                @foreach(['dashboard' => 'Dashboard', 'alumni.index' => 'Alumni', 'vacancies.index' => 'Lowongan', 'applications.index' => 'Lamaran', 'reports.index' => 'Laporan'] as $r => $l)
                <a href="{{ route('admin.bkk.'.$r) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.bkk.'.$r.'*') }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endcan

        @can('communication.view')
        <div>
            <button @click="openGroup = openGroup === 'communication' ? '' : 'communication'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.communication.*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>Komunikasi</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'communication' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'communication'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                <a href="{{ route('admin.communication.dashboard') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.communication.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.communication.announcements.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.communication.announcements.*') }}">Pengumuman</a>
                <a href="{{ route('admin.communication.messages.inbox') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.communication.messages.*') }}">Pesan Internal</a>
                <a href="{{ route('admin.communication.notifications.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.communication.notifications.*') }}">Notifikasi</a>
            </div>
        </div>
        @endcan

        @if(auth()->user()->can('user.view') || auth()->user()->can('role.view') || auth()->user()->hasRole('Super Admin'))
        <div>
            <button @click="openGroup = openGroup === 'users' ? '' : 'users'" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-xs font-bold uppercase tracking-wider {{ $activeGroup('admin.user-management.*') }} hover:bg-slate-100 transition">
                <span x-show="!sidebarCollapsed" x-collapse>User Mgmt</span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="openGroup === 'users' ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="openGroup === 'users'" x-collapse class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                @if(auth()->user()->can('user.view') || auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('admin.user-management.users.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.user-management.users.*') }}">Users</a>
                @endif
                @if(auth()->user()->can('role.view') || auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('admin.user-management.roles.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition {{ $activeRoute('admin.user-management.roles.*') }}">Roles</a>
                @endif
            </div>
        </div>
        @endif

        @can('settings.view')
        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition {{ $activeRoute('admin.settings.*') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0"/></svg>
            <span x-show="!sidebarCollapsed" x-collapse>Pengaturan</span>
        </a>
        @endcan
    </nav>

    <div class="border-t border-slate-200 px-4 py-3 text-xs text-slate-400" x-show="!sidebarCollapsed" x-collapse>
        v1.0.0 — LavaSMSID
    </div>
</aside>
