<x-admin-layout heading="Laporan Website CMS">
    <div class="mb-6 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-indigo-700">{{ $stats['total_news'] }}</div><div class="text-xs text-slate-500">Total Berita</div><div class="mt-1 flex gap-2 text-[10px]"><span class="text-emerald-600">{{ $stats['published_news'] }} Terbit</span><span class="text-slate-400">{{ $stats['draft_news'] }} Draft</span></div></div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-blue-700">{{ $stats['total_events'] }}</div><div class="text-xs text-slate-500">Total Agenda</div></div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-purple-700">{{ $stats['total_pages'] }}</div><div class="text-xs text-slate-500">Total Halaman</div></div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><div class="text-2xl font-bold text-teal-700">{{ $stats['total_facilities'] + $stats['total_achievements'] }}</div><div class="text-xs text-slate-500">Fasilitas & Prestasi</div></div>
    </div>

    <div class="mb-6 flex justify-end">
        @if(Route::has('admin.reports.website.export'))
        <a href="{{ route('admin.reports.website.export') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export CSV</a>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-bold text-slate-900 mb-4">Berita Terbaru</h3>
            <div class="space-y-3">@forelse($latestNews as $n) <div class="flex justify-between text-sm"><span class="truncate">{{ $n->title }}</span><span class="text-xs text-slate-400 shrink-0">{{ $n->created_at->format('d/m/Y') }}</span></div> @empty <p class="text-sm text-slate-500 italic">Belum ada berita.</p> @endforelse</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-bold text-slate-900 mb-4">Agenda Terbaru</h3>
            <div class="space-y-3">@forelse($latestEvents as $e) <div class="flex justify-between text-sm"><span class="truncate">{{ $e->title }}</span><span class="text-xs text-slate-400 shrink-0">{{ $e->start_date?->format('d/m/Y') ?? '' }}</span></div> @empty <p class="text-sm text-slate-500 italic">Belum ada agenda.</p> @endforelse</div>
        </div>
    </div>
</x-admin-layout>
