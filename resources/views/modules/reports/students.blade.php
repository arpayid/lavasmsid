<x-admin-layout heading="Laporan Siswa">
    <div class="mb-6 flex gap-2 flex-wrap">
        <form method="GET" class="flex gap-2 flex-wrap items-end">
            <select name="department_id" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Jurusan</option>
                @foreach($departments as $d)
                <option value="{{ $d->id }}" @selected(request('department_id')==$d->id)>{{ $d->name }}</option>
                @endforeach
            </select>
            <select name="classroom_id" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classrooms as $c)
                <option value="{{ $c->id }}" @selected(request('classroom_id')==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        <div class="self-end flex gap-2">
            <a href="{{ route('admin.reports.students.export', request()->query()) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export CSV</a>
            <span class="text-sm text-slate-500 self-center"><strong>{{ $students->count() }}</strong> siswa</span>
        </div>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">NIS</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Kelas</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($students as $s)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $s->nis }}</td>
                    <td class="px-4 py-3 font-medium">{{ $s->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $s->department->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $s->classroom->name ?? '-' }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$s->status" :variant="$s->status=='active'?'success':'default'" /></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16 text-center text-slate-500">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
