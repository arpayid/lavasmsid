<x-admin-layout heading="Data Alumni & Tracer Study">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.bkk.alumni.index') }}" class="flex gap-2 flex-wrap">
            <x-admin.form-input name="search" placeholder="Cari nama/NIS..." :value="request('search')" class="w-64" />
            <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="working" @selected(request('status') == 'working')>Bekerja</option>
                <option value="studying" @selected(request('status') == 'studying')>Kuliah</option>
                <option value="entrepreneur" @selected(request('status') == 'entrepreneur')>Wirausaha</option>
                <option value="unemployed" @selected(request('status') == 'unemployed')>Belum Bekerja</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">Filter</button>
        </form>
        @can('alumni.create')
        <a href="{{ route('admin.bkk.alumni.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">+ Tambah Alumni</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="border-b bg-slate-50 text-slate-700 font-semibold">
                    <tr>
                        <th class="px-6 py-3">Nama / NIS</th>
                        <th class="px-6 py-3">Jurusan</th>
                        <th class="px-6 py-3">Tahun Lulus</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($alumni as $a)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $a->name }}</div>
                            <div class="text-xs text-slate-500">{{ $a->nis ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $a->department->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $a->graduation_year }}</td>
                        <td class="px-6 py-4">
                            @php
                                $variants = ['working' => 'success', 'studying' => 'primary', 'entrepreneur' => 'warning', 'unemployed' => 'danger', 'unknown' => 'default'];
                                $labels = ['working' => 'Bekerja', 'studying' => 'Kuliah', 'entrepreneur' => 'Wirausaha', 'unemployed' => 'Belum Bekerja', 'unknown' => 'Tidak Diketahui'];
                            @endphp
                            <x-admin.badge :label="$labels[$a->status] ?? $a->status" :variant="$variants[$a->status] ?? 'default'" size="xs" />
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.bkk.alumni.show', $a) }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 transition-colors" title="Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @can('alumni.update')
                                <a href="{{ route('admin.bkk.alumni.edit', $a) }}" class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L117 19l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endcan
                                @can('alumni.delete')
                                <form action="{{ route('admin.bkk.alumni.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus data alumni ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-2 text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <x-admin.empty-state title="Data alumni tidak ditemukan" message="Coba sesuaikan kata kunci pencarian atau filter Anda." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($alumni->hasPages())
        <div class="px-6 py-4 border-t bg-slate-50/50">
            {{ $alumni->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
