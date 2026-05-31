<x-admin-layout heading="Pusat Laporan">
    <p class="mb-6 text-slate-500">Pilih jenis laporan yang ingin dilihat.</p>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($reportTypes as $r)
        <a href="{{ route('admin.reports.' . $r['key']) }}" class="flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md hover:ring-indigo-200">
            <span class="text-3xl">{{ $r['icon'] }}</span>
            <div>
                <h3 class="font-semibold text-slate-900">{{ $r['name'] }}</h3>
                <p class="text-sm text-slate-500">Lihat laporan</p>
            </div>
        </a>
        @endforeach
    </div>
</x-admin-layout>
