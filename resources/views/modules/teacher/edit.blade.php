<x-admin-layout heading="Edit Guru">
    <x-admin.form method="PUT" action="{{ route('admin.teachers.update', $teacher) }}" enctype="multipart/form-data" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name', $teacher->name)" required />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="nip" label="NIP" :value="old('nip', $teacher->nip)" />
            <x-admin.form-input name="nuptk" label="NUPTK" :value="old('nuptk', $teacher->nuptk)" />
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender', $teacher->gender)" />
            <x-admin.form-input name="birth_place" label="Tempat Lahir" :value="old('birth_place', $teacher->birth_place)" />
        </div>
        <x-admin.form-input name="birth_date" label="Tanggal Lahir" type="date" :value="old('birth_date', $teacher->birth_date?->format('Y-m-d'))" />
        <x-admin.form-input name="email" label="Email" type="email" :value="old('email', $teacher->email)" />
        <x-admin.form-input name="phone" label="Telepon" :value="old('phone', $teacher->phone)" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address', $teacher->address)" rows="2" />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="qualification" label="Pendidikan" :value="old('qualification', $teacher->qualification)" />
            <x-admin.form-input name="certification_number" label="No. Sertifikasi" :value="old('certification_number', $teacher->certification_number)" />
        </div>
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" :value="old('status', $teacher->status)" />
        @if($teacher->photo_path)<img src="{{ asset('storage/'.$teacher->photo_path) }}" class="h-16 w-16 rounded-lg object-cover mb-3">@endif
        <x-admin.form-input name="photo" label="Ganti Foto" type="file" accept="image/jpeg,image/png,image/webp" />

        {{-- Subject Assignment --}}
        <div class="mt-6 border-t border-slate-200 pt-6">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Mata Pelajaran yang Diajar</h3>
            <div id="subjectRows" class="space-y-3">
                @php
                    $existingSubjects = $teacher->subjects->map(fn($s) => [
                        'subject_id' => $s->id,
                        'classroom_id' => $s->pivot->classroom_id,
                        'academic_year_id' => $s->pivot->academic_year_id,
                        'semester_id' => $s->pivot->semester_id,
                    ])->toArray();
                    $oldSubjects = old('subjects', $existingSubjects);
                    if (empty($oldSubjects)) { $oldSubjects = [['subject_id'=>'','classroom_id'=>'','academic_year_id'=>'','semester_id'=>'']]; }
                    $subjectCount = count($oldSubjects);
                @endphp
                @foreach($oldSubjects as $i => $sub)
                <div class="subject-row grid gap-3 md:grid-cols-3">
                    <select name="subjects[{{ $i }}][subject_id]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $s) <option value="{{ $s->id }}" @selected($sub['subject_id'] == $s->id)>{{ $s->name }}</option> @endforeach
                    </select>
                    <select name="subjects[{{ $i }}][classroom_id]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">-- Kelas (opsional) --</option>
                        @foreach($classrooms as $c) <option value="{{ $c->id }}" @selected($sub['classroom_id'] == $c->id)>{{ $c->name }}</option> @endforeach
                    </select>
                    <div class="flex gap-2">
                        <select name="subjects[{{ $i }}][academic_year_id]" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Tahun Ajaran</option>
                            @foreach($academicYears as $ay) <option value="{{ $ay->id }}" @selected($sub['academic_year_id'] == $ay->id)>{{ $ay->name }}</option> @endforeach
                        </select>
                        <select name="subjects[{{ $i }}][semester_id]" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Semester</option>
                            @foreach($semesters as $sm) <option value="{{ $sm->id }}" @selected($sub['semester_id'] == $sm->id)>{{ $sm->name }}</option> @endforeach
                        </select>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addSubjectRow()" class="mt-3 text-sm text-primary-600 hover:underline">+ Tambah Mapel</button>
        </div>

        <div class="mt-6 flex gap-3">
            <x-admin.button type="submit">Simpan</x-admin.button>
            <a href="{{ route('admin.teachers.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </x-admin.form>

    @push('scripts')
    <script>
        let subjectIndex = {{ $subjectCount }};
        function addSubjectRow() {
            const container = document.getElementById('subjectRows');
            const row = document.createElement('div');
            row.className = 'subject-row grid gap-3 md:grid-cols-3';
            row.innerHTML = `
                <select name="subjects[${subjectIndex}][subject_id]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                </select>
                <select name="subjects[${subjectIndex}][classroom_id]" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">-- Kelas (opsional) --</option>
                    @foreach($classrooms as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                </select>
                <div class="flex gap-2">
                    <select name="subjects[${subjectIndex}][academic_year_id]" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Tahun Ajaran</option>
                        @foreach($academicYears as $ay) <option value="{{ $ay->id }}">{{ $ay->name }}</option> @endforeach
                    </select>
                    <select name="subjects[${subjectIndex}][semester_id]" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Semester</option>
                        @foreach($semesters as $sm) <option value="{{ $sm->id }}">{{ $sm->name }}</option> @endforeach
                    </select>
                </div>
            `;
            container.appendChild(row);
            subjectIndex++;
        }
    </script>
    @endpush
</x-admin-layout>
