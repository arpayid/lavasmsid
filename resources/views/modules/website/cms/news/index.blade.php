<x-admin-layout heading="Kelola Berita">
    <div class="mb-6 flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <x-admin.form-input name="search" placeholder="Cari berita..." :value="request('search')" class="w-72" />
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Cari</button>
        </form>
        <a href="{{ route('admin.website.news.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Berita</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Judul</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Penulis</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($news as $n)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ Str::limit($n->title, 50) }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $n->author }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $n->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$n->is_published ? 'Terbit' : 'Draft'" :variant="$n->is_published ? 'success' : 'default'" /></td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.website.news.edit', $n) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <form method="POST" action="{{ route('admin.website.news.destroy', $n) }}" class="inline" onsubmit="return confirm('Hapus berita ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada berita" message="Tambahkan berita pertama." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($news->hasPages())<div class="px-4 py-3 border-t">{{ $news->links() }}</div>@endif
    </div>
</x-admin-layout>