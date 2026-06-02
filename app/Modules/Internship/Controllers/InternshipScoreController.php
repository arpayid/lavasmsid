<?php

namespace App\Modules\Internship\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Internship\Models\Internship;
use App\Modules\Internship\Models\InternshipScore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InternshipScoreController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('internship.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'internship_id' => ['required', 'exists:internships,id'],
            'discipline_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'skill_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'attitude_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'report_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessed_by' => ['nullable', 'string', 'max:255'],
            'assessed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $internship = Internship::findOrFail($validated['internship_id']);

        if ($internship->status === 'cancelled') {
            return back()->with('error', 'Tidak dapat memberikan nilai untuk PKL yang dibatalkan.');
        }

        if ($internship->status === 'planned') {
            return back()->with('error', 'Tidak dapat memberikan nilai untuk PKL yang belum berjalan.');
        }

        // Calculate final score & predicate
        $scores = collect([
            $validated['discipline_score'],
            $validated['skill_score'],
            $validated['attitude_score'],
            $validated['report_score'],
        ])->filter(fn ($s) => ! is_null($s));

        $finalScore = $scores->count() > 0 ? $scores->average() : 0;
        $predicate = InternshipScore::calculatePredicate($finalScore);

        DB::transaction(function () use ($validated, $internship, $finalScore, $predicate) {
            $internship->score()->updateOrCreate(
                ['internship_id' => $internship->id],
                array_merge($validated, [
                    'final_score' => $finalScore,
                    'predicate' => $predicate,
                ])
            );

            // Sync with main internship grade
            $internship->update(['grade' => $finalScore]);
        });

        return back()->with('success', 'Penilaian PKL berhasil disimpan.');
    }
}
