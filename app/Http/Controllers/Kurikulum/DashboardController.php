<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\ReportCardStatus;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_subjects' => Subject::where('is_active', true)->count(),
            'total_configs'  => SubjectGradeConfig::count(),
            'total_assign'   => TeacherSubjectAssignment::count(),
            'total_classes'  => SchoolClass::count(),
            'total_teachers' => User::role('guru')->count(),
        ];
        
        $activeYear = AcademicYear::getActive();
        
        $periods = AssessmentPeriod::orderBy('id', 'asc')->get();
        $periodId = $request->period_id;
        $selectedPeriod = $periodId ? AssessmentPeriod::find($periodId) : AssessmentPeriod::getActive();

        // 1. Grading Progress (Guru)
        $teacherAssignments = TeacherSubjectAssignment::where('academic_year_id', $activeYear?->id)
            ->with(['user', 'subject', 'schoolClass'])
            ->get();
            
        $pendingGrading = [];
        $totalAssignments = $teacherAssignments->count();
        $completedAssignments = 0;

        if ($selectedPeriod) {
            foreach ($teacherAssignments as $assignment) {
                $studentIds = $assignment->schoolClass->students()->where('is_active', true)->pluck('id');
                $totalStudents = $studentIds->count();
                
                if ($totalStudents == 0) {
                    $completedAssignments++; 
                    continue; 
                }

                $gradedStudents = Grade::where('assessment_period_id', $selectedPeriod->id)
                    ->where('subject_id', $assignment->subject_id)
                    ->whereIn('student_id', $studentIds)
                    ->where(function($q) {
                        $q->whereNotNull('uh1')->orWhereNotNull('uh2')
                          ->orWhereNotNull('ujian_teori')->orWhereNotNull('ujian_praktek');
                    })
                    ->count();

                if ($gradedStudents >= $totalStudents) {
                    $completedAssignments++;
                } else {
                    $pendingGrading[] = [
                        'teacher' => $assignment->user->name,
                        'subject' => $assignment->subject->name,
                        'class'   => $assignment->schoolClass->name,
                        'graded'  => $gradedStudents,
                        'total'   => $totalStudents,
                    ];
                }
            }
        }

        $gradingProgress = $totalAssignments > 0 
            ? round(($completedAssignments / $totalAssignments) * 100) : 0;

        // 2. Report Card Progress (Walas)
        $homeroomAssignments = HomeroomAssignment::where('academic_year_id', $activeYear?->id)
            ->with(['user', 'schoolClass'])
            ->get();
        
        $walasProgress = [];
        $totalStudentsAll = 0;
        $totalReportCardsGenerated = 0;

        if ($selectedPeriod) {
            foreach ($homeroomAssignments as $assignment) {
                $studentIds = $assignment->schoolClass->students()->where('is_active', true)->pluck('id');
                $totalClassStudents = $studentIds->count();
                $totalStudentsAll += $totalClassStudents;
                
                if ($totalClassStudents == 0) continue;

                $generatedCards = ReportCardStatus::where('assessment_period_id', $selectedPeriod->id)
                    ->whereIn('student_id', $studentIds)
                    ->whereNotNull('generated_at')
                    ->count();
                
                $totalReportCardsGenerated += $generatedCards;

                $walasProgress[] = [
                    'walas'   => $assignment->user->name,
                    'class'   => $assignment->schoolClass->name,
                    'printed' => $generatedCards,
                    'total'   => $totalClassStudents,
                ];
            }
        }

        $reportProgress = $totalStudentsAll > 0
            ? round(($totalReportCardsGenerated / $totalStudentsAll) * 100) : 0;

        return view('kurikulum.dashboard', compact(
            'stats', 'activeYear', 'periods', 'selectedPeriod',
            'pendingGrading', 'gradingProgress', 'totalAssignments', 'completedAssignments',
            'walasProgress', 'reportProgress', 'totalStudentsAll', 'totalReportCardsGenerated'
        ));
    }
}
