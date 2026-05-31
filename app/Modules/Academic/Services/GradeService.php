<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Grade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GradeService
{
    /**
     * Create or update grade with auto calculation.
     */
    public function saveGrade(array $data): Grade
    {
        return DB::transaction(function () use ($data) {
            $grade = Grade::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'subject_id' => $data['subject_id'],
                    'semester_id' => $data['semester_id'],
                ],
                [
                    'assignment_score' => $data['assignment_score'] ?? 0,
                    'midterm_score' => $data['midterm_score'] ?? 0,
                    'final_score' => $data['final_score'] ?? 0,
                    'practice_score' => $data['practice_score'] ?? 0,
                ]
            );

            $grade->final_result = $grade->calculateFinalResult();
            $grade->save();

            return $grade;
        });
    }

    /**
     * Get grades for a student in a semester.
     */
    public function getStudentGrades(int $studentId, int $semesterId): Collection
    {
        return Grade::with(['subject'])
            ->where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->get();
    }

    /**
     * Get class grades for a subject in a semester.
     */
    public function getClassGrades(int $classroomId, int $subjectId, int $semesterId): Collection
    {
        return Grade::with(['student'])
            ->whereHas('student', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            })
            ->where('subject_id', $subjectId)
            ->where('semester_id', $semesterId)
            ->get();
    }

    /**
     * Get grade recap for a classroom in a semester.
     */
    public function getClassRecap(int $classroomId, int $semesterId): Collection
    {
        return Grade::with(['student', 'subject'])
            ->whereHas('student', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            })
            ->where('semester_id', $semesterId)
            ->orderBy('student_id')
            ->get()
            ->groupBy('student_id');
    }
}
