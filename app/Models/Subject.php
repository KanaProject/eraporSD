<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['parent_id', 'name', 'code', 'group', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_id');
    }

    public function subjectGradeConfigs()
    {
        return $this->hasMany(SubjectGradeConfig::class);
    }

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function configForGrade(int $gradeLevel): ?SubjectGradeConfig
    {
        return $this->subjectGradeConfigs()->where('grade_level', $gradeLevel)->first();
    }
}
