<x-public-layout title="Akses Ditolak">
    <div class="flex min-h-[70vh] items-center justify-center">
        <div class="text-center">
            <div class="mb-6 text-8xl font-black text-slate-900">403</div>
            <h1 class="mb-3 text-2xl font-bold text-slate-700">Akses Ditolak</h1>
            <p class="mb-8 text-slate-500">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition">Kembali ke Dashboard</a>
        </div>
    </div>
</x-public-layout>
