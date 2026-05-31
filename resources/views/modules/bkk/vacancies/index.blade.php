<x-admin-layout heading="Lowongan Kerja">
    <div class="mb-6 flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <select name="status" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status')=='active')>Aktif</option>
                <option value="closed" @selected(request('status')=='closed')>Tutup</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        <a href="{{ route('admin.bkk.vacancies.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Lowongan</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Posisi</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Perusahaan</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Lokasi</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Deadline</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($vacancies as $v)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $v->title }}</td>
                    <td class="px-4 py-3">{{ $v->company_name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $v->location ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $v->deadline?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$v->status === 'active' ? 'Aktif' : 'Tutup'" :variant="$v->status === 'active' ? 'success' : 'danger'" /></td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.bkk.vacancies.edit', $v) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <form method="POST" action="{{ route('admin.bkk.vacancies.destroy', $v) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-16"><x-admin.empty-state title="Belum ada lowongan" message="Tambahkan lowongan kerja." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($vacancies->hasPages())<div class="px-4 py-3 border-t">{{ $vacancies->links() }}</div>@endif
    </div>
</x-admin-layout>
