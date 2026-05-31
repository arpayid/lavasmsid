<x-admin-layout heading="Data Siswa">
<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <div class="flex items-center justify-between"><h2 class="font-bold">Manajemen Siswa</h2><button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Tambah Siswa</button></div>
    <div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-500"><tr><th class="p-3">NIS</th><th class="p-3">Nama</th><th class="p-3">Status</th></tr></thead><tbody>@forelse($students as $student)<tr class="border-t"><td class="p-3">{{ $student->nis }}</td><td class="p-3 font-medium">{{ $student->name }}</td><td class="p-3"><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">{{ $student->status }}</span></td></tr>@empty<tr><td colspan="3" class="p-6 text-center text-slate-500">Belum ada data siswa.</td></tr>@endforelse</tbody></table></div>
    <div class="mt-4">{{ $students->links() }}</div>
</div>
</x-admin-layout>
