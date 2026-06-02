<x-admin-layout heading="Mitra Industri">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2">
            <x-admin.form-input name="search" placeholder="Cari mitra..." :value="request('search')" class="w-72" />
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Cari</button>
        </form>
        <a href="{{ route('admin.industry-partners.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Mitra</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Sektor</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Kontak</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($partners as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium"><a href="{{ route('admin.industry-partners.show', $p) }}" class="text-indigo-600 hover:underline">{{ $p->name }}</a></td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $p->sector ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $p->contact_person ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.industry-partners.edit', $p) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <form method="POST" action="{{ route('admin.industry-partners.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-16"><x-admin.empty-state title="Belum ada mitra industri" message="Tambahkan mitra industri." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($partners->hasPages())<div class="px-4 py-3 border-t">{{ $partners->links() }}</div>@endif
    </div>
</x-admin-layout>