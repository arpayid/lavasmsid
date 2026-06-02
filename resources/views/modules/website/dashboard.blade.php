<x-admin-layout heading="Website & CMS Dashboard">
    <div class="mb-8 rounded-2xl bg-indigo-600 p-8 text-white shadow-lg shadow-indigo-200">
        <div class="flex items-center gap-6">
            @if($settings->logo_path)
                <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-20 w-auto rounded-lg bg-white/10 p-2 border border-white/20">
            @else
                <div class="h-20 w-20 flex items-center justify-center rounded-lg bg-white/10 border border-white/20 text-3xl font-black">CMS</div>
            @endif
            <div>
                <h2 class="text-3xl font-bold">{{ $settings->school_name }}</h2>
                <p class="mt-1 text-indigo-100 italic">{{ $settings->tagline ?? 'Selamat datang di Panel CMS Website Sekolah' }}</p>
                <div class="mt-4 flex gap-4">
                    <a href="{{ route('admin.website.settings.edit') }}" class="rounded-lg bg-white/20 px-4 py-2 text-xs font-bold hover:bg-white/30 transition-colors">Edit Profil Sekolah</a>
                    <a href="{{ url('/') }}" target="_blank" class="rounded-lg bg-white/10 px-4 py-2 text-xs font-bold hover:bg-white/20 transition-colors">Lihat Live Website ↗</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Pages --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-indigo-500">
            <div class="text-sm font-medium text-indigo-600 uppercase tracking-widest">Halaman Statis</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['pages_count'] }}</div>
        </div>

        {{-- News --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-blue-500">
            <div class="text-sm font-medium text-blue-600 uppercase tracking-widest">Berita</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['news_count'] }}</div>
        </div>

        {{-- Events --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-emerald-500">
            <div class="text-sm font-medium text-emerald-600 uppercase tracking-widest">Agenda</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['events_count'] }}</div>
        </div>

        {{-- Galleries --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-purple-500">
            <div class="text-sm font-medium text-purple-600 uppercase tracking-widest">Galeri</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['galleries_count'] }}</div>
        </div>

        {{-- Achievements --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-amber-500">
            <div class="text-sm font-medium text-amber-600 uppercase tracking-widest">Prestasi</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['achievements_count'] }}</div>
        </div>

        {{-- Facilities --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-teal-500">
            <div class="text-sm font-medium text-teal-600 uppercase tracking-widest">Fasilitas</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['facilities_count'] }}</div>
        </div>
    </div>
</x-admin-layout>
