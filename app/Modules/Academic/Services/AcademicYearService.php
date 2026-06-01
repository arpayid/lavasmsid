<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class AcademicYearService
{
    public function create(array $data): AcademicYear
    {
        return DB::transaction(function () use ($data) {
            if (! empty($data['is_active'])) {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }

            return AcademicYear::create($data);
        });
    }

    public function update(AcademicYear $academicYear, array $data): AcademicYear
    {
        return DB::transaction(function () use ($academicYear, $data) {
            if (! empty($data['is_active'])) {
                AcademicYear::where('is_active', true)->where('id', '!=', $academicYear->id)->update(['is_active' => false]);
            }
            $academicYear->update($data);

            return $academicYear;
        });
    }

    public function delete(AcademicYear $academicYear): bool
    {
        if ($academicYear->semesters()->exists()) {
            abort(403, 'Tahun ajaran tidak dapat dihapus karena masih memiliki semester terkait.');
        }

        return $academicYear->delete();
    }
}
