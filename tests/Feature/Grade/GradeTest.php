<?php

use App\Modules\Academic\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('grade calculates final result correctly', function () {
    $grade = new Grade(['assignment_score' => 80, 'midterm_score' => 75, 'final_score' => 85, 'practice_score' => 90]);
    // Weighted: 80*0.30 + 75*0.25 + 85*0.35 + 90*0.10 = 24 + 18.75 + 29.75 + 9 = 81.5
    expect($grade->calculateFinalResult())->toBe(81.5);
    expect(Grade::getGradeLetter(95))->toBe('A');
    expect(Grade::getGradeLetter(85))->toBe('B');
    expect(Grade::getGradeLetter(75))->toBe('C');
    expect(Grade::getGradeLetter(65))->toBe('D');
    expect(Grade::getGradeLetter(50))->toBe('E');
    expect(Grade::getPredicate(95))->toBe('Sangat Baik');
    expect(Grade::getPredicate(75))->toBe('Cukup');
});

test('grade with zero scores returns zero', function () {
    $grade = new Grade;
    expect($grade->calculateFinalResult())->toBe(0.0);
});
