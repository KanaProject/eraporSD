<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeInputController extends Controller
{
    /** Show list of subjects/classes the guru teaches */
    public function index()
    {
        $user       = Auth::user();
        $activeYear = AcademicYear::getActive();

        $assignments = TeacherSubjectAssignment::where('user_id', $user->id)
            ->where('academic_year_id', $activeYear?->id)
            ->whereHas('subject', function($q) {
                $q->where('is_active', true)->whereDoesntHave('children');
            })
            ->with(['subject', 'schoolClass'])
            ->get();

        $activePeriod = AssessmentPeriod::getActive();

        // Count graded students per assignment in active period
        $progress = [];
        foreach ($assignments as $assignment) {
            $total   = $assignment->schoolClass->students()->where('is_active', true)->count();
            $graded  = 0;
            if ($activePeriod) {
                $graded = Grade::where('assessment_period_id', $activePeriod->id)
                    ->where('subject_id', $assignment->subject_id)
                    ->whereHas('student', fn($q) => $q->where('school_class_id', $assignment->school_class_id)->where('is_active', true))
                    ->where(fn($q) => $q->whereNotNull('uh1')->orWhereNotNull('uh2')->orWhereNotNull('ujian_teori')->orWhereNotNull('ujian_praktek'))
                    ->count();
            }
            $progress[] = [
                'assignment'  => $assignment,
                'total'       => $total,
                'graded'      => $graded,
            ];
        }

        return view('guru.grades.index', compact('progress', 'activeYear', 'activePeriod'));
    }

    /** Show grade input form for a specific subject + class + period */
    public function input(Request $request)
    {
        $user = Auth::user();

        $subjectId = $request->subject_id;
        $classId   = $request->class_id;
        $periodId  = $request->period_id ?? AssessmentPeriod::getActive()?->id;

        // Security: ensure this teacher is assigned to this subject/class
        $activeYear = AcademicYear::getActive();
        $assignment = TeacherSubjectAssignment::where('user_id', $user->id)
            ->where('subject_id', $subjectId)
            ->where('school_class_id', $classId)
            ->where('academic_year_id', $activeYear?->id)
            ->firstOrFail();

        $subject = Subject::findOrFail($subjectId);
        $class   = SchoolClass::findOrFail($classId);
        $period  = AssessmentPeriod::findOrFail($periodId);

        $students = Student::where('school_class_id', $classId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Load existing grades keyed by student_id
        $grades = Grade::where('subject_id', $subjectId)
            ->where('assessment_period_id', $periodId)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        // All periods for this semester (for period switcher)
        $periods = AssessmentPeriod::whereHas('semester', fn($q) =>
            $q->whereHas('academicYear', fn($q2) => $q2->where('is_active', true))
        )->get();

        return view('guru.grades.input', compact('subject', 'class', 'period', 'students', 'grades', 'periods', 'assignment'));
    }

    /** Save grades for a subject/class/period */
    public function save(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'subject_id'         => 'required|exists:subjects,id',
            'class_id'           => 'required|exists:school_classes,id',
            'period_id'          => 'required|exists:assessment_periods,id',
            'grades'             => 'required|array',
            'grades.*.uh1'       => 'nullable|numeric|min:0|max:100',
            'grades.*.uh2'       => 'nullable|numeric|min:0|max:100',
            'grades.*.ujian_teori'   => 'nullable|numeric|min:0|max:100',
            'grades.*.ujian_praktek' => 'nullable|numeric|min:0|max:100',
        ]);

        $activeYear = AcademicYear::getActive();
        $period = AssessmentPeriod::findOrFail($request->period_id);
        if (!$period->is_active) {
            return back()->with('error', 'Gagal menyimpan: Periode penilaian ini sudah ditutup/tidak aktif.');
        }

        // Security check
        TeacherSubjectAssignment::where('user_id', $user->id)
            ->where('subject_id', $request->subject_id)
            ->where('school_class_id', $request->class_id)
            ->where('academic_year_id', $activeYear?->id)
            ->firstOrFail();

        foreach ($request->grades as $studentId => $data) {
            $uh1 = $data['uh1'] ?? null;
            $uh2 = $data['uh2'] ?? null;
            $teori = $data['ujian_teori'] ?? null;
            $praktek = $data['ujian_praktek'] ?? null;

            if ($uh1 === '' || $uh1 === null) $uh1 = null;
            if ($uh2 === '' || $uh2 === null) $uh2 = null;
            if ($teori === '' || $teori === null) $teori = null;
            if ($praktek === '' || $praktek === null) $praktek = null;

            if ($uh1 === null && $uh2 === null && $teori === null && $praktek === null) {
                Grade::where('student_id', $studentId)
                    ->where('subject_id', $request->subject_id)
                    ->where('assessment_period_id', $request->period_id)
                    ->delete();
                continue;
            }

            $grade = Grade::updateOrCreate(
                [
                    'student_id'           => $studentId,
                    'subject_id'           => $request->subject_id,
                    'assessment_period_id' => $request->period_id,
                ],
                [
                    'user_id'       => $user->id,
                    'uh1'           => $uh1,
                    'uh2'           => $uh2,
                    'ujian_teori'   => $teori,
                    'ujian_praktek' => $praktek,
                ]
            );
            $grade->recompute(); // Calculate pengetahuan & keterampilan
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
