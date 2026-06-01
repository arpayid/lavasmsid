<x-admin-layout heading="Laporan Keuangan">
    <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="mb-4 text-lg font-semibold">Filter Laporan</h3>
        <form method="GET" action="{{ route('admin.finance.reports.index') }}" class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
            <x-admin.form-input name="date_from" label="Dari Tanggal" type="date" :value="request('date_from')" />
            <x-admin.form-input name="date_to" label="Sampai Tanggal" type="date" :value="request('date_to')" />
            <x-admin.form-select name="payment_category_id" label="Kategori" :options="$categories->pluck('name','id')->toArray()" :value="request('payment_category_id')" placeholder="Semua Kategori" />
            <x-admin.form-select name="status" label="Status" :options="['unpaid'=>'Belum Lunas', 'partial'=>'Sebagian', 'paid'=>'Lunas']" :value="request('status')" placeholder="Semua Status" />
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Filter</button>
                @can('finance.export')
                <a href="{{ route('admin.finance.reports.export', request()->all()) }}" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700" title="Export CSV">
                    CSV
                </a>
                @endcan
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="mb-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Total Tagihan</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">Rp {{ number_format($totals['totalBilled'], 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400">{{ $totals['totalInvoices'] }} invoice</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Total Terbayar</div>
            <div class="mt-1 text-2xl font-bold text-emerald-700">Rp {{ number_format($totals['totalPaid'], 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400">{{ $totals['verifiedPaymentCount'] }} pembayaran</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Sisa Piutang</div>
            <div class="mt-1 text-2xl font-bold text-red-600">Rp {{ number_format($totals['outstanding'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Status Invoice</div>
            <div class="mt-2 flex gap-2">
                <span class="text-xs font-medium text-red-600">{{ $totals['unpaidCount'] }} Blm</span>
                <span class="text-xs font-medium text-amber-600">{{ $totals['partialCount'] }} Seb</span>
                <span class="text-xs font-medium text-emerald-600">{{ $totals['paidCount'] }} Lunas</span>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Recent Verified Payments --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="mb-4 font-semibold text-slate-900">Pembayaran Terakhir (Terverifikasi)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b"><tr>
                        <th class="px-2 py-2 text-left">No. Kwitansi</th>
                        <th class="px-2 py-2 text-left">Siswa</th>
                        <th class="px-2 py-2 text-right">Jumlah</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($recentPayments as $p)
                        <tr>
                            <td class="px-2 py-3 font-mono text-xs">{{ $p->receipt_number }}</td>
                            <td class="px-2 py-3">{{ $p->invoice->student->name ?? '-' }}</td>
                            <td class="px-2 py-3 text-right">Rp{{ number_format($p->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Unpaid Invoices --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="mb-4 font-semibold text-slate-900">Tagihan Tertunggak / Sebagian</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b"><tr>
                        <th class="px-2 py-2 text-left">Siswa</th>
                        <th class="px-2 py-2 text-left">Kategori</th>
                        <th class="px-2 py-2 text-right">Sisa</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($recentUnpaid as $inv)
                        <tr>
                            <td class="px-2 py-3">{{ $inv->student->name ?? '-' }}</td>
                            <td class="px-2 py-3 text-xs">{{ $inv->paymentCategory->name ?? '-' }}</td>
                            <td class="px-2 py-3 text-right text-red-600">Rp{{ number_format($inv->getRemainingAmount(), 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
