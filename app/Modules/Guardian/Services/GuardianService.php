<?php

namespace App\Modules\Guardian\Services;

use App\Modules\Guardian\Models\Guardian;
use Illuminate\Support\Facades\DB;

class GuardianService
{
    public function create(array $data): Guardian
    {
        return DB::transaction(function () use ($data) {
            return Guardian::create($data);
        });
    }

    public function update(Guardian $guardian, array $data): Guardian
    {
        return DB::transaction(function () use ($guardian, $data) {
            $guardian->update($data);

            return $guardian;
        });
    }

    public function delete(Guardian $guardian): bool
    {
        return $guardian->delete();
    }
}
