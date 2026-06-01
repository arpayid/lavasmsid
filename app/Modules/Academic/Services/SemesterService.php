<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Semester;
use Illuminate\Support\Facades\DB;

class SemesterService
{
    public function create(array $data): Semester
    {
        return DB::transaction(function () use ($data) {
            if (! empty($data['is_active'])) {
                Semester::where('is_active', true)->update(['is_active' => false]);
            }

            return Semester::create($data);
        });
    }

    public function update(Semester $semester, array $data): Semester
    {
        return DB::transaction(function () use ($semester, $data) {
            if (! empty($data['is_active'])) {
                Semester::where('is_active', true)->where('id', '!=', $semester->id)->update(['is_active' => false]);
            }
            $semester->update($data);

            return $semester;
        });
    }

    public function delete(Semester $semester): bool
    {
        return $semester->delete();
    }
}
