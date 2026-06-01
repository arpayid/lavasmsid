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
            $subjects = $data['subjects'] ?? [];
            unset($data['subjects']);

            if (isset($data['photo'])) {
                $data['photo_path'] = $data['photo']->store('teachers', 'public');
                unset($data['photo']);
            }

            $teacher = Teacher::create($data);
            $this->syncSubjects($teacher, $subjects);

            return $teacher;
        });
    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        return DB::transaction(function () use ($teacher, $data) {
            $subjects = $data['subjects'] ?? [];
            unset($data['subjects']);

            if (isset($data['photo'])) {
                if ($teacher->photo_path) {
                    Storage::disk('public')->delete($teacher->photo_path);
                }
                $data['photo_path'] = $data['photo']->store('teachers', 'public');
                unset($data['photo']);
            }

            $teacher->update($data);
            $this->syncSubjects($teacher, $subjects);

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

    /**
     * Sync teacher subjects pivot.
     * $subjects = [['subject_id' => 1, 'classroom_id' => null, ...], ...]
     */
    protected function syncSubjects(Teacher $teacher, array $subjects): void
    {
        if (empty($subjects)) {
            $teacher->subjects()->detach();

            return;
        }

        $syncData = [];
        foreach ($subjects as $item) {
            $sid = $item['subject_id'];
            $syncData[$sid] = [
                'classroom_id' => $item['classroom_id'] ?? null,
                'academic_year_id' => $item['academic_year_id'] ?? null,
                'semester_id' => $item['semester_id'] ?? null,
            ];
        }

        $teacher->subjects()->sync($syncData);
    }
}
