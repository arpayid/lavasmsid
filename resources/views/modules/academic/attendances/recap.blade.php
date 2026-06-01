<x-admin-layout heading="Rekap Absensi">
    <form method="GET" action="{{ route('admin.attendances.recap') }}" class="mb-6 flex gap-4 flex-wrap items-end">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Filter Kelas</label>
            <select name="classroom_id" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
            <select name="month" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                @foreach(range(1,12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" @selected(request('month') == str_pad($m, 2, '0', STR_PAD_LEFT))>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Tampilkan</button>
    </form>

    @if(count($summary) > 0)
    <div class="space-y-4">
        @foreach($summary as $studentId => $data)
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold text-slate-900 mb-3">{{ $data['student_name'] }}</h3>
            <div class="grid gap-3 sm:grid-cols-5">
                <div class="rounded-lg bg-emerald-50 p-3 text-center">
                    <div class="text-xl font-bold text-emerald-700">{{ $data['present'] }}</div>
                    <div class="text-xs text-emerald-600">Hadir</div>
                </div>
                <div class="rounded-lg bg-amber-50 p-3 text-center">
                    <div class="text-xl font-bold text-amber-700">{{ $data['sick'] }}</div>
                    <div class="text-xs text-amber-600">Sakit</div>
                </div>
                <div class="rounded-lg bg-blue-50 p-3 text-center">
                    <div class="text-xl font-bold text-blue-700">{{ $data['permission'] }}</div>
                    <div class="text-xs text-blue-600">Izin</div>
                </div>
                <div class="rounded-lg bg-red-50 p-3 text-center">
                    <div class="text-xl font-bold text-red-700">{{ $data['absent'] }}</div>
                    <div class="text-xs text-red-600">Alpha</div>
                </div>
                <div class="rounded-lg bg-orange-50 p-3 text-center">
                    <div class="text-xl font-bold text-orange-700">{{ $data['late'] }}</div>
                    <div class="text-xs text-orange-600">Terlambat</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="rounded-2xl bg-white p-16 shadow-sm ring-1 ring-slate-200">
        <x-admin.empty-state title="Belum ada data absensi" message="Pilih filter untuk menampilkan rekap absensi." />
    </div>
    @endif
</x-admin-layout>
