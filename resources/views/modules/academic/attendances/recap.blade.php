<x-admin-layout heading="Rekap Absensi">
    <form method="GET" action="{{ route('admin.attendances.recap') }}" class="mb-6 flex gap-4 flex-wrap">
        <select name="student_id" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" required>
            <option value="">Pilih Siswa</option>
            @foreach($students as $s)
                <option value="{{ $s->id }}" @selected($student?->id == $s->id)>{{ $s->name }} — {{ $s->classroom->name ?? '' }}</option>
            @endforeach
        </select>
        <select name="month" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
            @foreach(range(1,12) as $m)
                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" @selected($month == str_pad($m, 2, '0', STR_PAD_LEFT))>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
            @endforeach
        </select>
        <select name="year" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
            @foreach(range(date('Y')-2, date('Y')) as $y)
                <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Tampilkan</button>
    </form>

    @if($recap)
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Rekap Absensi: {{ $student->name }}</h2>
        <div class="grid gap-4 sm:grid-cols-5">
            <div class="rounded-xl bg-emerald-50 p-4 text-center">
                <div class="text-2xl font-bold text-emerald-700">{{ $recap['present'] }}</div>
                <div class="text-sm text-emerald-600">Hadir</div>
            </div>
            <div class="rounded-xl bg-amber-50 p-4 text-center">
                <div class="text-2xl font-bold text-amber-700">{{ $recap['sick'] }}</div>
                <div class="text-sm text-amber-600">Sakit</div>
            </div>
            <div class="rounded-xl bg-blue-50 p-4 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ $recap['permission'] }}</div>
                <div class="text-sm text-blue-600">Izin</div>
            </div>
            <div class="rounded-xl bg-red-50 p-4 text-center">
                <div class="text-2xl font-bold text-red-700">{{ $recap['absent'] }}</div>
                <div class="text-sm text-red-600">Alpha</div>
            </div>
            <div class="rounded-xl bg-orange-50 p-4 text-center">
                <div class="text-2xl font-bold text-orange-700">{{ $recap['late'] }}</div>
                <div class="text-sm text-orange-600">Terlambat</div>
            </div>
        </div>
        <div class="mt-4 text-sm text-slate-500">Total: {{ $recap['total'] }} hari</div>
    </div>
    @endif
</x-admin-layout>