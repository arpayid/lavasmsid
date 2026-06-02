<x-admin-layout heading="Detail Lowongan">
    <div class="max-w-4xl mx-auto space-y-6 pb-12">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.bkk.vacancies.index') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
            <div class="flex gap-2">
                @can('bkk.update')
                <a href="{{ route('admin.bkk.vacancies.edit', $vacancy) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">Edit Lowongan</a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Main Info --}}
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $vacancy->title }}</h2>
                            <p class="text-lg text-slate-600 mt-1">{{ $vacancy->company_name }}</p>
                        </div>
                        @php
                            $variants = ['draft' => 'default', 'active' => 'success', 'closed' => 'danger'];
                            $labels = ['draft' => 'Draft', 'active' => 'Aktif', 'closed' => 'Tutup'];
                        @endphp
                        <x-admin.badge :label="$labels[$vacancy->status] ?? $vacancy->status" :variant="$variants[$vacancy->status] ?? 'default'" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-y border-slate-100 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-slate-100 text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Lokasi</p>
                                <p class="text-sm font-medium text-slate-700">{{ $vacancy->location ?? 'Tidak ditentukan' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-slate-100 text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Tipe</p>
                                <p class="text-sm font-medium text-slate-700">{{ $vacancy->type ?? 'Full-time' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Gaji</p>
                                <p class="text-sm font-medium text-slate-700">
                                    @if($vacancy->salary_min)
                                        Rp{{ number_format($vacancy->salary_min, 0, ',', '.') }} - Rp{{ number_format($vacancy->salary_max, 0, ',', '.') }}
                                    @else
                                        Negosiasi
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-red-50 text-red-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Deadline</p>
                                <p class="text-sm font-medium text-slate-700">{{ $vacancy->deadline?->format('d M Y') ?? 'Tidak terbatas' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-900 mb-2">Deskripsi Pekerjaan</h3>
                            <div class="prose prose-slate prose-sm max-w-none text-slate-600">
                                {!! nl2br(e($vacancy->description)) !!}
                            </div>
                        </div>
                        @if($vacancy->requirements)
                        <div>
                            <h3 class="font-bold text-slate-900 mb-2">Persyaratan</h3>
                            <div class="prose prose-slate prose-sm max-w-none text-slate-600">
                                {!! nl2br(e($vacancy->requirements)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">Informasi Mitra</h3>
                    @if($vacancy->industryPartner)
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nama Perusahaan</p>
                                <p class="text-sm font-medium text-indigo-600"><a href="{{ route('admin.industry-partners.show', $vacancy->industry_partner_id) }}">{{ $vacancy->industryPartner->name }}</a></p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Bidang / Sektor</p>
                                <p class="text-sm text-slate-700">{{ $vacancy->industryPartner->sector ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Kontak Person</p>
                                <p class="text-sm text-slate-700">{{ $vacancy->industryPartner->contact_person ?? '-' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">Mitra belum terdaftar.</p>
                    @endif
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">Pelamar ({{ $vacancy->applications->count() }})</h3>
                    @can('bkk.update')
                        @if($vacancy->status === 'active' && (!$vacancy->deadline || strtotime($vacancy->deadline) >= strtotime(date('Y-m-d'))))
                            <form action="{{ route('admin.bkk.applications.store') }}" method="POST" class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                @csrf
                                <input type="hidden" name="job_vacancy_id" value="{{ $vacancy->id }}">
                                <p class="text-[10px] font-bold text-slate-500 uppercase mb-3">Catat Pelamar Baru</p>
                                <div class="space-y-3">
                                    <select name="alumni_id" required class="w-full rounded-lg border-slate-300 text-xs focus:ring-indigo-500">
                                        <option value="">-- Pilih Alumni --</option>
                                        @foreach(\App\Modules\Alumni\Models\Alumni::orderBy('name')->get() as $alumni)
                                            <option value="{{ $alumni->id }}">{{ $alumni->name }} ({{ $alumni->nis }})</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2 text-xs font-bold text-white hover:bg-indigo-700">Submit Lamaran</button>
                                </div>
                            </form>
                        @endif
                    @endcan
                    <div class="space-y-3">
                        @forelse($vacancy->applications->sortByDesc('created_at')->take(10) as $app)
                            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-0">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                    {{ substr($app->alumni->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-slate-900 truncate">
                                        <a href="{{ route('admin.bkk.applications.show', $app) }}" class="hover:text-indigo-600">{{ $app->alumni->name }}</a>
                                    </p>
                                    <p class="text-[10px] text-slate-500">{{ $app->created_at->diffForHumans() }}</p>
                                </div>
                                @php
                                    $v = ['applied' => 'default', 'screening' => 'info', 'interview' => 'warning', 'hired' => 'success', 'rejected' => 'danger'];
                                @endphp
                                <x-admin.badge :label="ucfirst($app->status)" :variant="$v[$app->status] ?? 'default'" size="xs" />
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 italic text-center py-4">Belum ada pelamar.</p>
                        @endforelse
                    </div>
                    @if($vacancy->applications->count() > 10)
                        <a href="{{ route('admin.bkk.applications.index', ['vacancy_id' => $vacancy->id]) }}" class="block text-center mt-4 text-[10px] font-bold text-indigo-600 hover:underline text-uppercase">Lihat Semua Pelamar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
