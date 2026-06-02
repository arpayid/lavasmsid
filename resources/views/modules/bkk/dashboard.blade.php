<x-admin-layout heading="BKK & Alumni Dashboard">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Alumni --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm font-medium text-slate-500">Total Alumni</div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-slate-900">{{ $stats['total_alumni'] }}</span>
                <span class="text-xs text-slate-400">orang</span>
            </div>
        </div>

        {{-- Working --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-emerald-500">
            <div class="text-sm font-medium text-emerald-600">Bekerja</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['working_alumni'] }}</div>
        </div>

        {{-- Studying --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-blue-500">
            <div class="text-sm font-medium text-blue-600">Kuliah</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['studying_alumni'] }}</div>
        </div>

        {{-- Entrepreneur --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-amber-500">
            <div class="text-sm font-medium text-amber-600">Wirausaha</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['entrepreneur_alumni'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Vacancy Stats --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl bg-indigo-600 p-6 shadow-lg text-white">
                <h3 class="text-lg font-semibold mb-4">Lowongan Kerja</h3>
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-indigo-100 text-xs uppercase tracking-wider">Aktif</div>
                        <div class="text-4xl font-black">{{ $stats['active_vacancies'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-indigo-100 text-xs uppercase tracking-wider">Total</div>
                        <div class="text-2xl font-bold">{{ $stats['total_vacancies'] }}</div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.bkk.vacancies.index') }}" class="inline-block w-full text-center rounded-lg bg-white/20 py-2 text-sm font-medium hover:bg-white/30 transition-colors">
                        Kelola Lowongan
                    </a>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 gap-2">
                    <a href="{{ route('admin.bkk.alumni.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">🎓</span>
                        <span class="text-sm font-medium text-slate-700">Tambah Alumni</span>
                    </a>
                    <a href="{{ route('admin.bkk.vacancies.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">💼</span>
                        <span class="text-sm font-medium text-slate-700">Pasang Lowongan</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Alumni --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800">Alumni Terbaru</h3>
                    <a href="{{ route('admin.bkk.alumni.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Tahun Lulus</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentAlumni as $alumnus)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $alumnus->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $alumnus->department->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $alumnus->graduation_year }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $variants = ['working' => 'success', 'studying' => 'primary', 'entrepreneur' => 'warning', 'unemployed' => 'default'];
                                        $labels = ['working' => 'Bekerja', 'studying' => 'Kuliah', 'entrepreneur' => 'Wirausaha', 'unemployed' => 'Belum Bekerja'];
                                    @endphp
                                    <x-admin.badge :label="$labels[$alumnus->status] ?? $alumnus->status" :variant="$variants[$alumnus->status] ?? 'default'" size="xs" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">Belum ada data alumni.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
