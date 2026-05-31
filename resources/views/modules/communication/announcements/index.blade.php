<x-admin-layout heading="Pengumuman Sekolah">
    <div class="mb-6 flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <select name="target" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Target</option>
                <option value="all" @selected(request('target')=='all')>Semua</option>
                <option value="students" @selected(request('target')=='students')>Siswa</option>
                <option value="teachers" @selected(request('target')=='teachers')>Guru</option>
                <option value="staff" @selected(request('target')=='staff')>Staff</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        <a href="{{ route('admin.communication.announcements.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Buat Pengumuman</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Judul</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Target</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Prioritas</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($announcements as $a)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ Str::limit($a->title, 50) }}</td>
                    <td class="hidden md:table-cell px-4 py-3"><x-admin.badge :label="$a->target" variant="info" /></td>
                    <td class="hidden md:table-cell px-4 py-3">
                        @php $pv = ['urgent'=>'danger','high'=>'warning','normal'=>'default','low'=>'default']; $pl = ['urgent'=>'Urgent','high'=>'Tinggi','normal'=>'Normal','low'=>'Rendah']; @endphp
                        <x-admin.badge :label="$pl[$a->priority]??$a->priority" :variant="$pv[$a->priority]??'default'" />
                    </td>
                    <td class="px-4 py-3"><x-admin.badge :label="$a->is_published ? 'Terbit' : 'Draft'" :variant="$a->is_published ? 'success' : 'default'" /></td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.communication.announcements.edit', $a) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <form method="POST" action="{{ route('admin.communication.announcements.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada pengumuman" message="Buat pengumuman pertama." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($announcements->hasPages())<div class="px-4 py-3 border-t">{{ $announcements->links() }}</div>@endif
    </div>
</x-admin-layout>
