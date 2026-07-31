<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Create empty school record (id=1) — admin fills via portal
        School::firstOrCreate(['id' => 1], [
            'name'           => null,
            'npsn'           => null,
            'address'        => null,
            'phone'          => null,
            'email'          => null,
            'principal_name' => null,
            'principal_nip'  => null,
            'logo_path'      => null,
            'city'           => null,
            'province'       => null,
        ]);
    }
}
