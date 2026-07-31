<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAssignment extends Model
{
    protected $fillable = ['user_id', 'subject_id', 'school_class_id', 'academic_year_id'];

    public function user()        { return $this->belongsTo(User::class); }
    public function subject()     { return $this->belongsTo(Subject::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class); }
    public function academicYear(){ return $this->belongsTo(AcademicYear::class); }
}
