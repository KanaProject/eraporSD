<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'school_class_id', 'nis', 'nisn', 'name', 'gender',
        'birth_place', 'birth_date', 'religion', 'address',
        'parent_name', 'parent_phone', 'photo_path', 'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active'  => 'boolean',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }



    public function homeroomNote()
    {
        return $this->hasMany(HomeroomNote::class);
    }

    public function reportCardStatuses()
    {
        return $this->hasMany(ReportCardStatus::class);
    }

    /** Nilai untuk periode tertentu, dikelompokkan per mapel */
    public function gradesForPeriod(int $periodId)
    {
        return $this->grades()
            ->where('assessment_period_id', $periodId)
            ->with('subject')
            ->get()
            ->keyBy('subject_id');
    }
}
