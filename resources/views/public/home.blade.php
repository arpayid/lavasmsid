<x-public-layout title="Beranda LavaSMSID">
<section class="bg-gradient-to-br from-indigo-700 via-blue-700 to-slate-900 px-6 py-24 text-white">
    <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <p class="mb-4 inline-flex rounded-full bg-white/10 px-4 py-2 text-sm">SMK Management System Professional</p>
            <h1 class="text-4xl font-black leading-tight md:text-6xl">Kelola sekolah SMK secara modern, aman, dan terintegrasi.</h1>
            <p class="mt-6 max-w-2xl text-lg text-blue-100">Website publik, admin panel, akademik, PPDB, keuangan, PKL, BKK, alumni, dan laporan dalam satu aplikasi Laravel modular.</p>
            <div class="mt-8 flex flex-wrap gap-3"><a class="rounded-xl bg-white px-5 py-3 font-semibold text-indigo-700" href="{{ route('public.ppdb') }}">Daftar PPDB</a><a class="rounded-xl border border-white/30 px-5 py-3 font-semibold" href="{{ route('login') }}">Masuk Portal</a></div>
        </div>
        <div class="rounded-3xl bg-white p-6 text-slate-900 shadow-2xl">
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach(['Siswa','Guru','Jurusan','PKL','PPDB','Keuangan'] as $item)
                    <div class="rounded-2xl border border-slate-200 p-5"><div class="text-2xl font-black text-indigo-600">{{ $loop->iteration * 128 }}</div><div class="text-sm text-slate-500">{{ $item }}</div></div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<section class="px-6 py-20"><div class="mx-auto max-w-7xl"><h2 class="text-3xl font-black">Modul Khusus SMK</h2><div class="mt-8 grid gap-5 md:grid-cols-3">@foreach(['Program Keahlian','PKL / Prakerin','Mitra Industri','Teaching Factory','BKK Alumni','Tracer Study'] as $module)<div class="rounded-2xl border border-slate-200 p-6 shadow-sm"><h3 class="font-bold">{{ $module }}</h3><p class="mt-2 text-sm text-slate-600">Terintegrasi dengan data akademik, siswa, laporan, dan dashboard sekolah.</p></div>@endforeach</div></div></section>
</x-public-layout>
