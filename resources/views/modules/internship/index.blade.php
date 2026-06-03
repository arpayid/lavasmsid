<x-admin-layout heading="PKL/Prakerin">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2">
            <select name="status" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="planned" @selected(request('status')=='planned')>Direncanakan</option>
                <option value="ongoing" @selected(request('status')=='ongoing')>Berlangsung</option>
                <option value="completed" @selected(request('status')=='completed')>Selesai</option>
                <option value="cancelled" @selected(request('status')=='cancelled')>Dibatalkan</option>
            </select>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        <a href="{{ route('admin.internships.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah PKL</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Mitra</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Periode</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($internships as $i)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium"><a href="{{ route('admin.internships.show', $i) }}" class="text-indigo-600 hover:underline">{{ $i->student->name ?? '-' }}</a></td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $i->industryPartner->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-xs">{{ $i->start_date->format('d/m') }} - {{ $i->end_date->format('d/m/y') }}</td>
                    <td class="px-4 py-3">
                        @php $vm = ['planned'=>'warning','ongoing'=>'info','completed'=>'success','cancelled'=>'danger']; $vl = ['planned'=>'Direncanakan','ongoing'=>'Berlangsung','completed'=>'Selesai','cancelled'=>'Dibatalkan']; @endphp
                        <x-admin.badge :label="$vl[$i->status]??$i->status" :variant="$vm[$i->status]??'default'" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.internships.edit', $i) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <x-admin.delete-confirm :action="route('admin.internships.destroy', $i)" message="Hapus data PKL ini?">
                            Hapus
                        </x-admin.delete-confirm>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada data PKL" message="Tambahkan data PKL." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($internships->hasPages())<div class="px-4 py-3 border-t">{{ $internships->links() }}</div>@endif
    </div>
</x-admin-layout>