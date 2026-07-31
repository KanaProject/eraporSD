<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Grade;
use App\Models\ReportCardStatus;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'  => Student::where('is_active', true)->count(),
            'total_teachers'  => User::role('guru')->count(),
            'total_classes'   => SchoolClass::where('is_active', true)->count(),
            'total_subjects'  => Subject::where('is_active', true)->count(),
        ];

        $activeYear   = AcademicYear::getActive();
        $activeSem    = Semester::getActive();
        $activePeriod = AssessmentPeriod::getActive();

        // Grade input progress per class
        $classProgress = [];
        if ($activePeriod) {
            $classes = SchoolClass::where('is_active', true)->orderBy('grade_level')->orderBy('section')->get();
            foreach ($classes as $class) {
                $totalStudents = $class->students()->where('is_active', true)->count();
                $assignments   = TeacherSubjectAssignment::where('school_class_id', $class->id)
                    ->where('academic_year_id', $activeYear?->id)->count();
                $classProgress[] = [
                    'class'          => $class,
                    'total_students' => $totalStudents,
                    'assignments'    => $assignments,
                ];
            }
        }

        return view('admin.dashboard', compact('stats', 'activeYear', 'activeSem', 'activePeriod', 'classProgress'));
    }
}
