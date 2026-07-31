<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeroomNote extends Model
{
    protected $fillable = ['student_id', 'assessment_period_id', 'user_id', 'note', 'character_desc'];

    public function student()  { return $this->belongsTo(Student::class); }
    public function assessmentPeriod() { return $this->belongsTo(AssessmentPeriod::class); }
    public function user()     { return $this->belongsTo(User::class); }
}
