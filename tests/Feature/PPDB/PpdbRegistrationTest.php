<?php

use App\Modules\PPDB\Models\PpdbRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ppdb registration can be created', function () {
    $registration = PpdbRegistration::create([
        'registration_number' => 'PPDB-TEST-' . time(),
        'candidate_name' => 'Siswa Baru',
        'status' => 'submitted',
    ]);
    expect($registration)->toBeInstanceOf(PpdbRegistration::class);
});

test('ppdb registration number is unique', function () {
    PpdbRegistration::create(['registration_number' => 'PPDB-UNIQUE-001', 'candidate_name' => 'A', 'status' => 'submitted']);
    expect(fn() => PpdbRegistration::create(['registration_number' => 'PPDB-UNIQUE-001', 'candidate_name' => 'B', 'status' => 'submitted']))->toThrow(Illuminate\Database\QueryException::class);
});
