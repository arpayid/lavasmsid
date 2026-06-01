<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Classroom;
use Illuminate\Support\Facades\DB;

class ClassroomService
{
    public function create(array $data): Classroom
    {
        return DB::transaction(function () use ($data) {
            return Classroom::create($data);
        });
    }

    public function update(Classroom $classroom, array $data): Classroom
    {
        return DB::transaction(function () use ($classroom, $data) {
            $classroom->update($data);

            return $classroom;
        });
    }

    public function delete(Classroom $classroom): bool
    {
        if ($classroom->students()->exists() || $classroom->schedules()->exists()) {
            abort(403, 'Kelas tidak dapat dihapus karena masih memiliki data terkait.');
        }

        return $classroom->delete();
    }
}
