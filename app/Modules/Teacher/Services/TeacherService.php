<?php

namespace App\Modules\Teacher\Services;

use App\Modules\Teacher\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherService
{
    public function create(array $data): Teacher
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['photo'])) {
                $data['photo_path'] = $data['photo']->store('teachers', 'public');
                unset($data['photo']);
            }

            return Teacher::create($data);
        });
    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        return DB::transaction(function () use ($teacher, $data) {
            if (isset($data['photo'])) {
                if ($teacher->photo_path) {
                    Storage::disk('public')->delete($teacher->photo_path);
                }
                $data['photo_path'] = $data['photo']->store('teachers', 'public');
                unset($data['photo']);
            }
            $teacher->update($data);

            return $teacher;
        });
    }

    public function delete(Teacher $teacher): bool
    {
        return DB::transaction(function () use ($teacher) {
            if ($teacher->photo_path) {
                Storage::disk('public')->delete($teacher->photo_path);
            }

            return $teacher->delete();
        });
    }
}
