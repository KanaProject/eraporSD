<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $activeYear = AcademicYear::getActive();

        // 1. Get subjects taught by this teacher this year
        $assignments = TeacherSubjectAssignment::where('user_id', $user->id)
            ->where('academic_year_id', $activeYear?->id)
            ->with(['subject', 'schoolClass.students' => fn($q) => $q->where('is_active', true)])
            ->get();
            
        $subjectsTaught = $assignments->pluck('subject')->unique('id')->values();
        
        // 2. Get students taught
        $studentsTaught = $assignments->pluck('schoolClass.students')->flatten()->unique('id')->sortBy('name')->values();

        // 3. Get last 4 assessment periods (chronological order)
        $periods = AssessmentPeriod::orderBy('id', 'desc')->take(4)->get()->reverse()->values();
        $periodIds = $periods->pluck('id');

        // 4. Fetch all relevant grades
        $grades = Grade::whereIn('subject_id', $subjectsTaught->pluck('id'))
            ->whereIn('student_id', $studentsTaught->pluck('id'))
            ->whereIn('assessment_period_id', $periodIds)
            ->whereNotNull('nilai_pengetahuan')
            ->get();

        // 5. Prepare data for chart (JSON structure for frontend)
        $chartLabels = $periods->pluck('name')->toArray();
        $chartData   = []; // for the default view (all subjects average)
        $subjectData = []; // for detailed view (per subject and per student)

        foreach ($subjectsTaught as $subject) {
            $dataPoints = [];
            $studentPoints = [];
            
            // Initialize student arrays
            foreach ($studentsTaught as $student) {
                $studentPoints[$student->id] = array_fill(0, 4, null);
            }

            foreach ($periods as $index => $period) {
                $periodGrades = $grades->where('subject_id', $subject->id)->where('assessment_period_id', $period->id);
                $avg = $periodGrades->avg('nilai_pengetahuan');
                $dataPoints[] = $avg ? round((float) $avg, 2) : 0;
                
                foreach ($periodGrades as $grade) {
                    $studentPoints[$grade->student_id][$index] = round((float) $grade->nilai_pengetahuan, 2);
                }
            }
            
            $chartData[] = [
                'id'    => $subject->id,
                'label' => $subject->name,
                'data'  => $dataPoints,
            ];
            
            $subjectData[$subject->id] = [
                'average' => $dataPoints,
                'students'=> $studentPoints
            ];
        }

        return view('guru.dashboard', compact('activeYear', 'chartLabels', 'chartData', 'subjectData', 'subjectsTaught', 'studentsTaught'));
    }
}
