<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        Curriculum::firstOrCreate(['name' => 'Kurikulum Merdeka'], ['is_active' => true]);
        Curriculum::firstOrCreate(['name' => 'Kurikulum 2013'], ['is_active' => true]);
    }
}
