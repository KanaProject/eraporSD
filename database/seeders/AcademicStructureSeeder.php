<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Curriculum;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class AcademicStructureSeeder extends Seeder
{
    public function run(): void
    {
        $curriculum = Curriculum::where('name', 'Kurikulum Merdeka')->first();

        // Tahun ajaran 2024/2025 (aktif)
        $year = AcademicYear::firstOrCreate(
            ['name' => '2024/2025'],
            [
                'curriculum_id' => $curriculum?->id,
                'is_active' => true
            ]
        );

        // Semester Ganjil (aktif)
        $ganjil = Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'type' => 'ganjil'],
            ['name' => 'Ganjil', 'is_active' => true]
        );

        // Semester Genap
        $genap = Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'type' => 'genap'],
            ['name' => 'Genap', 'is_active' => false]
        );

        // Assessment Periods untuk Ganjil
        AssessmentPeriod::firstOrCreate(
            ['semester_id' => $ganjil->id, 'code' => 'ASTS_GANJIL'],
            ['name' => 'ASTS Ganjil', 'is_active' => true]
        );
        AssessmentPeriod::firstOrCreate(
            ['semester_id' => $ganjil->id, 'code' => 'SAS'],
            ['name' => 'Sumatif Akhir Semester (SAS)', 'is_active' => false]
        );

        // Assessment Periods untuk Genap
        AssessmentPeriod::firstOrCreate(
            ['semester_id' => $genap->id, 'code' => 'ASTS_GENAP'],
            ['name' => 'ASTS Genap', 'is_active' => false]
        );
        AssessmentPeriod::firstOrCreate(
            ['semester_id' => $genap->id, 'code' => 'SAT'],
            ['name' => 'Sumatif Akhir Tahun (SAT)', 'is_active' => false]
        );
    }
}
