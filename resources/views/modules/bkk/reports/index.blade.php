<x-admin-layout heading="Laporan BKK & Alumni">
    <div class="max-w-7xl mx-auto space-y-8 pb-12">
        {{-- Export Tools --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-widest">Ekspor Data (CSV)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @can('alumni.export')
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Data Alumni</span>
                    <a href="{{ route('admin.bkk.reports.alumni-export') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition-colors">Export</a>
                </div>
                @endcan
                @can('bkk.export')
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Lowongan Kerja</span>
                    <a href="{{ route('admin.bkk.reports.vacancy-export') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition-colors">Export</a>
                </div>
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Riwayat Lamaran</span>
                    <a href="{{ route('admin.bkk.reports.application-export') }}" class="rounded-lg bg-amber-600 px-4 py-2 text-xs font-bold text-white hover:bg-amber-700 transition-colors">Export</a>
                </div>
                @endcan
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Alumni Stats --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="font-bold text-slate-900 mb-6 border-b pb-3">Statistik Alumni</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <span class="text-sm text-slate-500 font-medium">Total Alumni</span>
                        <span class="text-2xl font-black text-slate-900">{{ $stats['total_alumni'] }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">Bekerja</p>
                            <p class="text-lg font-bold text-emerald-700">{{ $stats['working'] }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-blue-50 border border-blue-100">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-tighter">Kuliah</p>
                            <p class="text-lg font-bold text-blue-700">{{ $stats['studying'] }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-amber-50 border border-amber-100">
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-tighter">Wirausaha</p>
                            <p class="text-lg font-bold text-amber-700">{{ $stats['entrepreneur'] }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Belum/Lainnya</p>
                            <p class="text-lg font-bold text-slate-700">{{ $stats['unemployed'] + $stats['unknown'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vacancy Stats --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="font-bold text-slate-900 mb-6 border-b pb-3">Statistik Lowongan</h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                        <div>
                            <p class="text-xs font-bold text-indigo-600 uppercase">Lowongan Aktif</p>
                            <p class="text-3xl font-black text-indigo-700">{{ $stats['active_vacancies'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-indigo-400 uppercase">Total</p>
                            <p class="text-xl font-bold text-indigo-500">{{ $stats['total_vacancies'] }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lowongan Terkini</p>
                        @foreach($activeVacancies as $v)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 truncate mr-2">{{ $v->title }}</span>
                                <span class="text-slate-400 font-mono text-[10px] shrink-0">{{ $v->deadline?->format('d/m') ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Application Stats --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="font-bold text-slate-900 mb-6 border-b pb-3">Pelacakan Penyerapan</h3>
                <div class="space-y-5">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 font-medium">Total Lamaran</span>
                        <span class="font-bold text-slate-900">{{ $stats['total_applications'] }}</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Diterima Kerja (Hired)</span>
                            <span class="font-bold text-emerald-600">{{ $stats['hired'] }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $stats['total_applications'] > 0 ? ($stats['hired'] / $stats['total_applications'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="pt-4 space-y-3">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Baru Diterima Kerja</p>
                        @forelse($recentHired as $app)
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-6 rounded-full bg-emerald-100 flex items-center justify-center text-[10px] font-bold text-emerald-600">✓</div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-700 truncate">{{ $app->alumni->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">di {{ $app->vacancy->company_name }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Belum ada data terbaru.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
