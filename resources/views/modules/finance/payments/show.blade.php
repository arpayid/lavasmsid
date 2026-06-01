<x-admin-layout heading="Detail Pembayaran">
    <div class="max-w-2xl space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-lg font-semibold">Informasi Pembayaran</h2>
            <dl class="grid gap-4 sm:grid-cols-2">
                <div><dt class="text-sm text-slate-500">No. Kwitansi</dt><dd class="font-mono text-sm">{{ $payment->receipt_number }}</dd></div>
                <div><dt class="text-sm text-slate-500">Status</dt><dd><x-admin.badge :label="$payment->status" :variant="$payment->status=='verified'?'success':($payment->status=='rejected'?'danger':'warning')" /></dd></div>
                <div><dt class="text-sm text-slate-500">Siswa</dt><dd class="font-medium">{{ $payment->invoice->student->name ?? '-' }}</dd></div>
                <div><dt class="text-sm text-slate-500">No. Invoice</dt><dd><a href="{{ route('admin.finance.invoices.show', $payment->invoice_id) }}" class="text-indigo-600 hover:underline">{{ $payment->invoice->invoice_number }}</a></dd></div>
                <div><dt class="text-sm text-slate-500">Jumlah Bayar</dt><dd class="font-semibold text-lg text-emerald-700">Rp{{ number_format($payment->amount,0,',','.') }}</dd></div>
                <div><dt class="text-sm text-slate-500">Tanggal Bayar</dt><dd>{{ $payment->paid_at->format('d M Y') }}</dd></div>
                <div><dt class="text-sm text-slate-500">Metode</dt><dd class="capitalize">{{ $payment->method }}</dd></div>
                <div><dt class="text-sm text-slate-500">Diverifikasi Oleh</dt><dd>{{ $payment->verifier->name ?? 'Belum diverifikasi' }}</dd></div>
            </dl>
        </div>

        <div class="flex gap-3">
            @if($payment->status === 'pending' && auth()->user()->can('finance.verify'))
            <form method="POST" action="{{ route('admin.finance.payments.verify', $payment) }}">
                @csrf
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700">Verifikasi Pembayaran</button>
            </form>
            @endif
            @if($payment->status === 'pending' && auth()->user()->can('finance.update'))
            <form method="POST" action="{{ route('admin.finance.payments.destroy', $payment) }}" onsubmit="return confirm('Hapus pembayaran pending ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Hapus</button>
            </form>
            @endif
            <a href="{{ route('admin.finance.payments.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kembali</a>
        </div>
    </div>
</x-admin-layout>
