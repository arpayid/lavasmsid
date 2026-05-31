<x-public-layout title="Halaman Tidak Ditemukan">
    <div class="flex min-h-[70vh] items-center justify-center">
        <div class="text-center">
            <div class="mb-6 text-8xl font-black text-slate-900">404</div>
            <h1 class="mb-3 text-2xl font-bold text-slate-700">Halaman Tidak Ditemukan</h1>
            <p class="mb-8 text-slate-500">Halaman yang Anda cari tidak ditemukan atau telah dipindahkan.</p>
            <a href="{{ route('public.home') }}" class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition">Kembali ke Beranda</a>
        </div>
    </div>
</x-public-layout>
