<x-public-layout title="Fasilitas">
    <div class="bg-teal-600 py-16 text-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Fasilitas Sekolah</h1>
            <p class="mt-4 text-teal-100 text-lg">Mendukung pembelajaran yang optimal dengan sarana dan prasarana lengkap.</p>
        </div>
    </div>

    <div class="py-16 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($facilities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($facilities as $facility)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group">
                            @if($facility->image_path)
                                <div class="relative h-60 overflow-hidden">
                                    <img src="{{ Storage::url($facility->image_path) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                    <h3 class="absolute bottom-4 left-6 text-xl font-bold text-white">{{ $facility->name }}</h3>
                                </div>
                            @else
                                <div class="h-60 bg-slate-100 flex flex-col items-center justify-center text-slate-400 border-b border-slate-100">
                                    <svg class="h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <h3 class="text-xl font-bold text-slate-500">{{ $facility->name }}</h3>
                                </div>
                            @endif
                            <div class="p-6">
                                <p class="text-slate-600 text-sm leading-relaxed">{{ $facility->description ?? 'Tidak ada deskripsi.' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-20 text-center">
                    <p class="text-slate-500 italic">Belum ada data fasilitas yang ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
