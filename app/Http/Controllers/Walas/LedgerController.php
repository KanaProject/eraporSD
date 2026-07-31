<?php

namespace App\Http\Controllers\Walas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActive();
        $assignment = HomeroomAssignment::where('user_id', Auth::id())
            ->where('academic_year_id', $activeYear?->id)
            ->with('schoolClass')
            ->firstOrFail();
        $myClass = $assignment->schoolClass;

        $periodId = $request->get('period_id', AssessmentPeriod::getActive()?->id);
        $period   = AssessmentPeriod::findOrFail($periodId);
        $periods  = AssessmentPeriod::whereHas('semester.academicYear', fn($q) => $q->where('is_active', true))->get();

        $students = $myClass->students()->where('is_active', true)->orderBy('name')->get();
        $gradeLevel = $myClass->grade_level;
        $subjects = Subject::whereHas('subjectGradeConfigs', function($q) use ($gradeLevel) {
            $q->where('grade_level', $gradeLevel);
        })->where('is_active', true)->orderBy('group')->orderBy('sort_order')->get();

        // Build ledger matrix: student_id → subject_id → grade
        $ledger = [];
        $gradeData = Grade::where('assessment_period_id', $period->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get();

        foreach ($students as $student) {
            $ledger[$student->id] = [];
            foreach ($subjects as $subject) {
                $ledger[$student->id][$subject->id] = $gradeData->first(
                    fn($g) => $g->student_id == $student->id && $g->subject_id == $subject->id
                );
            }
        }

        // Configs (KKM) per subject
        $configs = SubjectGradeConfig::whereIn('subject_id', $subjects->pluck('id'))
            ->where('grade_level', $gradeLevel)
            ->get()->keyBy('subject_id');

        return view('walas.ledger.index', compact(
            'myClass', 'students', 'subjects', 'period', 'periods', 'ledger', 'configs'
        ));
    }
}
