<x-admin-layout heading="Detail Tagihan">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Informasi Tagihan</h2>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">No. Invoice</dt><dd class="font-mono text-sm">{{ $invoice->invoice_number }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Siswa</dt><dd class="font-medium">{{ $invoice->student->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Kategori</dt><dd>{{ $invoice->paymentCategory->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jatuh Tempo</dt><dd>{{ $invoice->due_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jumlah Tagihan</dt><dd class="font-semibold text-lg">Rp{{ number_format($invoice->amount,0,',','.') }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Sisa Tagihan</dt><dd class="font-semibold text-lg {{ $invoice->getRemainingAmount() > 0 ? 'text-red-600' : 'text-emerald-600' }}">Rp{{ number_format($invoice->getRemainingAmount(),0,',','.') }}</dd></div>
                </dl>
            </div>

            {{-- Payments --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Riwayat Pembayaran</h2>
                    @if(!$invoice->getRemainingAmount() <= 0)
                    <a href="{{ route('admin.finance.payments.create', ['invoice_id' => $invoice->id]) }}" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white">+ Bayar</a>
                    @endif
                </div>
                @if($invoice->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b"><tr>
                            <th class="px-3 py-2 text-left text-slate-700 font-semibold">No. Kwitansi</th>
                            <th class="px-3 py-2 text-left text-slate-700 font-semibold">Tanggal</th>
                            <th class="px-3 py-2 text-right text-slate-700 font-semibold">Jumlah</th>
                            <th class="px-3 py-2 text-left text-slate-700 font-semibold">Status</th>
                        </tr></thead>
                        <tbody class="divide-y">
                            @foreach($invoice->payments as $p)
                            <tr class="hover:bg-slate-50">
                                <td class="px-3 py-2 font-mono text-xs">{{ $p->receipt_number }}</td>
                                <td class="px-3 py-2">{{ $p->paid_at->format('d M Y') }}</td>
                                <td class="px-3 py-2 text-right">Rp{{ number_format($p->amount,0,',','.') }}</td>
                                <td class="px-3 py-2"><x-admin.badge :label="$p->status == 'verified' ? 'Terverifikasi' : ($p->status == 'rejected' ? 'Ditolak' : 'Pending')" :variant="$p->status=='verified'?'success':($p->status=='rejected'?'danger':'warning')" /></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-slate-500">Belum ada pembayaran.</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <a href="{{ route('admin.finance.payments.create', ['invoice_id' => $invoice->id]) }}" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-center text-sm font-medium text-white">Catat Pembayaran</a>
            <a href="{{ route('admin.finance.invoices.index') }}" class="rounded-lg border px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a>
        </div>
    </div>
</x-admin-layout>