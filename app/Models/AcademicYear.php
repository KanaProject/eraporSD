<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['curriculum_id', 'name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public static function getActive(): ?static
    {
        return static::where('is_active', true)->first();
    }
}
