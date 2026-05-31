<x-admin-layout heading="Jurusan SMK">
<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <div class="flex items-center justify-between"><h2 class="font-bold">Program Keahlian</h2><button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Tambah Jurusan</button></div>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-500"><tr><th class="p-3">Kode</th><th class="p-3">Nama</th><th class="p-3">Status</th></tr></thead><tbody>@forelse($departments as $department)<tr class="border-t"><td class="p-3">{{ $department->code }}</td><td class="p-3 font-medium">{{ $department->name }}</td><td class="p-3">Aktif</td></tr>@empty<tr><td colspan="3" class="p-6 text-center text-slate-500">Belum ada jurusan.</td></tr>@endforelse</tbody></table></div>
    <div class="mt-4">{{ $departments->links() }}</div>
</div>
</x-admin-layout>
