<x-admin-layout heading="Edit Tagihan">
    <form method="POST" action="{{ route('admin.finance.invoices.update', $invoice) }}" class="max-w-xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-select name="student_id" label="Siswa" :options="$students->pluck('name','id')->toArray()" :value="old('student_id', $invoice->student_id)" required />
            <x-admin.form-select name="payment_category_id" label="Kategori Pembayaran" :options="$categories->pluck('name','id')->toArray()" :value="old('payment_category_id', $invoice->payment_category_id)" required />
            <x-admin.form-input name="amount" label="Jumlah Tagihan (Rp)" type="number" :value="old('amount', $invoice->amount)" required min="1" />
            <x-admin.form-input name="due_date" label="Jatuh Tempo" type="date" :value="old('due_date', $invoice->due_date?->format('Y-m-d'))" />
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Perbarui Tagihan</button>
            <a href="{{ route('admin.finance.invoices.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
