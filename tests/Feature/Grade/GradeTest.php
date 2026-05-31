<?php

use App\Modules\Academic\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('grade calculates final result correctly', function () {
    $grade = new Grade(['assignment_score' => 80, 'midterm_score' => 75, 'final_score' => 85, 'practice_score' => 90]);
    expect($grade->calculateFinalResult())->toBe(82.5);
});

test('grade with zero scores returns zero', function () {
    $grade = new Grade();
    expect($grade->calculateFinalResult())->toBe(0.0);
});
