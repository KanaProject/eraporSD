<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [];
        for ($grade = 1; $grade <= 6; $grade++) {
            foreach (['A', 'B', 'C'] as $section) {
                $classes[] = [
                    'grade_level' => $grade,
                    'section'     => $section,
                    'name'        => $grade . $section,
                    'is_active'   => true,
                ];
            }
        }

        foreach ($classes as $c) {
            SchoolClass::firstOrCreate(
                ['grade_level' => $c['grade_level'], 'section' => $c['section']],
                $c
            );
        }
    }
}
