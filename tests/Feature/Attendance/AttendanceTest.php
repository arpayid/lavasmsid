<?php

use App\Modules\Academic\Models\Attendance;
use App\Modules\Student\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('attendance can have valid statuses', function () {
    expect(Attendance::STATUS_PRESENT)->toBe('present');
    expect(Attendance::STATUS_SICK)->toBe('sick');
    expect(Attendance::STATUS_ABSENT)->toBe('absent');
});

test('unique attendance per student per day constraint exists', function () {
    $student = Student::create(['nis' => 'ATN'.time(), 'name' => 'Test', 'gender' => 'L', 'status' => 'active']);
    Attendance::create(['student_id' => $student->id, 'attendance_date' => '2026-05-31', 'status' => 'present']);
    expect(fn () => Attendance::create(['student_id' => $student->id, 'attendance_date' => '2026-05-31', 'status' => 'sick']))->toThrow(QueryException::class);
});
