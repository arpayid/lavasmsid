<x-public-layout title="Berita">
    <div class="bg-indigo-700 py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Berita & Kabar Sekolah</h1>
            <p class="mt-4 text-indigo-100 text-lg">Informasi terbaru seputar kegiatan dan prestasi sekolah.</p>
        </div>
    </div>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $item)
                        <div class="group rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col">
                            @if($item->image_path)
                                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-48 bg-slate-100 flex items-center justify-center text-slate-300">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 6H21a2 2 0 012 2v11a2 2 0 01-2 2h-2z"/></svg>
                                </div>
                            @endif
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 text-xs text-slate-500 mb-3">
                                    <span>{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $item->author ?? 'Admin' }}</span>
                                </div>
                                <h4 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('public.news.show', $item->slug) }}">{{ $item->title }}</a>
                                </h4>
                                <p class="text-slate-600 text-sm line-clamp-3 mb-4 flex-1">{{ Str::limit(strip_tags($item->content), 150) }}</p>
                                <a href="{{ route('public.news.show', $item->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Baca selengkapnya <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="py-20 text-center">
                    <p class="text-slate-500 italic">Belum ada berita yang diterbitkan.</p>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
