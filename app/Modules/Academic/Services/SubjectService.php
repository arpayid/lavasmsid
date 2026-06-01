<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectService
{
    public function create(array $data): Subject
    {
        return DB::transaction(function () use ($data) {
            return Subject::create($data);
        });
    }

    public function update(Subject $subject, array $data): Subject
    {
        return DB::transaction(function () use ($subject, $data) {
            $subject->update($data);

            return $subject;
        });
    }

    public function delete(Subject $subject): bool
    {
        if ($subject->grades()->exists() || $subject->schedules()->exists()) {
            abort(403, 'Mata pelajaran tidak dapat dihapus karena masih memiliki data terkait.');
        }

        return $subject->delete();
    }
}
