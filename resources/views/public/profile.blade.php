<x-public-layout title="Profil Sekolah">
    <div class="relative bg-slate-900 py-24 text-white overflow-hidden">
        <div class="absolute inset-0 bg-indigo-900/60 z-10"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1541339907198-e08756cdfb3f?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Profil Sekolah</h1>
            <p class="mt-4 text-indigo-100 text-lg max-w-2xl mx-auto">{{ $settings->tagline ?? 'Mengenal lebih dekat sistem pendidikan kami.' }}</p>
        </div>
    </div>

    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="h-8 w-1.5 bg-indigo-600 rounded-full"></span>
                            Tentang {{ $settings->school_name }}
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                            @if($about && $about->content)
                                {!! nl2br(e($about->content)) !!}
                            @else
                                <p>{{ $settings->history ?? 'Informasi sejarah sekolah belum tersedia.' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="rounded-2xl bg-slate-50 p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-xl font-bold text-slate-900 mb-4">Visi</h3>
                            <p class="text-slate-600 text-sm leading-relaxed italic">
                                "{{ $settings->vision ?? 'Belum ditentukan.' }}"
                            </p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-xl font-bold text-slate-900 mb-4">Misi</h3>
                            <div class="text-slate-600 text-sm leading-relaxed prose prose-sm">
                                {!! nl2br(e($settings->mission ?? 'Belum ditentukan.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-12">
                    @if($settings->principal_name)
                    <div class="rounded-3xl bg-indigo-50 p-8 border border-indigo-100 relative shadow-sm">
                        <span class="absolute -top-6 left-8 text-6xl text-indigo-200">“</span>
                        <h3 class="text-xl font-bold text-indigo-900 mb-4 relative z-10">Sambutan Kepala Sekolah</h3>
                        <p class="text-indigo-800 text-sm leading-relaxed mb-6 italic">
                            {{ $settings->principal_message ?? '-' }}
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($settings->principal_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-indigo-900 leading-tight">{{ $settings->principal_name }}</p>
                                <p class="text-xs text-indigo-600 uppercase font-semibold tracking-wider">Kepala Sekolah</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="rounded-3xl overflow-hidden shadow-2xl ring-1 ring-slate-200">
                         <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1000&auto=format&fit=crop" alt="School" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
