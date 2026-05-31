<x-admin-layout heading="Edit Nilai Siswa">
    <form method="POST" action="{{ route('admin.grades.update', $grade) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-select name="student_id" label="Siswa" :options="$students->pluck('name', 'id')->toArray()" :value="old('student_id', $grade->student_id)" required />
            <x-admin.form-select name="subject_id" label="Mata Pelajaran" :options="$subjects->pluck('name', 'id')->toArray()" :value="old('subject_id', $grade->subject_id)" required />
            <x-admin.form-select name="semester_id" label="Semester" :options="$semesters->pluck('name', 'id')->toArray()" :value="old('semester_id', $grade->semester_id)" required />
            <div class="grid gap-4 grid-cols-2">
                <x-admin.form-input name="assignment_score" label="Tugas" type="number" step="0.01" :value="old('assignment_score', $grade->assignment_score)" />
                <x-admin.form-input name="midterm_score" label="UTS" type="number" step="0.01" :value="old('midterm_score', $grade->midterm_score)" />
                <x-admin.form-input name="final_score" label="UAS" type="number" step="0.01" :value="old('final_score', $grade->final_score)" />
                <x-admin.form-input name="practice_score" label="Praktik" type="number" step="0.01" :value="old('practice_score', $grade->practice_score)" />
            </div>
            <p class="text-xs text-slate-500">Nilai akhir dihitung otomatis dari rata-rata semua komponen yang diisi.</p>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.grades.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>