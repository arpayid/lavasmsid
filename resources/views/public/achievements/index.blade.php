<x-public-layout title="Prestasi">
    <div class="bg-amber-500 py-16 text-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Prestasi Sekolah & Siswa</h1>
            <p class="mt-4 text-amber-100 text-lg">Bangga atas dedikasi dan pencapaian civitas akademika kami.</p>
        </div>
    </div>

    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($achievements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($achievements as $ach)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition-all group">
                            <div class="h-16 w-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2 leading-tight">{{ $ach->title }}</h3>
                            <p class="text-slate-600 text-sm mb-4">{{ $ach->description }}</p>
                            <div class="mt-auto pt-4 border-t border-slate-50 flex flex-col gap-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400 font-semibold uppercase tracking-wider">Tingkat</span>
                                    <span class="text-indigo-600 font-bold uppercase">{{ $ach->level }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400 font-semibold uppercase tracking-wider">Posisi</span>
                                    <span class="text-slate-700 font-bold">Juara {{ $ach->position ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between text-xs pt-1">
                                    <span class="text-slate-900 font-bold">{{ $ach->student_name ?? 'Siswa/Tim' }}</span>
                                    <span class="text-slate-400">{{ $ach->year }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $achievements->links() }}
                </div>
            @else
                <div class="py-20 text-center">
                    <p class="text-slate-500 italic">Belum ada data prestasi yang ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
