<x-admin-layout heading="Edit Jadwal">
    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        @if($errors->has('conflict'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <x-admin.badge label="Bentrok!" variant="danger" /> {{ $errors->first('conflict') }}
        </div>
        @endif
        <div class="space-y-5">
            <x-admin.form-select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name', 'id')->toArray()" :value="old('classroom_id', $schedule->classroom_id)" required />
            <x-admin.form-select name="subject_id" label="Mata Pelajaran" :options="$subjects->pluck('name', 'id')->toArray()" :value="old('subject_id', $schedule->subject_id)" required />
            <x-admin.form-input name="teacher_id" label="ID Guru (opsional)" type="number" :value="old('teacher_id', $schedule->teacher_id)" />
            <x-admin.form-select name="day" label="Hari" :options="array_combine($days, $days)" :value="old('day', $schedule->day)" required />
            <div class="grid gap-4 grid-cols-2">
                <x-admin.form-input name="start_time" label="Jam Mulai" type="time" :value="old('start_time', $schedule->start_time?->format('H:i'))" required />
                <x-admin.form-input name="end_time" label="Jam Selesai" type="time" :value="old('end_time', $schedule->end_time?->format('H:i'))" required />
            </div>
            <x-admin.form-input name="room" label="Ruang" :value="old('room', $schedule->room)" />
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.schedules.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>