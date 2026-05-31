<x-admin-layout heading="Data Alumni & Tracer Study">
    {{-- Stats --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-5">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 text-center">
            <div class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</div>
            <div class="text-xs text-slate-500">Total Alumni</div>
        </div>
        <div class="rounded-xl bg-emerald-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-emerald-700">{{ $stats['working'] }}</div>
            <div class="text-xs text-emerald-600">Bekerja</div>
        </div>
        <div class="rounded-xl bg-blue-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-blue-700">{{ $stats['studying'] }}</div>
            <div class="text-xs text-blue-600">Kuliah</div>
        </div>
        <div class="rounded-xl bg-amber-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-amber-700">{{ $stats['entrepreneur'] }}</div>
            <div class="text-xs text-amber-600">Wirausaha</div>
        </div>
        <div class="rounded-xl bg-red-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-red-700">{{ $stats['unemployed'] }}</div>
            <div class="text-xs text-red-600">Belum Bekerja</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('admin.bkk.alumni.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Semua</a>
        @foreach(['working'=>'Bekerja','studying'=>'Kuliah','entrepreneur'=>'Wirausaha','unemployed'=>'Belum Bekerja'] as $k => $l)
        <a href="{{ route('admin.bkk.alumni.index', ['status' => $k]) }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request('status')==$k ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">{{ $l }}</a>
        @endforeach
    </div>

    <div class="mb-6 flex justify-between">
        <a href="{{ route('admin.bkk.alumni.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Alumni</a>
        <a href="{{ route('admin.bkk.vacancies.index') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white">Lowongan Kerja</a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tahun</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Perusahaan/Institusi</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($alumni as $a)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $a->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->department->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->graduation_year }}</td>
                    <td class="px-4 py-3">
                        @php $vm = ['working'=>'success','studying'=>'info','entrepreneur'=>'warning','unemployed'=>'danger']; $vl = ['working'=>'Bekerja','studying'=>'Kuliah','entrepreneur'=>'Wirausaha','unemployed'=>'Belum Bekerja']; @endphp
                        <x-admin.badge :label="$vl[$a->status]??$a->status" :variant="$vm[$a->status]??'default'" />
                    </td>
                    <td class="hidden md:table-cell px-4 py-3 text-xs">
                        @if($a->status === 'working') {{ $a->company_name }}
                        @elseif($a->status === 'studying') {{ $a->institution_name }}
                        @elseif($a->status === 'entrepreneur') Wirausaha
                        @else - @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.bkk.alumni.edit', $a) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>
                        <form method="POST" action="{{ route('admin.bkk.alumni.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-16"><x-admin.empty-state title="Belum ada data alumni" message="Tambahkan data alumni." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($alumni->hasPages())<div class="px-4 py-3 border-t">{{ $alumni->links() }}</div>@endif
    </div>
</x-admin-layout>