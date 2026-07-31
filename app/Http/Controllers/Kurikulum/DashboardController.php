<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_subjects' => Subject::where('is_active', true)->count(),
            'total_configs'  => SubjectGradeConfig::count(),
            'total_assign'   => TeacherSubjectAssignment::count(),
        ];
        $activeYear = AcademicYear::getActive();
        // Subjects by grade level
        $subjectsByGrade = SubjectGradeConfig::with('subject')->orderBy('grade_level')->get()->groupBy('grade_level');
        return view('kurikulum.dashboard', compact('stats', 'activeYear', 'subjectsByGrade'));
    }
}
