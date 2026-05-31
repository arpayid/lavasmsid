<x-admin-layout heading="Dashboard Keuangan">
    {{-- Stats --}}
    <div class="mb-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Total Pemasukan</div>
            <div class="mt-1 text-2xl font-bold text-emerald-700">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Pemasukan Hari Ini</div>
            <div class="mt-1 text-2xl font-bold text-emerald-700">Rp {{ number_format($stats['income_today'], 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Tagihan Belum Lunas</div>
            <div class="mt-1 text-2xl font-bold text-amber-700">{{ $stats['unpaid_invoices'] }}</div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm text-slate-500">Menunggu Verifikasi</div>
            <div class="mt-1 text-2xl font-bold text-indigo-700">{{ $stats['pending_verifications'] }}</div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="mb-6 flex gap-3">
        <a href="{{ route('admin.finance.invoices.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Buat Tagihan</a>
        <a href="{{ route('admin.finance.payments.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700">Catat Pembayaran</a>
        <a href="{{ route('admin.finance.categories.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kategori</a>
    </div>

    {{-- Recent Payments --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Pembayaran Terbaru</h2>
        @if($recentPayments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200"><tr>
                    <th class="px-4 py-3 text-left text-slate-700 font-semibold">No. Kwitansi</th>
                    <th class="px-4 py-3 text-left text-slate-700 font-semibold">Siswa</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-slate-700 font-semibold">Jumlah</th>
                    <th class="px-4 py-3 text-left text-slate-700 font-semibold">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentPayments as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $p->receipt_number }}</td>
                        <td class="px-4 py-3">{{ $p->invoice->student->name ?? '-' }}</td>
                        <td class="hidden md:table-cell px-4 py-3">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3"><x-admin.badge :label="$p->status" :variant="$p->status === 'verified' ? 'success' : ($p->status === 'rejected' ? 'danger' : 'warning')" /></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-slate-500">Belum ada pembayaran.</p>
        @endif
    </div>
</x-admin-layout>