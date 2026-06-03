<x-admin-layout heading="Detail PKL">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold mb-4 text-slate-800">Informasi Penempatan</h3>
                    <dl class="space-y-4">
                        <div><dt class="text-sm text-slate-500">Siswa</dt><dd class="font-medium text-slate-900 text-lg">{{ $internship->student->name ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-slate-500">Mitra Industri</dt><dd class="font-medium text-slate-900">{{ $internship->industryPartner->name ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-slate-500">Guru Pembimbing</dt><dd class="text-slate-700">{{ $internship->user->name ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1">
                            @php $vm = ['planned'=>'warning','ongoing'=>'info','completed'=>'success','cancelled'=>'danger']; $vl = ['planned'=>'Direncanakan','ongoing'=>'Berlangsung','completed'=>'Selesai','cancelled'=>'Dibatalkan']; @endphp
                            <x-admin.badge :label="$vl[$internship->status] ?? $internship->status" :variant="$vm[$internship->status] ?? 'default'" />
                        </dd></div>
                        <div><dt class="text-sm text-slate-500">Periode</dt><dd class="text-slate-700 font-mono text-sm">{{ $internship->start_date?->format('d M Y') }} - {{ $internship->end_date?->format('d M Y') }}</dd></div>
                    </dl>

                    <div class="mt-6 rounded-xl border border-indigo-100 bg-indigo-50/40 p-4">
                        <h4 class="text-sm font-semibold text-indigo-700">Penilaian</h4>
                        @if($internship->score)
                        <div class="mt-3 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Nilai Akhir</div>
                                <div class="text-3xl font-bold text-indigo-700">{{ $internship->score->final_score }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Predikat</div>
                                <div class="text-2xl font-bold text-indigo-700">{{ $internship->score->predicate ?? '-' }}</div>
                            </div>
                        </div>
                        @elseif($internship->grade)
                        <div class="mt-3">
                            <div class="text-xs text-slate-500">Nilai Akhir</div>
                            <div class="text-3xl font-bold text-indigo-700">{{ $internship->grade }}</div>
                        </div>
                        @else
                        <p class="mt-2 text-sm text-slate-500 italic">Belum ada penilaian.</p>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-col gap-2">
                        @can('internship.update')<a href="{{ route('admin.internships.edit', $internship) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-indigo-700">Edit Data</a>@endcan
                        <a href="{{ route('admin.internships.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kembali</a>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="lg:col-span-2 space-y-6" x-data="{ activeTab: 'logs' }">
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="flex border-b border-slate-200 bg-slate-50">
                        <button @click="activeTab = 'logs'" :class="activeTab === 'logs' ? 'border-indigo-600 text-indigo-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Logbook</button>
                        <button @click="activeTab = 'monitorings'" :class="activeTab === 'monitorings' ? 'border-indigo-600 text-indigo-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Monitoring</button>
                        <button @click="activeTab = 'scoring'" :class="activeTab === 'scoring' ? 'border-indigo-600 text-indigo-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Penilaian</button>
                    </div>

                    <div class="p-6">
                        {{-- Logbook Content --}}
                        <div x-show="activeTab === 'logs'">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-slate-800">Catatan Kegiatan Harian</h4>
                                @if(in_array($internship->status, ['planned', 'ongoing']))
                                <button onclick="document.getElementById('modal-log').showModal()" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-700">+ Isi Logbook</button>
                                @endif
                            </div>
                            <div class="space-y-4">
                                @forelse($internship->logs()->latest('activity_date')->get() as $log)
                                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-1">
                                                <span class="font-mono text-xs font-bold text-slate-500">{{ $log->activity_date->format('d M Y') }}</span>
                                                @php $sv = ['submitted'=>'warning','reviewed'=>'success','rejected'=>'danger']; @endphp
                                                <x-admin.badge :label="ucfirst($log->status)" :variant="$sv[$log->status]??'default'" size="xs" />
                                            </div>
                                            <p class="text-sm text-slate-900 font-medium">{{ $log->activity }}</p>
                                        </div>
                                        @if($log->status === 'submitted' && auth()->user()->can('internship.update'))
                                        <button onclick="reviewLog({{ $log->id }}, '{{ $log->activity }}')" class="ml-2 text-xs text-indigo-600 hover:underline">Review</button>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <p class="py-8 text-center text-sm text-slate-500 italic">Belum ada catatan kegiatan.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Monitoring Content --}}
                        <div x-show="activeTab === 'monitorings'">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-slate-800">Riwayat Monitoring</h4>
                                @if(in_array($internship->status, ['ongoing', 'completed']))
                                <button onclick="document.getElementById('modal-monitoring').showModal()" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white">+ Catat Monitoring</button>
                                @endif
                            </div>
                            <div class="space-y-4">
                                @forelse($internship->monitorings()->latest('monitor_date')->get() as $mon)
                                <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                                    <p class="text-sm text-slate-700">{{ $mon->note }}</p>
                                    <div class="mt-2 text-[11px] text-slate-400 font-mono">{{ $mon->monitor_date->format('d/m/Y') }} oleh {{ $mon->teacher->name ?? '-' }}</div>
                                </div>
                                @empty
                                <p class="py-8 text-center text-sm text-slate-500 italic">Belum ada data monitoring.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Scoring Content --}}
                        <div x-show="activeTab === 'scoring'">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-semibold text-slate-800">Hasil Penilaian PKL</h4>
                                @if(in_array($internship->status, ['ongoing', 'completed']) && auth()->user()->can('internship.update'))
                                <button onclick="document.getElementById('modal-scoring').showModal()" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                                    {{ $internship->score ? 'Update Nilai' : 'Input Nilai' }}
                                </button>
                                @endif
                            </div>

                            @if($internship->score)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                    <div class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Kedisiplinan</div>
                                    <div class="text-xl font-bold text-slate-900">{{ $internship->score->discipline_score ?? '-' }}</div>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                    <div class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Keahlian</div>
                                    <div class="text-xl font-bold text-slate-900">{{ $internship->score->skill_score ?? '-' }}</div>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                    <div class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Sikap</div>
                                    <div class="text-xl font-bold text-slate-900">{{ $internship->score->attitude_score ?? '-' }}</div>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                    <div class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Laporan</div>
                                    <div class="text-xl font-bold text-slate-900">{{ $internship->score->report_score ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-indigo-100 bg-indigo-50/30 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-indigo-600 font-semibold mb-1">Rata-rata Nilai Akhir</div>
                                        <div class="text-4xl font-black text-indigo-700">{{ $internship->score->final_score }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-slate-500 mb-1 font-medium">Predikat</div>
                                        <div class="text-4xl font-black text-indigo-700">{{ $internship->score->predicate }}</div>
                                    </div>
                                </div>
                                @if($internship->score->notes)
                                <div class="mt-4 border-t border-indigo-100 pt-4 text-sm text-slate-700 italic">"{{ $internship->score->notes }}"</div>
                                @endif
                                <div class="mt-4 text-[10px] text-slate-400">Dinilai oleh: {{ $internship->score->assessed_by ?? '-' }} pada {{ $internship->score->assessed_at?->format('d/m/Y') }}</div>
                            </div>
                            @else
                            <div class="py-12 text-center">
                                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">📝</div>
                                <p class="text-slate-500 italic">Belum ada penilaian yang diinput.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    <dialog id="modal-scoring" class="rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/50">
        <form method="POST" action="{{ route('admin.internships.scores.store') }}" class="w-[500px]">
            @csrf
            <input type="hidden" name="internship_id" value="{{ $internship->id }}">
            <div class="border-b border-slate-100 p-5 font-bold text-slate-800">Input Penilaian PKL</div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.form-input name="discipline_score" label="Kedisiplinan (0-100)" type="number" step="0.01" :value="$internship->score->discipline_score ?? ''" />
                    <x-admin.form-input name="skill_score" label="Keahlian (0-100)" type="number" step="0.01" :value="$internship->score->skill_score ?? ''" />
                    <x-admin.form-input name="attitude_score" label="Sikap/Perilaku (0-100)" type="number" step="0.01" :value="$internship->score->attitude_score ?? ''" />
                    <x-admin.form-input name="report_score" label="Nilai Laporan (0-100)" type="number" step="0.01" :value="$internship->score->report_score ?? ''" />
                </div>
                <x-admin.form-input name="assessed_by" label="Nama Penilai (dari Industri)" :value="$internship->score->assessed_by ?? ''" />
                <x-admin.form-input name="assessed_at" label="Tanggal Penilaian" type="date" :value="$internship->score->assessed_at?->format('Y-m-d') ?? date('Y-m-d')" />
                <x-admin.form-textarea name="notes" label="Catatan Tambahan" rows="2">{{ $internship->score->notes ?? '' }}</x-admin.form-textarea>
                <p class="text-[10px] text-slate-500 italic">* Nilai Akhir & Predikat akan dihitung otomatis oleh sistem.</p>
            </div>
            <div class="flex justify-end gap-3 bg-slate-50 p-5 rounded-b-2xl">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-slate-600">Batal</button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan Nilai</button>
            </div>
        </form>
    </dialog>

    {{-- Other existing modals (logbook, monitoring, review) --}}
    <dialog id="modal-log" class="rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/50">
        <form method="POST" action="{{ route('admin.internships.logs.store') }}" class="w-[450px]">
            @csrf
            <input type="hidden" name="internship_id" value="{{ $internship->id }}">
            <div class="border-b border-slate-100 p-5 font-bold text-slate-800">Isi Logbook Harian</div>
            <div class="space-y-4 p-5">
                <x-admin.form-input name="activity_date" label="Tanggal Kegiatan" type="date" :value="date('Y-m-d')" required />
                <x-admin.form-textarea name="activity" label="Kegiatan yang dilakukan" required rows="3" />
                <x-admin.form-textarea name="result" label="Hasil/Output" rows="2" />
                <x-admin.form-input name="obstacle" label="Kendala (jika ada)" />
            </div>
            <div class="flex justify-end gap-3 bg-slate-50 p-5 rounded-b-2xl">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-slate-600">Batal</button>
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">Simpan Logbook</button>
            </div>
        </form>
    </dialog>

    <dialog id="modal-monitoring" class="rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/50">
        <form method="POST" action="{{ route('admin.internships.monitorings.store') }}" class="w-[500px]">
            @csrf
            <input type="hidden" name="internship_id" value="{{ $internship->id }}">
            <div class="border-b border-slate-100 p-5 font-bold text-slate-800">Catat Kunjungan Monitoring</div>
            <div class="space-y-4 p-5">
                <x-admin.form-input name="monitor_date" label="Tanggal Kunjungan" type="date" :value="date('Y-m-d')" required />
                <x-admin.form-textarea name="note" label="Catatan Monitoring" required rows="4" />
                <x-admin.form-input name="follow_up" label="Rencana Tindak Lanjut" />
            </div>
            <div class="flex justify-end gap-3 bg-slate-50 p-5 rounded-b-2xl">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-slate-600">Batal</button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan Data</button>
            </div>
        </form>
    </dialog>

    <dialog id="modal-review" class="rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/50">
        <form id="form-review" method="POST" action="" class="w-[450px]">
            @csrf
            <div class="border-b border-slate-100 p-5 font-bold text-slate-800">Review Logbook</div>
            <div class="space-y-4 p-5">
                <p id="review-activity" class="text-sm text-slate-600 italic"></p>
                <x-admin.form-select name="status" label="Keputusan" :options="['reviewed'=>'Diterima','rejected'=>'Ditolak']" required />
                <x-admin.form-textarea name="note" label="Catatan Review" rows="3" />
            </div>
            <div class="flex justify-end gap-3 bg-slate-50 p-5 rounded-b-2xl">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 text-sm font-medium text-slate-600">Batal</button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan Review</button>
            </div>
        </form>
    </dialog>

    @push('scripts')
    <script>
        function reviewLog(id, activity) {
            const modal = document.getElementById('modal-review');
            const form = document.getElementById('form-review');
            const text = document.getElementById('review-activity');
            form.action = `/admin/internships/logs/${id}/review`;
            text.innerText = `"${activity}"`;
            modal.showModal();
        }
    </script>
    @endpush
</x-admin-layout>
