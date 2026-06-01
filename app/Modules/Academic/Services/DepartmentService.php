<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    public function create(array $data): Department
    {
        return DB::transaction(function () use ($data) {
            return Department::create($data);
        });
    }

    public function update(Department $department, array $data): Department
    {
        return DB::transaction(function () use ($department, $data) {
            $department->update($data);

            return $department;
        });
    }

    public function delete(Department $department): bool
    {
        if ($department->competencies()->exists() || $department->classrooms()->exists() || $department->subjects()->exists()) {
            abort(403, 'Jurusan tidak dapat dihapus karena masih memiliki data terkait.');
        }

        return $department->delete();
    }
}
