<x-public-layout title="Galeri">
    <div class="bg-purple-700 py-16 text-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Galeri Foto</h1>
            <p class="mt-4 text-purple-100 text-lg">Dokumentasi momen berharga dan aktivitas sekolah.</p>
        </div>
    </div>

    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($galleries->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($galleries as $gal)
                        <div class="group relative aspect-square bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all">
                            <img src="{{ Storage::url($gal->image_path) }}" alt="{{ $gal->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-end">
                                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">{{ $gal->category }}</span>
                                <h4 class="text-white font-bold text-sm mt-1">{{ $gal->title }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $galleries->links() }}
                </div>
            @else
                <div class="py-20 text-center">
                    <p class="text-slate-500 italic">Belum ada dokumentasi galeri.</p>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
