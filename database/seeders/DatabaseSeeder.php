<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\AssessmentPeriod;
use App\Models\Extracurricular;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SchoolSeeder::class,
            CurriculumSeeder::class,
            AcademicStructureSeeder::class,
            SchoolClassSeeder::class,
            SubjectSeeder::class,
        ]);
    }
}
