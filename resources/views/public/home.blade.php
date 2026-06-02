<x-public-layout title="Beranda">
    {{-- Hero Section --}}
    <div class="relative bg-slate-900 overflow-hidden">
        {{-- Fallback Gradient Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 z-10"></div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 text-center lg:text-left flex flex-col lg:flex-row items-center">
            <div class="lg:w-2/3">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                    {{ $settings->school_name ?? config('app.name') }}
                </h1>
                <p class="text-lg md:text-xl text-indigo-100 max-w-2xl mx-auto lg:mx-0 mb-10 leading-relaxed font-light">
                    {{ $settings->tagline ?? 'Sistem Manajemen Sekolah Modern Terpadu' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @if(Route::has('public.ppdb'))
                    <a href="{{ route('public.ppdb') }}" class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-full text-indigo-700 bg-white hover:bg-indigo-50 shadow-lg hover:shadow-xl transition-all">
                        Informasi PPDB
                    </a>
                    @endif
                    <a href="{{ route('public.profile') }}" class="inline-flex justify-center items-center px-8 py-3.5 border-2 border-white/30 text-base font-bold rounded-full text-white hover:bg-white/10 transition-all">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            @if(!empty($settings->principal_name) && !empty($settings->principal_message))
            <div class="lg:w-1/3 mt-12 lg:mt-0 hidden lg:block">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-2xl">
                    <p class="text-indigo-100 italic text-sm mb-4 leading-relaxed line-clamp-4">"{{ $settings->principal_message }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">{{ substr($settings->principal_name, 0, 1) }}</div>
                        <div>
                            <p class="text-white font-bold text-sm">{{ $settings->principal_name }}</p>
                            <p class="text-indigo-200 text-xs">Kepala Sekolah</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- News Section --}}
    @if(count($news) > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-2">Berita Terkini</h2>
                    <h3 class="text-3xl font-bold text-slate-900">Kabar Sekolah</h3>
                </div>
                <a href="{{ route('public.news') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                    Lihat Semua Berita <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($news as $item)
                <div class="group rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col">
                    @if($item->image_path)
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-48 bg-slate-100 flex items-center justify-center text-slate-300 group-hover:scale-105 transition-transform duration-500">
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
                        <p class="text-slate-600 text-sm line-clamp-3 mb-4 flex-1">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                        <a href="{{ route('public.news.show', $item->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                            Baca selengkapnya <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('public.news') }}" class="inline-flex items-center justify-center w-full px-6 py-3 border border-slate-200 rounded-xl text-sm font-bold text-indigo-600 hover:bg-slate-50 transition-colors">
                    Lihat Semua Berita
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Events & Achievements Section --}}
    <div class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
                {{-- Events --}}
                @if(count($events) > 0)
                <div>
                    <div class="flex justify-between items-end mb-8">
                        <h3 class="text-2xl font-bold text-slate-900">Agenda Mendatang</h3>
                        <a href="{{ route('public.events') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($events as $event)
                        <div class="flex bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all group">
                            <div class="bg-indigo-50 group-hover:bg-indigo-600 transition-colors w-24 shrink-0 flex flex-col items-center justify-center text-indigo-600 group-hover:text-white p-4 border-r border-slate-100 group-hover:border-indigo-600">
                                <span class="text-xs font-bold uppercase tracking-widest">{{ $event->start_date->format('M') }}</span>
                                <span class="text-3xl font-black leading-none mt-1">{{ $event->start_date->format('d') }}</span>
                            </div>
                            <div class="p-5 flex-1 flex flex-col justify-center">
                                <h4 class="font-bold text-lg text-slate-900 mb-2 leading-tight group-hover:text-indigo-600 transition-colors">{{ $event->title }}</h4>
                                @if($event->location)
                                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Achievements --}}
                @if(count($achievements) > 0)
                <div>
                    <div class="flex justify-between items-end mb-8">
                        <h3 class="text-2xl font-bold text-slate-900">Prestasi Terkini</h3>
                        <a href="{{ route('public.achievements') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($achievements as $ach)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-start gap-4 hover:shadow-md transition-all group">
                            <div class="h-14 w-14 shrink-0 rounded-xl bg-amber-50 text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors flex items-center justify-center">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-slate-900 leading-snug group-hover:text-amber-600 transition-colors">{{ $ach->title }}</h4>
                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-600">
                                    <span class="font-medium text-slate-700">{{ $ach->student_name ?? 'Siswa/Tim Sekolah' }}</span>
                                    <span class="hidden sm:inline text-slate-300">&bull;</span>
                                    <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                        Juara {{ $ach->position ?? '-' }} ({{ ucfirst($ach->level) }})
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-public-layout>
