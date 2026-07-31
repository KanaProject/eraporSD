<?php

namespace App\Http\Controllers\Walas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Extracurricular;
use App\Models\HomeroomAssignment;
use App\Models\HomeroomNote;
use App\Models\Semester;
use App\Models\AssessmentPeriod;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeroomNoteController extends Controller
{
    private function getMyClass()
    {
        $activeYear = AcademicYear::getActive();
        $assignment = HomeroomAssignment::where('user_id', Auth::id())
            ->where('academic_year_id', $activeYear?->id)->with('schoolClass')->firstOrFail();
        return $assignment->schoolClass;
    }

    public function index(Request $request)
    {
        $myClass   = $this->getMyClass();
        $activeYear = AcademicYear::getActive();
        
        $periods = AssessmentPeriod::whereHas('semester', function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear?->id);
        })->get();

        $activePeriod = AssessmentPeriod::getActive();
        $periodId = $request->get('period_id', $activePeriod?->id ?? $periods->first()?->id);
        $period = AssessmentPeriod::findOrFail($periodId);

        $students  = $myClass->students()->where('is_active', true)->orderBy('name')->get();

        $notes = HomeroomNote::where('assessment_period_id', $periodId)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()->keyBy('student_id');

        return view('walas.notes.index', compact('myClass', 'students', 'notes', 'period', 'periods', 'activeYear'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:assessment_periods,id',
            'notes'       => 'array',
        ]);

        $period = AssessmentPeriod::findOrFail($request->period_id);
        if (!$period->is_active) {
            return back()->with('error', 'Gagal menyimpan: Periode ini sudah ditutup/tidak aktif.');
        }

        foreach ($request->notes ?? [] as $studentId => $data) {
            HomeroomNote::updateOrCreate(
                ['student_id' => $studentId, 'assessment_period_id' => $request->period_id],
                [
                    'user_id'        => Auth::id(),
                    'note'           => $data['note'] ?? null,
                    'character_desc' => $data['character_desc'] ?? null,
                ]
            );
        }
        return back()->with('success', 'Catatan wali kelas berhasil disimpan.');
    }
}
