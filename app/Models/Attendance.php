<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'month',
        'sakit',
        'izin',
        'alpa',
        'daily_data'
    ];

    protected $casts = [
        'daily_data' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
