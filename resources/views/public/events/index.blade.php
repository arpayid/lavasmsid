<x-public-layout title="Agenda & Pengumuman">
    <div class="bg-emerald-700 py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Agenda & Kegiatan</h1>
            <p class="mt-4 text-emerald-100 text-lg">Jadwal kegiatan sekolah dan informasi penting mendatang.</p>
        </div>
    </div>

    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($events->count() > 0)
                <div class="space-y-6">
                    @foreach($events as $event)
                        <div class="flex flex-col sm:flex-row bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow group">
                            <div class="bg-emerald-50 sm:w-48 shrink-0 flex flex-col items-center justify-center text-emerald-600 p-6 border-b sm:border-b-0 sm:border-r border-slate-100">
                                <span class="text-sm font-bold uppercase tracking-widest">{{ $event->start_date->format('M Y') }}</span>
                                <span class="text-5xl font-black leading-none mt-2">{{ $event->start_date->format('d') }}</span>
                            </div>
                            <div class="p-6 sm:p-8 flex-1 flex flex-col justify-center">
                                <h4 class="font-bold text-2xl text-slate-900 mb-3 group-hover:text-emerald-600 transition-colors">{{ $event->title }}</h4>
                                @if($event->description)
                                    <p class="text-slate-600 mb-4">{{ strip_tags($event->description) }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 mt-auto pt-4 border-t border-slate-50">
                                    @if($event->end_date && $event->end_date != $event->start_date)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Selesai: {{ $event->end_date->format('d M Y') }}
                                        </div>
                                    @endif
                                    @if($event->location)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $event->location }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $events->links() }}
                </div>
            @else
                <div class="py-20 text-center">
                    <p class="text-slate-500 italic">Belum ada agenda kegiatan.</p>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
