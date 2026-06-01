<?php

namespace App\Modules\Staff\Services;

use App\Modules\Staff\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffService
{
    public function create(array $data): Staff
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['photo'])) {
                $data['photo_path'] = $data['photo']->store('staff', 'public');
                unset($data['photo']);
            }

            return Staff::create($data);
        });
    }

    public function update(Staff $staff, array $data): Staff
    {
        return DB::transaction(function () use ($staff, $data) {
            if (isset($data['photo'])) {
                if ($staff->photo_path) {
                    Storage::disk('public')->delete($staff->photo_path);
                }
                $data['photo_path'] = $data['photo']->store('staff', 'public');
                unset($data['photo']);
            }
            $staff->update($data);

            return $staff;
        });
    }

    public function delete(Staff $staff): bool
    {
        return DB::transaction(function () use ($staff) {
            if ($staff->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }

            return $staff->delete();
        });
    }
}
