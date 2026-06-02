<x-admin-layout heading="Kirim Pesan Internal">
    <form method="POST" action="{{ route('admin.communication.messages.store') }}" class="max-w-3xl rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-6">
            <x-admin.form-select name="recipient_id" label="Pilih Penerima" :options="$users->pluck('name','id')->toArray()" :value="old('recipient_id')" required placeholder="-- Cari Nama User --" />
            <x-admin.form-input name="subject" label="Subjek Pesan" :value="old('subject')" required placeholder="Contoh: Info Pembayaran Siswa" />
            <x-admin.form-textarea name="body" label="Isi Pesan" :value="old('body')" rows="10" required placeholder="Tuliskan pesan Anda di sini..." />
        </div>

        <div class="mt-8 flex gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-md shadow-indigo-200">Kirim Pesan</button>
            <a href="{{ route('admin.communication.messages.inbox') }}" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
