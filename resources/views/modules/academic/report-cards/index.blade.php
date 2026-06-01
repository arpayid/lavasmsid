<x-admin-layout heading="Rapor Siswa">
    <form method="GET" action="{{ route('admin.report-cards.show') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-select name="student_id" label="Pilih Siswa" :options="$students->mapWithKeys(fn($s) => [$s->id => $s->name.' ('.$s->classroom->name.')'])->toArray()" :value="request('student_id')" required />
            <x-admin.form-select name="semester_id" label="Pilih Semester" :options="$semesters->mapWithKeys(fn($s) => [$s->id => $s->name.' ('.$s->academicYear->name.')'])->toArray()" :value="request('semester_id')" required />
        </div>
        <div class="mt-6"><button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Tampilkan Rapor</button></div>
    </form>
</x-admin-layout>
