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
        $activePeriod = AssessmentPeriod::getActive();

        // 1. Get assignments for this teacher this year
        $assignments = TeacherSubjectAssignment::where('user_id', $user->id)
            ->where('academic_year_id', $activeYear?->id)
            ->with(['subject', 'schoolClass'])
            ->get();
            
        $subjectsTaught = $assignments->pluck('subject')->unique('id')->values();
        $classesTaught = $assignments->pluck('schoolClass')->unique('id')->sortBy('name')->values();
        
        $classId = request('class_id');
        if (!$classId && $classesTaught->isNotEmpty()) {
            $classId = $classesTaught->first()->id;
        }

        $selectedClass = $classesTaught->where('id', $classId)->first();
        
        $students = collect();
        $tableGrades = collect();
        $chartLabels = [];
        $chartData = [];
        $subjectForClass = null;

        if ($selectedClass) {
            $students = $selectedClass->students()->where('is_active', true)->orderBy('name')->get();
            $subjectsForClass = $assignments->where('school_class_id', $classId)->pluck('subject')->unique('id')->values();
            $subjectForClass = $subjectsForClass->first(); // Pick the first subject taught in this class

            if ($subjectForClass && $activePeriod) {
                $tableGrades = Grade::where('subject_id', $subjectForClass->id)
                    ->where('assessment_period_id', $activePeriod->id)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->get()
                    ->keyBy('student_id');
            }

            // Chart: Class average over last 4 periods
            $periods = AssessmentPeriod::orderBy('id', 'desc')->take(4)->get()->reverse()->values();
            $chartLabels = $periods->pluck('name')->toArray();
            
            if ($subjectForClass) {
                foreach ($periods as $period) {
                    $avg = Grade::where('subject_id', $subjectForClass->id)
                        ->where('assessment_period_id', $period->id)
                        ->whereIn('student_id', $students->pluck('id'))
                        ->avg('nilai_pengetahuan');
                    $chartData[] = $avg ? round((float) $avg, 2) : 0;
                }
            }
        }

        return view('guru.dashboard', compact(
            'activeYear', 
            'activePeriod', 
            'classesTaught', 
            'subjectsTaught', 
            'selectedClass', 
            'subjectForClass',
            'students', 
            'tableGrades',
            'chartLabels',
            'chartData'
        ));
    }
}
