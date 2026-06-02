<x-admin-layout heading="Dashboard">
    {{-- Greeting --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 px-6 py-8 sm:px-10 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="mt-1.5 text-blue-100 text-sm max-w-xl">
                    Kelola data sekolah, pantau keuangan, PPDB, PKL, dan alumni dari satu dashboard terpadu.
                </p>
            </div>
            @if(Route::has('admin.communication.messages.create'))
            <a href="{{ route('admin.communication.messages.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/20 px-5 py-2.5 text-sm font-semibold hover:bg-white/30 transition-colors shrink-0 backdrop-blur-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Pesan Baru
            </a>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="mb-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        @foreach($stats as $label => $value)
            @php
                $colors = [
                    'students' => 'blue', 'teachers' => 'green', 'departments' => 'purple', 'classrooms' => 'indigo',
                    'ppdb' => 'amber', 'payments_today' => 'emerald', 'attendance_today' => 'cyan', 'users' => 'slate',
                ];
            @endphp
            <x-admin.stat-card
                :value="$value"
                :label="ucwords(str_replace('_', ' ', $label))"
                :color="$colors[$label] ?? 'blue'"
                :icon="match($label) {
                    'students' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 14l9-5-9-5-9 5 9 5z\"/><path d=\"M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z\"/></svg>',
                    'teachers' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z\"/></svg>',
                    'departments' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\"/></svg>',
                    'classrooms' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4\"/></svg>',
                    'ppdb' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z\"/></svg>',
                    'payments_today' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>',
                    'attendance_today' => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>',
                    default => '<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13 10V3L4 14h7v7l9-11h-7z\"/></svg>',
                }"
            />
        @endforeach
    </div>

    {{-- Quick Actions + Modules --}}
    <div class="grid gap-6 lg:grid-cols-3">

        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Akses Cepat Modul">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @can('student.view')
                    <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-blue-200 hover:bg-blue-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">Siswa</span>
                    </a>
                    @endcan
                    @can('finance.view')
                    <a href="{{ route('admin.finance.dashboard') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-emerald-200 hover:bg-emerald-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-emerald-700 transition-colors">Keuangan</span>
                    </a>
                    @endcan
                    @can('attendance.view')
                    <a href="{{ route('admin.attendances.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-purple-200 hover:bg-purple-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-purple-700 transition-colors">Absensi</span>
                    </a>
                    @endcan
                    @can('grade.view')
                    <a href="{{ route('admin.grades.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-amber-200 hover:bg-amber-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-amber-700 transition-colors">Nilai</span>
                    </a>
                    @endcan
                    @can('internship.view')
                    <a href="{{ route('admin.internships.dashboard') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-blue-200 hover:bg-blue-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">PKL</span>
                    </a>
                    @endcan
                    @can('bkk.view')
                    <a href="{{ route('admin.bkk.dashboard') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-green-200 hover:bg-green-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-green-700 transition-colors">Alumni</span>
                    </a>
                    @endcan
                    @can('ppdb.view')
                    <a href="{{ route('admin.ppdb.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-amber-200 hover:bg-amber-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-amber-700 transition-colors">PPDB</span>
                    </a>
                    @endcan
                    @can('report.view')
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 hover:border-blue-200 hover:bg-blue-50 transition-all group">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">Laporan</span>
                    </a>
                    @endcan
                </div>
            </x-admin.card>
        </div>

        {{-- Right panel --}}
        <div class="space-y-6">
            <x-admin.card title="Ringkasan">
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm"><span class="text-slate-500">Siswa</span><span class="font-bold text-slate-900">{{ $stats['students'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm"><span class="text-slate-500">Guru</span><span class="font-bold text-slate-900">{{ $stats['teachers'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm"><span class="text-slate-500">Jurusan</span><span class="font-bold text-slate-900">{{ $stats['departments'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm"><span class="text-slate-500">Kelas</span><span class="font-bold text-slate-900">{{ $stats['classrooms'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm"><span class="text-slate-500">Pengguna</span><span class="font-bold text-slate-900">{{ $stats['users'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 pt-3"><span class="text-slate-500">PPDB</span><span class="font-bold text-slate-900">{{ $stats['ppdb'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm"><span class="text-slate-500">Absensi Hari Ini</span><span class="font-bold text-slate-900">{{ $stats['attendance_today'] ?? 0 }}</span></div>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 pt-3"><span class="text-slate-500">Pembayaran Hari Ini</span><span class="font-bold text-emerald-600">Rp {{ number_format($stats['payments_today'] ?? 0, 0, ',', '.') }}</span></div>
                </div>
            </x-admin.card>

            <x-admin.card title="Administrasi">
                @can('website.view')
                <a href="{{ route('admin.website.dashboard') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 hover:border-blue-200 hover:bg-blue-50 transition-all mb-2">
                    <span class="text-sm font-semibold text-slate-700">Website CMS</span>
                </a>
                @endcan
                @can('communication.view')
                <a href="{{ route('admin.communication.dashboard') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 hover:border-blue-200 hover:bg-blue-50 transition-all mb-2">
                    <span class="text-sm font-semibold text-slate-700">Komunikasi & Notifikasi</span>
                </a>
                @endcan
                @can('settings.view')
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 hover:border-blue-200 hover:bg-blue-50 transition-all">
                    <span class="text-sm font-semibold text-slate-700">Pengaturan Sekolah</span>
                </a>
                @endcan
            </x-admin.card>
        </div>
    </div>
</x-admin-layout>
