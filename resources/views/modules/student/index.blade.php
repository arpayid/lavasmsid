<x-admin-layout heading="Data Siswa">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2 flex-wrap">
            <x-admin.form-input name="search" placeholder="Cari nama/NIS/NISN..." :value="request('search')" class="w-64" />
            <select name="classroom_id" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Kelas</option>
                @foreach(\App\Modules\Academic\Models\Classroom::orderBy('name')->get() as $c)
                    <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') == 'active')>Aktif</option>
                <option value="graduated" @selected(request('status') == 'graduated')>Lulus</option>
                <option value="moved" @selected(request('status') == 'moved')>Pindah</option>
                <option value="dropped" @selected(request('status') == 'dropped')>Keluar</option>
            </select>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        @can('student.create')
        <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Siswa
        </a>
        @endcan
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50"><tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">NIS</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Kelas</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $s)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $s->nis }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $s->name }}</td>
                        <td class="hidden md:table-cell px-4 py-3">{{ $s->classroom->name ?? '-' }}</td>
                        <td class="hidden md:table-cell px-4 py-3">{{ $s->department->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php $sv = ['active'=>'success','graduated'=>'info','moved'=>'warning','dropped'=>'danger']; $sl = ['active'=>'Aktif','graduated'=>'Lulus','moved'=>'Pindah','dropped'=>'Keluar']; @endphp
                            <x-admin.badge :label="$sl[$s->status] ?? $s->status" :variant="$sv[$s->status] ?? 'default'" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.students.show', $s) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @can('student.update')
                                <a href="{{ route('admin.students.edit', $s) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endcan
                                @can('student.delete')
                                <form method="POST" action="{{ route('admin.students.destroy', $s) }}" onsubmit="return confirm('Hapus siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-16"><x-admin.empty-state title="Belum ada data siswa" message="Mulai tambahkan siswa baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())<div class="border-t border-slate-200 px-4 py-3">{{ $students->links() }}</div>@endif
    </div>
</x-admin-layout>
