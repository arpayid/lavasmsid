<?php

namespace App\Modules\PPDB\Services;

use App\Models\User;
use App\Modules\PPDB\Models\PpdbPeriod;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\Student\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

    public function activePeriod(): ?PpdbPeriod
    {
        return PpdbPeriod::query()->where('status', 'open')->where('is_active', true)->latest()->first();
    }

    public function register(array $data): PpdbRegistration
    {
        $period = $this->activePeriod();

        if (! $period) {
            throw ValidationException::withMessages(['ppdb' => 'PPDB belum dibuka.']);
        }

        $data['registration_number'] = $this->generateRegistrationNumber();
        $data['ppdb_period_id'] = $period->id;
        $data['status'] = PpdbRegistration::STATUS_SUBMITTED;
        $data['candidate_name'] = $data['candidate_name'] ?? ($data['name'] ?? null);
        $data['name'] = $data['name'] ?? ($data['candidate_name'] ?? null);
        $data['department_id'] = $data['department_id'] ?? ($data['chosen_department_id'] ?? null);

        return PpdbRegistration::create($data);
    }

    public function verify(int $id, ?string $note = null): PpdbRegistration
    {
        return DB::transaction(function () use ($id, $note) {
            $reg = PpdbRegistration::findOrFail($id);
            $reg->status = PpdbRegistration::STATUS_VERIFIED;
            $reg->verification_note = $note ?? $reg->verification_note;
            $reg->save();

            return $reg;
        });
    }

    public function accept(int $id): PpdbRegistration
    {
        return DB::transaction(function () use ($id) {
            $reg = PpdbRegistration::findOrFail($id);
            $reg->status = PpdbRegistration::STATUS_ACCEPTED;
            $reg->accepted_at = now();
            $reg->save();

            return $reg;
        });
    }

    public function reject(int $id, ?string $notes = null): PpdbRegistration
    {
        return DB::transaction(function () use ($id, $notes) {
            $reg = PpdbRegistration::findOrFail($id);
            $reg->status = PpdbRegistration::STATUS_REJECTED;
            $reg->verification_note = $notes;
            $reg->notes = $notes;
            $reg->save();

            return $reg;
        });
    }

    public function convertToStudent(PpdbRegistration $reg): int
    {
        return DB::transaction(function () use ($reg) {
            $reg->refresh();

            if ($reg->status !== PpdbRegistration::STATUS_ACCEPTED) {
                throw ValidationException::withMessages(['status' => 'Hanya pendaftar diterima yang dapat dikonversi.']);
            }

            if ($reg->converted_at) {
                throw ValidationException::withMessages(['status' => 'Pendaftar sudah pernah dikonversi.']);
            }

            $user = null;
            if ($reg->email) {
                $user = User::firstOrCreate(
                    ['email' => $reg->email],
                    [
                        'name' => $reg->name ?? $reg->candidate_name,
                        'password' => Hash::make('password'),
                        'is_active' => true,
                    ]
                );
            }

            $student = Student::create([
                'user_id' => $user?->id,
                'department_id' => $reg->chosen_department_id ?? $reg->department_id,
                'classroom_id' => $reg->chosen_classroom_id,
                'nis' => $this->generateStudentNis(),
                'nisn' => $reg->nisn,
                'name' => $reg->name ?? $reg->candidate_name,
                'gender' => $reg->gender ?? 'L',
                'birth_place' => $reg->birth_place,
                'birth_date' => $reg->birth_date,
                'religion' => $reg->religion,
                'phone' => $reg->phone,
                'address' => $reg->address,
                'status' => 'active',
            ]);

            $reg->status = PpdbRegistration::STATUS_CONVERTED;
            $reg->converted_at = now();
            $reg->save();

            return $student->id;
        });
    }

    protected function generateStudentNis(): string
    {
        $prefix = 'PPDB'.date('Y');
        $count = Student::query()->where('nis', 'like', $prefix.'%')->count() + 1;

        return $prefix.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
}
