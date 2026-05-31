<x-admin-layout heading="Kelola Prestasi">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.website.achievements.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Prestasi</a>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Prestasi</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tingkat</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tahun</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($achievements as $a)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $a->title }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->student_name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->level }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->year }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.website.achievements.edit', $a) }}" class="text-indigo-600">Edit</a>
                        <form method="POST" action="{{ route('admin.website.achievements.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button type="submit" class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada prestasi" message="Tambahkan prestasi." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($achievements->hasPages())<div class="px-4 py-3 border-t">{{ $achievements->links() }}</div>@endif
    </div>
</x-admin-layout>
