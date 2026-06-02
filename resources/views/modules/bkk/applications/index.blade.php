<x-admin-layout heading="Pelacakan Lamaran Kerja">
    <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <form method="GET" action="{{ route('admin.bkk.applications.index') }}" class="flex gap-4">
            <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="applied" @selected(request('status') == 'applied')>Applied</option>
                <option value="screening" @selected(request('status') == 'screening')>Screening</option>
                <option value="interview" @selected(request('status') == 'interview')>Interview</option>
                <option value="hired" @selected(request('status') == 'hired')>Hired</option>
                <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">Filter</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="border-b bg-slate-50 text-slate-700 font-semibold">
                    <tr>
                        <th class="px-6 py-3">Siswa / Alumni</th>
                        <th class="px-6 py-3">Lowongan / Perusahaan</th>
                        <th class="px-6 py-3">Tanggal Lamar</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $app)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $app->alumni->name }}</div>
                            <div class="text-xs text-slate-500">{{ $app->alumni->nis ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $app->vacancy->title }}</div>
                            <div class="text-xs text-slate-500">{{ $app->vacancy->company_name }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                            {{ $app->applied_at?->format('d/m/Y') ?? $app->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $variants = ['applied' => 'default', 'screening' => 'info', 'interview' => 'warning', 'hired' => 'success', 'rejected' => 'danger'];
                            @endphp
                            <x-admin.badge :label="ucfirst($app->status)" :variant="$variants[$app->status] ?? 'default'" size="xs" />
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.bkk.applications.show', $app) }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 transition-colors" title="Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @can('bkk.update')
                                <form action="{{ route('admin.bkk.applications.destroy', $app) }}" method="POST" onsubmit="return confirm('Hapus riwayat lamaran ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-2 text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <x-admin.empty-state title="Belum ada data lamaran" message="Riwayat alumni melamar kerja akan muncul di sini." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
        <div class="px-6 py-4 border-t bg-slate-50/50">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
