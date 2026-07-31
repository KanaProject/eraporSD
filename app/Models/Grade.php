<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'assessment_period_id', 'user_id',
        'uh1', 'uh2', 'ujian_teori', 'ujian_praktek',
        'nilai_pengetahuan', 'nilai_keterampilan',
    ];

    protected $casts = [
        'uh1'               => 'decimal:2',
        'uh2'               => 'decimal:2',
        'ujian_teori'       => 'decimal:2',
        'ujian_praktek'     => 'decimal:2',
        'nilai_pengetahuan' => 'decimal:2',
        'nilai_keterampilan'=> 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assessmentPeriod()
    {
        return $this->belongsTo(AssessmentPeriod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Display nilai_pengetahuan rounded (tampilan rapor tanpa koma) */
    public function getPengetahuanDisplayAttribute(): string
    {
        return $this->nilai_pengetahuan !== null
            ? (string) round((float) $this->nilai_pengetahuan)
            : '-';
    }

    /** Display nilai_keterampilan rounded */
    public function getKeterampilanDisplayAttribute(): string
    {
        return $this->nilai_keterampilan !== null
            ? (string) round((float) $this->nilai_keterampilan)
            : '-';
    }

    /** Predikat Tuntas/Belum Tuntas berdasarkan KKM */
    public function getPredikatPengetahuanAttribute(): string
    {
        if ($this->nilai_pengetahuan === null) return '-';
        $config = SubjectGradeConfig::where('subject_id', $this->subject_id)
            ->where('grade_level', $this->student->schoolClass->grade_level)
            ->first();
        if (!$config) return '-';
        return (float) $this->nilai_pengetahuan >= (float) $config->kkm ? 'Tuntas' : 'Belum Tuntas';
    }

    /** Recompute and save nilai_pengetahuan & nilai_keterampilan */
    public function recompute(): void
    {
        $config = SubjectGradeConfig::where('subject_id', $this->subject_id)
            ->where('grade_level', $this->student->schoolClass->grade_level)
            ->first();

        if ($config) {
            $this->nilai_pengetahuan = $config->computePengetahuan(
                $this->uh1 !== null ? (float) $this->uh1 : null,
                $this->uh2 !== null ? (float) $this->uh2 : null,
                $this->ujian_teori !== null ? (float) $this->ujian_teori : null,
            );
        }
        $this->nilai_keterampilan = $this->ujian_praktek;
        $this->saveQuietly();
    }
}
