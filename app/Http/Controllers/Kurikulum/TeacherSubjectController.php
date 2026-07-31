<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActive();
        $gradeLevel = $request->get('grade', 1);
        $classes    = SchoolClass::where('grade_level', $gradeLevel)->where('is_active', true)->get();
        $teachers   = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        $subjects   = Subject::whereHas('subjectGradeConfigs', function($q) use ($gradeLevel) {
            $q->where('grade_level', $gradeLevel);
        })->where('is_active', true)->orderBy('group')->orderBy('sort_order')->get();
        // existing assignments for this grade
        $assignments = TeacherSubjectAssignment::whereIn('school_class_id', $classes->pluck('id'))
            ->where('academic_year_id', $activeYear?->id)
            ->get()
            ->groupBy(fn($a) => $a->subject_id.'_'.$a->school_class_id);

        return view('kurikulum.assignments.index', compact(
            'classes', 'teachers', 'subjects', 'assignments', 'activeYear', 'gradeLevel'
        ));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'subject_id'       => 'required|exists:subjects,id',
            'school_class_id'  => 'required|exists:school_classes,id',
            'user_id'          => 'required|exists:users,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        TeacherSubjectAssignment::updateOrCreate(
            [
                'subject_id'       => $request->subject_id,
                'school_class_id'  => $request->school_class_id,
                'academic_year_id' => $request->academic_year_id,
            ],
            ['user_id' => $request->user_id]
        );

        return back()->with('success', 'Penugasan guru berhasil disimpan.');
    }

    public function remove(TeacherSubjectAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Penugasan berhasil dihapus.');
    }
}
