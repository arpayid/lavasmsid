<x-admin-layout heading="Laporan PKL">
    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Filter Card --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4 text-slate-800">Filter Data</h3>
            <form method="GET" action="{{ route('admin.internships.reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <x-admin.form-select name="status" label="Status" :options="['planned'=>'Direncanakan', 'ongoing'=>'Berlangsung', 'completed'=>'Selesai', 'cancelled'=>'Dibatalkan']" :value="request('status')" placeholder="Semua Status" />
                <x-admin.form-select name="industry_partner_id" label="Mitra Industri" :options="$partners->pluck('name','id')->toArray()" :value="request('industry_partner_id')" placeholder="Semua Mitra" />
                <x-admin.form-input name="date_from" label="Mulai Dari" type="date" :value="request('date_from')" />
                <x-admin.form-input name="date_to" label="Hingga" type="date" :value="request('date_to')" />
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">Filter</button>
                    <a href="{{ route('admin.internships.reports.export', request()->all()) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700" title="Export CSV">CSV</a>
                </div>
            </form>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="text-sm text-slate-500 font-medium">Total Penempatan</div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900">{{ $totalInternships }}</span>
                    <span class="text-xs text-slate-400">siswa</span>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="text-sm text-slate-500 font-medium">Status Aktif</div>
                <div class="mt-2 flex gap-3">
                    <div>
                        <div class="text-xs text-slate-400 uppercase tracking-wider">Planned</div>
                        <div class="font-bold text-amber-600">{{ $plannedCount }}</div>
                    </div>
                    <div class="border-l border-slate-100 pl-3">
                        <div class="text-xs text-slate-400 uppercase tracking-wider">Ongoing</div>
                        <div class="font-bold text-indigo-600">{{ $ongoingCount }}</div>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="text-sm text-slate-500 font-medium">Rata-rata Nilai</div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-emerald-600">{{ number_format($averageGrade, 1) }}</span>
                    <span class="text-xs text-slate-400">/ 100</span>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="text-sm text-slate-500 font-medium">Aktivitas</div>
                <div class="mt-2 flex gap-3">
                    <div>
                        <div class="text-xs text-slate-400 uppercase tracking-wider">Logbook</div>
                        <div class="font-bold text-slate-700">{{ $totalLogs }}</div>
                    </div>
                    <div class="border-l border-slate-100 pl-3">
                        <div class="text-xs text-slate-400 uppercase tracking-wider">Visit</div>
                        <div class="font-bold text-slate-700">{{ $totalMonitorings }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed List --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                <h3 class="font-semibold text-slate-800">Siswa Selesai PKL Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Siswa</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Mitra Industri</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Nilai</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Predikat</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentCompleted as $intern)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $intern->student->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $intern->industryPartner->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-indigo-600">{{ $intern->grade ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($intern->score)
                                <x-admin.badge :label="$intern->score->predicate" variant="success" size="xs" />
                                @else
                                <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.internships.show', $intern) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">Belum ada data siswa yang menyelesaikan PKL.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
