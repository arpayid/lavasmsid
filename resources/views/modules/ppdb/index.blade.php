<x-admin-layout heading="PPDB Online">
    {{-- Stats --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-5">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 text-center">
            <div class="text-2xl font-bold text-slate-900">{{ $stats['all'] }}</div>
            <div class="text-xs text-slate-500">Total</div>
        </div>
        <div class="rounded-xl bg-amber-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-amber-700">{{ $stats['submitted'] }}</div>
            <div class="text-xs text-amber-600">Baru</div>
        </div>
        <div class="rounded-xl bg-blue-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-blue-700">{{ $stats['verified'] }}</div>
            <div class="text-xs text-blue-600">Terverifikasi</div>
        </div>
        <div class="rounded-xl bg-emerald-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-emerald-700">{{ $stats['accepted'] }}</div>
            <div class="text-xs text-emerald-600">Diterima</div>
        </div>
        <div class="rounded-xl bg-red-50 p-4 shadow-sm text-center">
            <div class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</div>
            <div class="text-xs text-red-600">Ditolak</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="mb-6 flex gap-2">
        <a href="{{ route('admin.ppdb.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Semua</a>
        <a href="{{ route('admin.ppdb.index', ['status' => 'submitted']) }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request('status')=='submitted' ? 'bg-amber-600 text-white' : 'bg-white border text-slate-700' }}">Baru</a>
        <a href="{{ route('admin.ppdb.index', ['status' => 'verified']) }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request('status')=='verified' ? 'bg-blue-600 text-white' : 'bg-white border text-slate-700' }}">Terverifikasi</a>
        <a href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request('status')=='accepted' ? 'bg-emerald-600 text-white' : 'bg-white border text-slate-700' }}">Diterima</a>
        <a href="{{ route('admin.ppdb.index', ['status' => 'rejected']) }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request('status')=='rejected' ? 'bg-red-600 text-white' : 'bg-white border text-slate-700' }}">Ditolak</a>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">No. Daftar</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tgl Daftar</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($registrations as $r)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $r->registration_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ $r->candidate_name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $r->department->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $vm = ['submitted'=>'warning','verified'=>'info','accepted'=>'success','rejected'=>'danger'];
                            $vl = ['submitted'=>'Baru','verified'=>'Terverifikasi','accepted'=>'Diterima','rejected'=>'Ditolak'];
                        @endphp
                        <x-admin.badge :label="$vl[$r->status]??$r->status" :variant="$vm[$r->status]??'default'" />
                    </td>
                    <td class="hidden md:table-cell px-4 py-3 text-slate-600">{{ $r->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.ppdb.show', $r) }}" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-16"><x-admin.empty-state title="Belum ada pendaftar" message="PPDB dimulai." /></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($registrations->hasPages())<div class="px-4 py-3 border-t">{{ $registrations->links() }}</div>@endif
    </div>
</x-admin-layout>