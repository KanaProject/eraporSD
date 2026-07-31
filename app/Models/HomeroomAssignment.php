<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeroomAssignment extends Model
{
    protected $fillable = ['user_id', 'companion_id', 'school_class_id', 'academic_year_id'];

    public function user()       { return $this->belongsTo(User::class); }
    public function companion()  { return $this->belongsTo(User::class, 'companion_id'); }
    public function schoolClass(){ return $this->belongsTo(SchoolClass::class); }
    public function academicYear(){ return $this->belongsTo(AcademicYear::class); }
}
