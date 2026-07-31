<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectGradeConfig extends Model
{
    protected $fillable = ['subject_id', 'grade_level', 'kkm', 'bobot_uh', 'bobot_teori'];

    protected $casts = [
        'kkm'         => 'integer',
        'bobot_uh'    => 'integer',
        'bobot_teori' => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }



    /** Hitung nilai pengetahuan: (rata2_UH × bobot_uh%) + (teori × bobot_teori%) */
    public function computePengetahuan(?float $uh1, ?float $uh2, ?float $teori): ?float
    {
        if ($uh1 === null && $uh2 === null && $teori === null) return null;
        $uh1  = $uh1 ?? 0;
        $uh2  = $uh2 ?? 0;
        $teori = $teori ?? 0;
        $uh_avg = ($uh1 + $uh2) / 2;
        return ($uh_avg * ($this->bobot_uh / 100)) + ($teori * ($this->bobot_teori / 100));
    }
}
