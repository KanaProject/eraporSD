<?php

namespace App\Http\Controllers\Walas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $activeYear = AcademicYear::getActive();

        // Get homeroom class(es)
        $assignments = HomeroomAssignment::where('user_id', $user->id)
            ->where('academic_year_id', $activeYear?->id)
            ->with('schoolClass')
            ->get();

        $myClasses = $assignments->pluck('schoolClass')->sortBy('name');
        
        $classId = request('class_id');
        if (!$classId && $myClasses->isNotEmpty()) {
            $classId = $myClasses->first()->id;
        }
        
        $myClass = $myClasses->where('id', $classId)->first();
        
        $periods = AssessmentPeriod::whereHas('semester', function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear?->id);
        })->orderBy('id')->get();
        
        $students = $myClass ? $myClass->students()->where('is_active', true)->orderBy('name')->get() : collect();
        
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->whereIn('assessment_period_id', $periods->pluck('id'))
            ->with('subject')
            ->get();
            
        // Build Subjects List from grades
        $subjects = $grades->pluck('subject')->unique('id')->sortBy('name')->values();
        
        // Data Aggregation
        $classData = [];
        $studentData = []; // student_id => [period_id => avg]
        $studentSubjectData = []; // student_id => [subject_id => [period_id => avg]]
        
        // Initialize structures
        foreach ($periods as $p) {
            $classData[$p->id] = ['sum' => 0, 'count' => 0, 'avg' => null];
            foreach ($students as $s) {
                $studentData[$s->id][$p->id] = ['sum' => 0, 'count' => 0, 'avg' => null];
                foreach ($subjects as $sub) {
                    $studentSubjectData[$s->id][$sub->id][$p->id] = null;
                }
            }
        }
        
        foreach ($grades as $g) {
            $np = $g->nilai_pengetahuan;
            $nk = $g->nilai_keterampilan;
            
            $val = null;
            if ($np !== null && $nk !== null) $val = ($np + $nk) / 2;
            elseif ($np !== null) $val = $np;
            elseif ($nk !== null) $val = $nk;
            
            if ($val !== null) {
                $studentSubjectData[$g->student_id][$g->subject_id][$g->assessment_period_id] = $val;
                
                $studentData[$g->student_id][$g->assessment_period_id]['sum'] += $val;
                $studentData[$g->student_id][$g->assessment_period_id]['count']++;
                
                $classData[$g->assessment_period_id]['sum'] += $val;
                $classData[$g->assessment_period_id]['count']++;
            }
        }
        
        // Finalize averages
        foreach ($periods as $p) {
            if ($classData[$p->id]['count'] > 0) {
                $classData[$p->id]['avg'] = round($classData[$p->id]['sum'] / $classData[$p->id]['count'], 2);
            }
            foreach ($students as $s) {
                if ($studentData[$s->id][$p->id]['count'] > 0) {
                    $studentData[$s->id][$p->id]['avg'] = round($studentData[$s->id][$p->id]['sum'] / $studentData[$s->id][$p->id]['count'], 2);
                }
            }
        }

        return view('walas.dashboard', compact(
            'myClasses', 'myClass', 'activeYear', 'periods', 'students', 'subjects',
            'classData', 'studentData', 'studentSubjectData'
        ));
    }
}
