<x-admin-layout heading="Kategori Pembayaran">
    <div class="mb-6 flex items-center justify-between">
        <x-admin.form-input name="search" placeholder="Cari kategori..." :value="request('search')" class="w-72" />
        <a href="{{ route('admin.finance.categories.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Tambah Kategori
        </a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b border-slate-200 bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Deskripsi</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $c)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $c->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-slate-600">{{ $c->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.finance.categories.edit', $c) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <form method="POST" action="{{ route('admin.finance.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-16"><x-admin.empty-state title="Belum ada kategori" message="Tambahkan kategori pembayaran." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($categories->hasPages())<div class="px-4 py-3 border-t">{{ $categories->links() }}</div>@endif
    </div>
</x-admin-layout>