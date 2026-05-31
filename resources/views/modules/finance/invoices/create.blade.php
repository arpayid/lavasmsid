<x-admin-layout heading="Buat Tagihan">
    <form method="POST" action="{{ route('admin.finance.invoices.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-select name="payment_category_id" label="Kategori Pembayaran" :options="$categories->pluck('name','id')->toArray()" :value="old('payment_category_id')" required />
            <x-admin.form-input name="amount" label="Jumlah Tagihan (Rp)" type="number" :value="old('amount')" required min="1" />
            <x-admin.form-input name="due_date" label="Jatuh Tempo" type="date" :value="old('due_date')" />

            {{-- Target --}}
            <div class="space-y-3">
                <label class="block text-sm font-medium text-slate-700">Target Tagihan <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2"><input type="radio" name="target_type" value="student" class="text-indigo-600" @checked(old('target_type')=='student') id="targetStudent"> <span class="text-sm">Per Siswa</span></label>
                    <label class="flex items-center gap-2"><input type="radio" name="target_type" value="classroom" class="text-indigo-600" @checked(old('target_type')=='classroom') id="targetClass"> <span class="text-sm">Per Kelas</span></label>
                    <label class="flex items-center gap-2"><input type="radio" name="target_type" value="all" class="text-indigo-600" @checked(old('target_type')=='all')> <span class="text-sm">Semua Siswa</span></label>
                </div>
            </div>

            <div id="studentSelect" style="display:none">
                <x-admin.form-select name="student_id" label="Pilih Siswa" :options="$students->pluck('name','id')->toArray()" :value="old('student_id')" placeholder="-- Pilih --" />
            </div>
            <div id="classroomSelect" style="display:none">
                <x-admin.form-select name="classroom_id" label="Pilih Kelas" :options="$classrooms->pluck('name','id')->toArray()" :value="old('classroom_id')" placeholder="-- Pilih --" />
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Buat Tagihan</button>
            <a href="{{ route('admin.finance.invoices.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
    @push('scripts')
    <script>
        document.querySelectorAll('input[name="target_type"]').forEach(el => {
            el.addEventListener('change', function() {
                document.getElementById('studentSelect').style.display = this.value === 'student' ? 'block' : 'none';
                document.getElementById('classroomSelect').style.display = this.value === 'classroom' ? 'block' : 'none';
            });
        });
        if (document.querySelector('input[name="target_type"]:checked')) {
            document.querySelector('input[name="target_type"]:checked').dispatchEvent(new Event('change'));
        }
    </script>
    @endpush
</x-admin-layout>