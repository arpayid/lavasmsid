<x-admin-layout heading="Input Nilai Siswa">
    <form method="POST" action="{{ route('admin.grades.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-select name="student_id" label="Siswa" :options="$students->pluck('name', 'id')->toArray()" :value="old('student_id')" required />
            <x-admin.form-select name="subject_id" label="Mata Pelajaran" :options="$subjects->pluck('name', 'id')->toArray()" :value="old('subject_id')" required />
            <x-admin.form-select name="semester_id" label="Semester" :options="$semesters->pluck('name', 'id')->toArray()" :value="old('semester_id')" required />
            <div class="grid gap-4 grid-cols-2">
                <x-admin.form-input name="assignment_score" label="Tugas" type="number" step="0.01" :value="old('assignment_score')" placeholder="0 - 100" />
                <x-admin.form-input name="midterm_score" label="Nilai Tengah Semester (UTS)" type="number" step="0.01" :value="old('midterm_score')" placeholder="0 - 100" />
                <x-admin.form-input name="final_score" label="Nilai Akhir Semester (UAS)" type="number" step="0.01" :value="old('final_score')" placeholder="0 - 100" />
                <x-admin.form-input name="practice_score" label="Nilai Praktik" type="number" step="0.01" :value="old('practice_score')" placeholder="0 - 100" />
            </div>
            <p class="text-xs text-slate-500">Nilai akhir (final_result) akan dihitung otomatis dengan rata-rata dari semua komponen.</p>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.grades.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>