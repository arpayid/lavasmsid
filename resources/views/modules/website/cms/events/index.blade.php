<x-admin-layout heading="Kelola Agenda">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.website.events.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Agenda</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Agenda</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Lokasi</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($events as $e)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $e->title }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $e->start_date->format('d M Y') }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $e->location ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.website.events.edit', $e) }}" class="text-indigo-600">Edit</a>
                        <form method="POST" action="{{ route('admin.website.events.destroy', $e) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button type="submit" class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-16"><x-admin.empty-state title="Belum ada agenda" message="Tambahkan agenda." /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
