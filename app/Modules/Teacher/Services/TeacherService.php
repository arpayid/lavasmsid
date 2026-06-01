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
            $hasSubjectsKey = array_key_exists('subjects', $data);
            $subjects = $data['subjects'] ?? [];
            unset($data['subjects']);

            if (isset($data['photo'])) {
                $data['photo_path'] = $data['photo']->store('teachers', 'public');
                unset($data['photo']);
            }

            $teacher = Teacher::create($data);

            if ($hasSubjectsKey) {
                $this->syncSubjects($teacher, $subjects);
            }

            return $teacher;
        });
    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        return DB::transaction(function () use ($teacher, $data) {
            $hasSubjectsKey = array_key_exists('subjects', $data);
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

            if ($hasSubjectsKey) {
                $this->syncSubjects($teacher, $subjects);
            }

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
     * Filters out empty rows where subject_id is empty/null.
     * If all rows are empty after filtering, detach all.
     */
    protected function syncSubjects(Teacher $teacher, ?array $subjects): void
    {
        if (empty($subjects)) {
            $teacher->subjects()->detach();

            return;
        }

        // Filter out rows without subject_id
        $validSubjects = array_values(array_filter($subjects, fn ($item) => ! empty($item['subject_id'])));

        if (empty($validSubjects)) {
            $teacher->subjects()->detach();

            return;
        }

        $syncData = [];
        foreach ($validSubjects as $item) {
            $sid = (int) $item['subject_id'];
            $syncData[$sid] = [
                'classroom_id' => $item['classroom_id'] ?? null,
                'academic_year_id' => $item['academic_year_id'] ?? null,
                'semester_id' => $item['semester_id'] ?? null,
            ];
        }

        $teacher->subjects()->sync($syncData);
    }
}
