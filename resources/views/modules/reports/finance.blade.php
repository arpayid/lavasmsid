<x-admin-layout heading="Laporan Keuangan">
    {{-- Summary cards --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 text-center">
            <div class="text-2xl font-bold text-slate-900">{{ $summary['total_invoices'] }}</div>
            <div class="text-xs text-slate-500">Total Tagihan</div>
        </div>
        <div class="rounded-xl bg-emerald-50 p-4 text-center">
            <div class="text-lg font-bold text-emerald-700">Rp{{ number_format($summary['total_paid'],0,',','.') }}</div>
            <div class="text-xs text-emerald-600">Terbayar</div>
        </div>
        <div class="rounded-xl bg-amber-50 p-4 text-center">
            <div class="text-lg font-bold text-amber-700">Rp{{ number_format($summary['total_unpaid'],0,',','.') }}</div>
            <div class="text-xs text-amber-600">Belum Lunas</div>
        </div>
        <div class="rounded-xl bg-blue-50 p-4 text-center">
            <div class="text-lg font-bold text-blue-700">Rp{{ number_format($summary['total_amount'],0,',','.') }}</div>
            <div class="text-xs text-blue-600">Total Tagihan</div>
        </div>
        <div class="mb-4 flex justify-end">
            @if(Route::has('admin.reports.finance.export'))
            <a href="{{ route('admin.reports.finance.export', request()->query()) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">Export CSV</a>
            @endif
        </div>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">No. Invoice</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Kategori</th>
                <th class="hidden md:table-cell px-4 py-3 text-right font-semibold text-slate-700">Jumlah</th>
                <th class="hidden md:table-cell px-4 py-3 text-right font-semibold text-slate-700">Terbayar</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($invoices as $inv)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $inv->invoice_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ $inv->student->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $inv->paymentCategory->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-right">Rp{{ number_format($inv->amount,0,',','.') }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-right">Rp{{ number_format($inv->paid_amount,0,',','.') }}</td>
                    <td class="px-4 py-3">@php $v = $inv->status==='paid'?'success':($inv->status==='partial'?'warning':'danger'); $l = $inv->status==='paid'?'Lunas':($inv->status==='partial'?'Sebagian':'Belum Lunas'); @endphp <x-admin.badge :label="$l" :variant="$v" /></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-16 text-center text-slate-500">Tidak ada data keuangan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
