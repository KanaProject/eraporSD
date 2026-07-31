<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentPeriod extends Model
{
    protected $fillable = ['semester_id', 'code', 'name', 'is_active', 'report_place', 'report_date'];

    protected $casts = [
        'is_active' => 'boolean',
        'report_date' => 'date',
    ];

    // ASTS periods use "Sumatif 1/2"; SAS/SAT use "Pengetahuan/Keterampilan"
    public const ASTS_CODES = ['ASTS_GANJIL', 'ASTS_GENAP'];
    public const SAS_CODES  = ['SAS', 'SAT'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function reportCardStatuses()
    {
        return $this->hasMany(ReportCardStatus::class);
    }

    public function isAstsType(): bool
    {
        return in_array($this->code, self::ASTS_CODES);
    }

    public function labelPengetahuan(): string
    {
        return $this->isAstsType() ? 'Sumatif 1' : 'Pengetahuan';
    }

    public function labelKeterampilan(): string
    {
        return $this->isAstsType() ? 'Sumatif 2' : 'Keterampilan';
    }

    public static function getActive(): ?static
    {
        return static::where('is_active', true)->first();
    }
}
