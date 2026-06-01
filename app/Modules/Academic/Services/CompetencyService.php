<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Competency;
use Illuminate\Support\Facades\DB;

class CompetencyService
{
    public function create(array $data): Competency
    {
        return DB::transaction(function () use ($data) {
            return Competency::create($data);
        });
    }

    public function update(Competency $competency, array $data): Competency
    {
        return DB::transaction(function () use ($competency, $data) {
            $competency->update($data);

            return $competency;
        });
    }

    public function delete(Competency $competency): bool
    {
        return $competency->delete();
    }
}
