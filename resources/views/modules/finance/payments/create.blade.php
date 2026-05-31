<x-admin-layout heading="Catat Pembayaran">
    <form method="POST" action="{{ route('admin.finance.payments.store') }}" class="max-w-xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-select name="invoice_id" label="Tagihan" :options="$invoices->mapWithKeys(fn($i) => [$i->id => $i->invoice_number.' - '.($i->student->name ?? '').' (Rp'.number_format($i->getRemainingAmount(),0,',','.').')'])->toArray()" :value="old('invoice_id', $invoice?->id)" required />
            <x-admin.form-input name="amount" label="Jumlah Pembayaran (Rp)" type="number" step="1" :value="old('amount', $invoice?->getRemainingAmount())" required min="1" />
            <x-admin.form-input name="paid_at" label="Tanggal Bayar" type="date" :value="old('paid_at', date('Y-m-d'))" required />
            <x-admin.form-select name="method" label="Metode Pembayaran" :options="['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS']" :value="old('method', 'cash')" />
            <p class="text-xs text-slate-500">Pembayaran akan otomatis diverifikasi jika Anda memiliki izin <strong>finance.verify</strong>.</p>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white">Catat Pembayaran</button>
            <a href="{{ route('admin.finance.payments.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>