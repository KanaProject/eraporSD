<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'username', 'email', 'nip', 'phone', 'bio', 'password', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /** Kelas yang diajar sebagai walas di tahun ajaran aktif */
    public function activeHomeroomClass()
    {
        $year = AcademicYear::where('is_active', true)->first();
        if (!$year) return null;
        $assignment = $this->homeroomAssignments()
            ->where('academic_year_id', $year->id)->first();
        return $assignment?->schoolClass;
    }
}
