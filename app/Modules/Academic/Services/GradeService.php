<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradeService
{
    public function create(array $data): Grade
    {
        return DB::transaction(function () use ($data) {
            $grade = new Grade($data);
            $this->calculateAndSet($grade);
            $grade->save();

            return $grade;
        });
    }

    public function update(Grade $grade, array $data): Grade
    {
        return DB::transaction(function () use ($grade, $data) {
            $grade->fill($data);
            $this->calculateAndSet($grade);
            $grade->save();

            return $grade;
        });
    }

    /**
     * Bulk create/update grades for a class.
     */
    public function bulkCreate(array $data): void
    {
        DB::transaction(function () use ($data) {
            foreach ($data['grades'] as $g) {
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $g['student_id'],
                        'subject_id' => $data['subject_id'],
                        'semester_id' => $data['semester_id'],
                    ],
                    [
                        'assignment_score' => $g['assignment_score'] ?? null,
                        'midterm_score' => $g['midterm_score'] ?? null,
                        'final_score' => $g['final_score'] ?? null,
                        'practice_score' => $g['practice_score'] ?? null,
                    ]
                );
                $this->calculateAndSet($grade);
                $grade->save();
            }
        });
    }

    /**
     * Calculate weighted score and set grade_letter + predicate.
     * Weights: assignment 30%, midterm 25%, final 35%, practice 10%.
     * If components are null, calculate proportionally from available components.
     */
    protected function calculateAndSet(Grade $grade): void
    {
        $weights = [
            'assignment_score' => 0.30,
            'midterm_score' => 0.25,
            'final_score' => 0.35,
            'practice_score' => 0.10,
        ];

        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($weights as $field => $weight) {
            $score = $grade->{$field};
            if ($score !== null && $score > 0) {
                $weightedSum += $score * $weight;
                $totalWeight += $weight;
            }
        }

        $finalResult = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : 0;
        $grade->final_result = $finalResult;
        $grade->grade_letter = Grade::getGradeLetter($finalResult);
        $grade->predicate = Grade::getPredicate($finalResult);
    }
}
