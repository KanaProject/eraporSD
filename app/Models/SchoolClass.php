<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = ['grade_level', 'section', 'name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class);
    }

    public function subjectGradeConfigs()
    {
        return $this->hasMany(SubjectGradeConfig::class);
    }

    /** Walas aktif di tahun ajaran aktif */
    public function activeWalas()
    {
        $year = AcademicYear::getActive();
        if (!$year) return null;
        $ha = $this->homeroomAssignments()
            ->where('academic_year_id', $year->id)->with('user')->first();
        return $ha?->user;
    }
}
