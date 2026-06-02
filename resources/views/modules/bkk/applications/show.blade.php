<x-admin-layout heading="Detail Lamaran Kerja">
    <div class="max-w-4xl mx-auto space-y-6 pb-12">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.bkk.applications.index') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Tracking Info --}}
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-bold text-slate-900 uppercase tracking-widest text-[10px]">Status Lamaran Saat Ini</h3>
                        @php
                            $variants = ['applied' => 'default', 'screening' => 'info', 'interview' => 'warning', 'hired' => 'success', 'rejected' => 'danger'];
                        @endphp
                        <x-admin.badge :label="ucfirst($application->status)" :variant="$variants[$application->status] ?? 'default'" />
                    </div>

                    <div class="relative flex items-center justify-between before:absolute before:inset-x-0 before:top-5 before:h-0.5 before:bg-slate-100">
                        @foreach(['applied', 'screening', 'interview', 'hired'] as $idx => $step)
                            @php
                                $isCompleted = array_search($application->status, ['applied', 'screening', 'interview', 'hired', 'rejected']) >= array_search($step, ['applied', 'screening', 'interview', 'hired']);
                                $isCurrent = $application->status === $step;
                            @endphp
                            <div class="relative z-10 flex flex-col items-center">
                                <div @class([
                                    'h-10 w-10 rounded-full flex items-center justify-center border-4',
                                    'bg-indigo-600 border-indigo-100 text-white' => $isCompleted,
                                    'bg-white border-slate-100 text-slate-300' => !$isCompleted,
                                    'ring-4 ring-indigo-50' => $isCurrent
                                ])>
                                    @if($isCompleted)
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $idx + 1 }}</span>
                                    @endif
                                </div>
                                <span @class([
                                    'mt-2 text-[10px] font-bold uppercase tracking-wider',
                                    'text-indigo-600' => $isCompleted,
                                    'text-slate-400' => !$isCompleted
                                ])>{{ ucfirst($step) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @can('bkk.update')
                    <div class="mt-12 pt-8 border-t border-slate-100">
                        <h4 class="font-bold text-slate-900 mb-4 text-sm">Perbarui Status Pelacakan</h4>
                        <form action="{{ route('admin.bkk.applications.update-status', $application) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @csrf @method('PUT')
                            <div class="md:col-span-2">
                                <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="applied" @selected($application->status == 'applied')>Applied (Awal)</option>
                                    <option value="screening" @selected($application->status == 'screening')>Screening (Seleksi Berkas)</option>
                                    <option value="interview" @selected($application->status == 'interview')>Interview (Wawancara)</option>
                                    <option value="hired" @selected($application->status == 'hired')>Hired (Diterima Kerja)</option>
                                    <option value="rejected" @selected($application->status == 'rejected')>Rejected (Ditolak)</option>
                                </select>
                            </div>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">Update Status</button>
                            <div class="md:col-span-3">
                                <x-admin.form-textarea name="notes" label="Catatan Progress" rows="3">{{ old('notes', $application->notes) }}</x-admin.form-textarea>
                            </div>
                        </form>
                        @if($application->status === 'hired')
                            <div class="mt-4 p-4 rounded-xl bg-emerald-50 text-emerald-700 text-xs border border-emerald-100 flex gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="font-bold">Info Sistem:</p>
                                    <p>Status karir alumni ini sudah otomatis diperbarui menjadi <strong>"Bekerja"</strong> di {{ $application->vacancy->company_name }}.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endcan
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider">Catatan Riwayat</h3>
                    <div class="prose prose-slate prose-sm text-slate-600">
                        {!! nl2br(e($application->notes ?? 'Belum ada catatan tambahan.')) !!}
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                {{-- Alumni Info --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider">Pelamar</h3>
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            {{ substr($application->alumni->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $application->alumni->name }}</p>
                            <p class="text-xs text-slate-500">{{ $application->alumni->department->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <a href="{{ route('admin.bkk.alumni.show', $application->alumni_id) }}" class="inline-block w-full text-center rounded-lg border border-slate-200 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Lihat Profil Lengkap
                        </a>
                    </div>
                </div>

                {{-- Vacancy Info --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider">Lowongan</h3>
                    <div class="mb-4">
                        <p class="text-sm font-bold text-indigo-600">{{ $application->vacancy->title }}</p>
                        <p class="text-xs text-slate-600">{{ $application->vacancy->company_name }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-50 space-y-3">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $application->vacancy->location ?? '-' }}
                        </div>
                        <a href="{{ route('admin.bkk.vacancies.show', $application->job_vacancy_id) }}" class="inline-block w-full text-center rounded-lg border border-slate-200 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Lihat Lowongan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
