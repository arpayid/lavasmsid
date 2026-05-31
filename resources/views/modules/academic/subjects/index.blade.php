<x-admin-layout heading="Mata Pelajaran">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="flex gap-2">
            <x-admin.form-input name="search" placeholder="Cari mata pelajaran..." :value="request('search')" class="w-72" />
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Cari</button>
        </form>
        <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Mata Pelajaran
        </a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50"><tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                    <th class="hidden px-4 py-3 text-left font-semibold text-slate-700 md:table-cell">Jurusan</th>
                    <th class="hidden px-4 py-3 text-left font-semibold text-slate-700 md:table-cell">Tipe</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subjects as $s)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-mono text-sm text-slate-600">{{ $s->code }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $s->name }}</td>
                        <td class="hidden px-4 py-3 text-slate-600 md:table-cell">{{ $s->department->name ?? '-' }}</td>
                        <td class="hidden px-4 py-3 md:table-cell"><x-admin.badge :label="ucfirst($s->type)" variant="info" /></td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.subjects.edit', $s) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.subjects.destroy', $s) }}" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Tidak ada data mata pelajaran" message="Mulai tambahkan mata pelajaran baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subjects->hasPages())<div class="border-t border-slate-200 px-4 py-3">{{ $subjects->links() }}</div>@endif
    </div>
</x-admin-layout>