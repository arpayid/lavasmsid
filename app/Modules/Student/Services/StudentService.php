<?php

namespace App\Modules\Student\Services;

use App\Modules\Student\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['photo'])) {
                $data['photo_path'] = $data['photo']->store('students', 'public');
                unset($data['photo']);
            }

            return Student::create($data);
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            if (isset($data['photo'])) {
                if ($student->photo_path) {
                    Storage::disk('public')->delete($student->photo_path);
                }
                $data['photo_path'] = $data['photo']->store('students', 'public');
                unset($data['photo']);
            }
            $student->update($data);

            return $student;
        });
    }

    public function delete(Student $student): bool
    {
        return DB::transaction(function () use ($student) {
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }

            return $student->delete();
        });
    }
}
