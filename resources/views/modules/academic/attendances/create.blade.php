<x-admin-layout heading="Input Absensi Harian">
    <form method="POST" action="{{ route('admin.attendances.store') }}" class="max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" id="attendanceForm">
        @csrf
        <div class="mb-6 grid gap-4 grid-cols-2">
            <x-admin.form-select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name', 'id')->toArray()" :value="old('classroom_id')" required id="classroomSelect" />
            <x-admin.form-input name="attendance_date" label="Tanggal" type="date" :value="old('attendance_date', date('Y-m-d'))" required />
        </div>

        {{-- Student attendance table --}}
        <div class="mb-6 overflow-hidden rounded-lg border border-slate-200">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="studentList">
                    <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Pilih kelas untuk memuat daftar siswa</td></tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan Absensi</button>
            <a href="{{ route('admin.attendances.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>

    @push('scripts')
    <script>
        const statuses = @json($statuses);
        document.getElementById('classroomSelect').addEventListener('change', function() {
            // In a real implementation, this would fetch students via AJAX
            // For now, show a message
            document.getElementById('studentList').innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Daftar siswa akan dimuat setelah fitur backend siswa selesai (Tahap 4 lanjutan)</td></tr>';
        });
    </script>
    @endpush
</x-admin-layout>