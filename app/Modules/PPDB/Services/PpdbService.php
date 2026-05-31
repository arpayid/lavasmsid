<?php

namespace App\Modules\PPDB\Services;

use App\Models\User;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\Student\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PpdbService
{
    public function generateRegistrationNumber(): string
    {
        $prefix = 'PPDB-'.date('Y').'-';
        $last = PpdbRegistration::where('registration_number', 'like', $prefix.'%')
            ->orderBy('registration_number', 'desc')
            ->first();

        if (! $last) {
            return $prefix.'0001';
        }

        $seq = (int) substr($last->registration_number, -4);

        return $prefix.str_pad($seq + 1, 4, '0', STR_PAD_LEFT);
    }

    public function register(array $data): PpdbRegistration
    {
        $data['registration_number'] = $this->generateRegistrationNumber();
        $data['status'] = PpdbRegistration::STATUS_SUBMITTED;

        return PpdbRegistration::create($data);
    }

    public function verify(int $id): PpdbRegistration
    {
        return DB::transaction(function () use ($id) {
            $reg = PpdbRegistration::findOrFail($id);
            $reg->status = PpdbRegistration::STATUS_VERIFIED;
            $reg->save();

            return $reg;
        });
    }

    public function accept(int $id): PpdbRegistration
    {
        return DB::transaction(function () use ($id) {
            $reg = PpdbRegistration::findOrFail($id);
            $reg->status = PpdbRegistration::STATUS_ACCEPTED;
            $reg->save();

            return $reg;
        });
    }

    public function reject(int $id, ?string $notes = null): PpdbRegistration
    {
        return DB::transaction(function () use ($id, $notes) {
            $reg = PpdbRegistration::findOrFail($id);
            $reg->status = PpdbRegistration::STATUS_REJECTED;
            if ($notes) {
                $reg->notes = $notes;
            }
            $reg->save();

            return $reg;
        });
    }

    public function convertToStudent(PpdbRegistration $reg): int
    {
        return DB::transaction(function () use ($reg) {
            $user = User::create([
                'name' => $reg->candidate_name,
                'email' => $reg->email ?? $reg->registration_number.'@ppdb.lava',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'department_id' => $reg->department_id,
                'nis' => 'S'.$reg->registration_number,
                'name' => $reg->candidate_name,
                'gender' => $reg->gender ?? 'L',
                'phone' => $reg->phone,
                'address' => $reg->address,
                'status' => 'active',
            ]);

            $reg->status = PpdbRegistration::STATUS_ACCEPTED;
            $reg->save();

            return $student->id;
        });
    }
}
