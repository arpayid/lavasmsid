<x-admin-layout heading="Detail Alumni">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.bkk.alumni.index') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
            <div class="flex gap-2">
                @can('alumni.update')
                <a href="{{ route('admin.bkk.alumni.edit', $alumni) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">Edit Profil</a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Profile Card --}}
            <div class="md:col-span-1 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 text-center">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-indigo-100 text-3xl font-bold text-indigo-600">
                        {{ substr($alumni->name, 0, 1) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-slate-900">{{ $alumni->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $alumni->nis ?? 'NIS belum ada' }}</p>
                    <div class="mt-4">
                        @php
                            $variants = ['working' => 'success', 'studying' => 'primary', 'entrepreneur' => 'warning', 'unemployed' => 'danger', 'unknown' => 'default'];
                            $labels = ['working' => 'Bekerja', 'studying' => 'Kuliah', 'entrepreneur' => 'Wirausaha', 'unemployed' => 'Belum Bekerja', 'unknown' => 'Tidak Diketahui'];
                        @endphp
                        <x-admin.badge :label="$labels[$alumni->status] ?? $alumni->status" :variant="$variants[$alumni->status] ?? 'default'" />
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wider">Kontak</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-slate-600">{{ $alumni->email ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.94.86l-.76 3.29a1 1 0 01-1.17.73l-3.23-.96a8 8 0 006.18 6.18l.96-3.23a1 1 0 01.73-1.17l3.29.76a1 1 0 01.86 1.94L18.42 9.06a2 2 0 01-2 2H3a2 2 0 01-2-2V5z"/></svg>
                            <span class="text-slate-600">{{ $alumni->phone ?? '-' }}</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm">
                            <svg class="h-4 w-4 text-slate-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-slate-600">{{ $alumni->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Info --}}
            <div class="md:col-span-2 space-y-6">
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Informasi Akademik & Karir
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Jurusan</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->department->name ?? '-' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Tahun Lulus</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->graduation_year }}</div>
                        </div>

                        @if($alumni->status === 'working')
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Perusahaan</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->company_name ?? '-' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Posisi / Jabatan</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->job_title ?? '-' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Range Gaji</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->salary_range ?? '-' }}</div>
                        </div>
                        @elseif($alumni->status === 'studying')
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Institusi / Univ</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->institution_name ?? '-' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Program Studi</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->study_program ?? '-' }}</div>
                        </div>
                        @elseif($alumni->status === 'entrepreneur')
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Nama Usaha</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->company_name ?? '-' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs font-semibold text-slate-400 uppercase">Bidang Usaha</div>
                            <div class="text-slate-700 font-medium">{{ $alumni->job_title ?? '-' }}</div>
                        </div>
                        @endif
                    </div>

                    @if($alumni->notes)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <div class="text-xs font-semibold text-slate-400 uppercase mb-2">Catatan Tambahan</div>
                        <div class="text-slate-600 text-sm italic bg-slate-50 p-4 rounded-xl border-l-4 border-slate-300">
                            "{{ $alumni->notes }}"
                        </div>
                    </div>
                    @endif
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex justify-between items-center">
                        <h3 class="font-semibold text-slate-800">Riwayat Lamaran Kerja</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50/50 text-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold">Lowongan</th>
                                    <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($alumni->applications->sortByDesc('created_at') as $app)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900"><a href="{{ route('admin.bkk.applications.show', $app) }}" class="hover:text-indigo-600">{{ $app->vacancy->title ?? '-' }}</a></div>
                                        <div class="text-xs text-slate-500">{{ $app->vacancy->company_name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs font-mono">{{ $app->applied_at?->format('d M Y') ?? $app->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $v = ['applied' => 'default', 'screening' => 'info', 'interview' => 'warning', 'hired' => 'success', 'rejected' => 'danger'];
                                        @endphp
                                        <x-admin.badge :label="ucfirst($app->status)" :variant="$v[$app->status] ?? 'default'" size="xs" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-500 italic">Belum ada riwayat lamaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
