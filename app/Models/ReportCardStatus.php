<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCardStatus extends Model
{
    protected $fillable = [
        'student_id', 'assessment_period_id', 'generated_at', 'generated_by', 'pdf_path',
    ];

    protected $casts = ['generated_at' => 'datetime'];

    public function student()          { return $this->belongsTo(Student::class); }
    public function assessmentPeriod() { return $this->belongsTo(AssessmentPeriod::class); }
    public function generatedByUser()  { return $this->belongsTo(User::class, 'generated_by'); }

    public function isGenerated(): bool
    {
        return $this->generated_at !== null;
    }
}
