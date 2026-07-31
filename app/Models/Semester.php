<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'type', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function assessmentPeriods()
    {
        return $this->hasMany(AssessmentPeriod::class);
    }



    public function homeroomNotes()
    {
        return $this->hasMany(HomeroomNote::class);
    }

    public static function getActive(): ?static
    {
        return static::where('is_active', true)->first();
    }
}
