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

        $data = $this->getPdfData($request->student_id, $request->period_id);
        $student = $data['student'];
        $period = $data['period'];
        $template = $data['template'];

        $pdf = Pdf::loadView($template, $data)->setPaper('a4', 'portrait');

        // Store PDF
        $path = "rapor/{$student->id}_{$period->code}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        ReportCardStatus::updateOrCreate(
            ['student_id' => $student->id, 'assessment_period_id' => $period->id],
            ['generated_at' => now(), 'generated_by' => Auth::id(), 'pdf_path' => $path]
        );

        return $pdf->download("Rapor_{$student->name}_{$period->name}.pdf");
    }

    public function preview($student_id, $period_id)
    {
        $data = $this->getPdfData($student_id, $period_id);
        $pdf = Pdf::loadView($data['template'], $data)->setPaper('a4', 'portrait');
        
        return $pdf->stream("Preview_{$data['student']->name}.pdf");
    }

    private function getPdfData($student_id, $period_id)
    {
        $student = Student::with('schoolClass')->findOrFail($student_id);
        $period  = AssessmentPeriod::with('semester.academicYear')->findOrFail($period_id);
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

        // Determine the 3 months for this specific period
        $periodMonths = match($period->code) {
            'ASTS_GANJIL' => [7, 8, 9],
            'SAS'         => [10, 11, 12],
            'ASTS_GENAP'  => [1, 2, 3],
            'SAT'         => [4, 5, 6],
            default       => ($semester->type === 'ganjil' ? [7, 8, 9] : [1, 2, 3]),
        };

        $academicYear = $semester->academicYear;
        $parts = explode('/', $academicYear->name ?? '2024/2025');
        $startYear = (int) $parts[0];
        $endYear   = count($parts) > 1 ? (int) $parts[1] : $startYear + 1;

        // Fetch attendance records with daily_data for those 3 months
        $attendanceRecords = \App\Models\Attendance::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->whereIn('month', $periodMonths)
            ->get()
            ->keyBy('month');

        $totalH = 0; $totalS = 0; $totalI = 0; $totalA = 0; $totalL = 0; $totalDays = 0;

        foreach ($periodMonths as $m) {
            $year = ($m >= 7 && $m <= 12) ? $startYear : $endYear;
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $year);
            $record    = $attendanceRecords->get($m);
            $dailyData = $record ? ($record->daily_data ?? []) : [];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $status = $dailyData[$d] ?? 'H';
                $totalDays++;
                match($status) {
                    'S'     => $totalS++,
                    'I'     => $totalI++,
                    'A'     => $totalA++,
                    'L'     => $totalL++,
                    default => $totalH++,
                };
            }
        }

        $schoolDays          = $totalDays - $totalL;
        $attendancePercentage = $schoolDays > 0 ? round(($totalH / $schoolDays) * 100, 1) : 0;

        // Keep $attendance for backward compat (sakit/izin/alpa totals from period)
        $attendance = (object) ['sakit' => $totalS, 'izin' => $totalI, 'alpa' => $totalA];
        $attendanceStats = compact('totalH', 'totalS', 'totalI', 'totalA', 'totalL', 'totalDays', 'schoolDays', 'attendancePercentage', 'periodMonths');

        $walas = Auth::user();
        $template = $period->isAstsType() ? 'pdf.report-card-asts' : 'pdf.report-card-sas';

        return compact('student', 'period', 'semester', 'school', 'mainSubjects', 'grades', 'configs', 'note', 'walas', 'attendance', 'attendanceStats', 'template');
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
