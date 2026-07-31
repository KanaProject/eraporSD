<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Kurikulum Merdeka SD subjects per group
        $subjects = [
            // Kelompok A (Umum)
            [
                'group' => 'A. Mata Pelajaran',
                'name' => 'Pendidikan Agama Islam', 
                'code' => 'PAI',   
                'grades' => [1,2,3,4,5,6], 
                'order' => 1,
                'children' => [
                    ['name' => 'Aqidah Akhlak', 'code' => 'AA', 'order' => 1],
                    ['name' => 'Fiqih', 'code' => 'FQ', 'order' => 2],
                    ['name' => 'Al Qur\'an Hadits', 'code' => 'QH', 'order' => 3],
                    ['name' => 'Tahfizhul Qur\'an (TQ)', 'code' => 'TQ', 'order' => 4],
                    ['name' => 'Sejarah Kebudayaan Islam', 'code' => 'SKI', 'order' => 5],
                    ['name' => 'Bahasa Arab', 'code' => 'BA', 'order' => 6],
                    ['name' => 'BTQ (Baca Tulis Qur\'an)', 'code' => 'BTQ', 'order' => 7],
                ]
            ],
            ['group' => 'A. Mata Pelajaran', 'name' => 'Pendidikan Pancasila',               'code' => 'PP',    'grades' => [1,2,3,4,5,6], 'order' => 2],
            ['group' => 'A. Mata Pelajaran', 'name' => 'Bahasa Indonesia',                   'code' => 'BIND',  'grades' => [1,2,3,4,5,6], 'order' => 3],
            ['group' => 'A. Mata Pelajaran', 'name' => 'Matematika',                         'code' => 'MTK',   'grades' => [1,2,3,4,5,6], 'order' => 4],
            ['group' => 'A. Mata Pelajaran', 'name' => 'Ilmu Pengetahuan Alam dan Sosial',  'code' => 'IPAS',  'grades' => [3,4,5,6],     'order' => 5],
            ['group' => 'A. Mata Pelajaran', 'name' => 'Seni dan Budaya',           'code' => 'SB',  'grades' => [1,2,3,4,5,6], 'order' => 6],
            ['group' => 'A. Mata Pelajaran', 'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'code' => 'PJOK',  'grades' => [1,2,3,4,5,6], 'order' => 7],
            
            // Kelompok B (Muatan Lokal)
            ['group' => 'B. Muatan Lokal', 'name' => 'Bahasa Inggris',                    'code' => 'BING',  'grades' => [4,5,6],       'order' => 1],
            ['group' => 'B. Muatan Lokal', 'name' => 'Bahasa Sunda',                      'code' => 'BSND', 'grades' => [1,2,3,4,5,6], 'order' => 2],
            ['group' => 'B. Muatan Lokal', 'name' => 'Teknologi Informasi dan Komunikasi', 'code' => 'TIK', 'grades' => [1,2,3,4,5,6], 'order' => 3],
        ];

        foreach ($subjects as $s) {
            $subject = Subject::firstOrCreate(
                ['code' => $s['code']],
                [
                    'name'       => $s['name'],
                    'group'      => $s['group'],
                    'sort_order' => $s['order'],
                    'is_active'  => true,
                ]
            );

            // Create sub-subjects if any
            if (isset($s['children'])) {
                foreach ($s['children'] as $child) {
                    $childSubject = Subject::firstOrCreate(
                        ['code' => $child['code']],
                        [
                            'parent_id'  => $subject->id,
                            'name'       => $child['name'],
                            'group'      => $s['group'],
                            'sort_order' => $child['order'],
                            'is_active'  => true,
                        ]
                    );

                    // Map children to grades
                    foreach ($s['grades'] as $grade) {
                        SubjectGradeConfig::firstOrCreate(
                            ['subject_id' => $childSubject->id, 'grade_level' => $grade],
                            ['kkm' => 70.00, 'bobot_uh' => 50.00, 'bobot_teori' => 50.00]
                        );
                    }
                }
            } else {
                // Map main subject to grades if no children
                foreach ($s['grades'] as $grade) {
                    SubjectGradeConfig::firstOrCreate(
                        ['subject_id' => $subject->id, 'grade_level' => $grade],
                        ['kkm' => 70.00, 'bobot_uh' => 50.00, 'bobot_teori' => 50.00]
                    );
                }
            }
        }
    }
}
