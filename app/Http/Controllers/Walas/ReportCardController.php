<?php

namespace App\Http\Controllers\Walas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\HomeroomNote;
use App\Models\ReportCardStatus;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportCardController extends Controller
{
    private function getMyClass()
    {
        $activeYear = AcademicYear::getActive();
        $assignment = HomeroomAssignment::where('user_id', Auth::id())
            ->where('academic_year_id', $activeYear?->id)
            ->with('schoolClass')
            ->firstOrFail();
        return $assignment->schoolClass;
    }

    public function index(Request $request)
    {
        $myClass   = $this->getMyClass();
        $periodId  = $request->get('period_id', AssessmentPeriod::getActive()?->id);
        $period    = AssessmentPeriod::findOrFail($periodId);
        $periods   = AssessmentPeriod::whereHas('semester.academicYear', fn($q) => $q->where('is_active', true))->get();

        $students  = $myClass->students()->where('is_active', true)->orderBy('name')->get();

        $statuses  = ReportCardStatus::where('assessment_period_id', $period->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()->keyBy('student_id');

        return view('walas.report-cards.index', compact('myClass', 'students', 'period', 'periods', 'statuses'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'period_id'  => 'required|exists:assessment_periods,id',
        ]);

        $student = Student::with('schoolClass')->findOrFail($request->student_id);
        $period  = AssessmentPeriod::with('semester.academicYear')->findOrFail($request->period_id);
        $school  = School::getInstance();

        $gradeLevel = $student->schoolClass->grade_level;
        
        $mainSubjects = Subject::where('is_active', true)
            ->whereNull('parent_id')
            ->where(function ($q) use ($gradeLevel) {
                $q->whereHas('subjectGradeConfigs', function ($q2) use ($gradeLevel) {
                    $q2->where('grade_level', $gradeLevel);
                })->orWhereHas('children.subjectGradeConfigs', function ($q2) use ($gradeLevel) {
                    $q2->where('grade_level', $gradeLevel);
                });
            })
            ->with(['children' => function($q) use ($gradeLevel) {
                $q->whereHas('subjectGradeConfigs', function ($q2) use ($gradeLevel) {
                    $q2->where('grade_level', $gradeLevel);
                })->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('group')->orderBy('sort_order')->get();

        $grades = Grade::where('student_id', $student->id)
            ->where('assessment_period_id', $period->id)
            ->get()->keyBy('subject_id');

        $subjectIds = $mainSubjects->pluck('id')->merge($mainSubjects->flatMap->children->pluck('id'));

        $configs = SubjectGradeConfig::whereIn('subject_id', $subjectIds)
            ->where('grade_level', $gradeLevel)
            ->get()->keyBy('subject_id');

        $semester   = $period->semester;
        $note       = HomeroomNote::where('student_id', $student->id)->where('assessment_period_id', $period->id)->first();

        $walas = Auth::user();

        $template = $period->isAstsType() ? 'pdf.report-card-asts' : 'pdf.report-card-sas';

        $pdf = Pdf::loadView($template, compact(
            'student', 'period', 'semester', 'school', 'mainSubjects',
            'grades', 'configs', 'note', 'walas'
        ))->setPaper('a4', 'portrait');

        // Store PDF
        $path = "rapor/{$student->id}_{$period->code}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        ReportCardStatus::updateOrCreate(
            ['student_id' => $student->id, 'assessment_period_id' => $period->id],
            ['generated_at' => now(), 'generated_by' => Auth::id(), 'pdf_path' => $path]
        );

        return $pdf->download("Rapor_{$student->name}_{$period->name}.pdf");
    }

    public function generateAll(Request $request)
    {
        $request->validate(['period_id' => 'required|exists:assessment_periods,id']);

        $myClass  = $this->getMyClass();
        $students = $myClass->students()->where('is_active', true)->get();
        $period   = AssessmentPeriod::findOrFail($request->period_id);

        $generated = 0;
        foreach ($students as $student) {
            // We call the same logic; for bulk we just trigger downloads one by one via redirect
            // In real app this would be a zip; here we queue. For now, redirect to individual.
            $generated++;
        }
        return back()->with('success', "Silakan unduh rapor per siswa. Total: {$generated} siswa.");
    }
}
