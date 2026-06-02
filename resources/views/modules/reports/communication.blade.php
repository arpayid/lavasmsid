<x-admin-layout heading="Laporan Komunikasi">
    <div class="mb-6 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-indigo-700">{{ $stats['total_announcements'] }}</div><div class="text-xs text-slate-500">Pengumuman</div><div class="mt-1 flex gap-2 text-[10px]"><span class="text-emerald-600">{{ $stats['published_announcements'] }} Terbit</span><span class="text-slate-400">{{ $stats['draft_announcements'] }} Draft</span></div></div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-blue-700">{{ $stats['total_notifications'] }}</div><div class="text-xs text-slate-500">Notifikasi</div><div class="text-[10px] text-blue-600">{{ $stats['unread_notifications'] }} Belum Dibaca</div></div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-purple-700">{{ $stats['total_messages'] }}</div><div class="text-xs text-slate-500">Pesan Internal</div><div class="text-[10px] text-purple-600">{{ $stats['unread_messages'] }} Belum Dibaca</div></div>
    </div>

    <div class="mb-6 flex justify-end">
        @if(Route::has('admin.reports.communication.export'))
        <a href="{{ route('admin.reports.communication.export') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export CSV</a>
        @endif
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="font-bold text-slate-900 mb-4">Pengumuman Terbaru</h3>
        <div class="space-y-3">@forelse($recentAnnouncements as $a) <div class="flex justify-between text-sm"><span class="truncate">{{ $a->title }}</span><span class="text-xs text-slate-400 shrink-0">{{ $a->created_at->format('d/m/Y') }}</span></div> @empty <p class="text-sm text-slate-500 italic">Belum ada pengumuman.</p> @endforelse</div>
    </div>
</x-admin-layout>
