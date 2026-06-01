<x-admin-layout heading="Input Nilai Massal">
    <form method="POST" action="{{ route('admin.grades.bulk-store') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-select name="subject_id" label="Mata Pelajaran" :options="$subjects->pluck('name', 'id')->toArray()" :value="old('subject_id')" required />
            <x-admin.form-select name="semester_id" label="Semester" :options="$semesters->mapWithKeys(fn($s) => [$s->id => $s->name])->toArray()" :value="old('semester_id')" required />
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Tugas</th>
                        <th class="px-4 py-3">UTS</th>
                        <th class="px-4 py-3">UAS</th>
                        <th class="px-4 py-3">Praktik</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $index => $student)
                        <tr>
                            <td class="px-4 py-3">
                                <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                <div class="font-medium text-slate-900">{{ $student->name }}</div>
                                <div class="text-xs text-slate-500">{{ $student->nis ?? '-' }} · {{ $student->classroom?->name ?? 'Tanpa Kelas' }}</div>
                            </td>
                            <td class="px-4 py-3"><input type="number" min="0" max="100" step="0.01" name="grades[{{ $index }}][assignment_score]" class="w-24 rounded-lg border-slate-300 text-sm"></td>
                            <td class="px-4 py-3"><input type="number" min="0" max="100" step="0.01" name="grades[{{ $index }}][midterm_score]" class="w-24 rounded-lg border-slate-300 text-sm"></td>
                            <td class="px-4 py-3"><input type="number" min="0" max="100" step="0.01" name="grades[{{ $index }}][final_score]" class="w-24 rounded-lg border-slate-300 text-sm"></td>
                            <td class="px-4 py-3"><input type="number" min="0" max="100" step="0.01" name="grades[{{ $index }}][practice_score]" class="w-24 rounded-lg border-slate-300 text-sm"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.grades.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan Nilai</button>
        </div>
    </form>
</x-admin-layout>
