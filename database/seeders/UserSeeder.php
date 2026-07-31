<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'      => 'Administrator',
                'email'     => null,
                'password'  => Hash::make('admin123'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        // Kurikulum
        $kur = User::updateOrCreate(
            ['username' => 'kurikulum'],
            [
                'name'      => 'Staff Kurikulum',
                'email'     => null,
                'password'  => Hash::make('12345678'),
                'is_active' => true,
            ]
        );
        $kur->syncRoles(['kurikulum']);

        // Sample teachers (dual-role: guru + walas)
        $teachers = [
            [
                'username' => 'budi.santoso',
                'name'     => 'Budi Santoso, S.Pd.',
                'nip'      => '198001012005011001',
                'roles'    => ['guru', 'walas'],
            ],
            [
                'username' => 'siti.rahayu',
                'name'     => 'Siti Rahayu, S.Pd.',
                'nip'      => '198505152010012002',
                'roles'    => ['guru'],
            ],
            [
                'username' => 'ahmad.fauzi',
                'name'     => 'Ahmad Fauzi, S.Pd.I.',
                'nip'      => '199002202015011003',
                'roles'    => ['guru', 'walas'],
            ],
        ];

        foreach ($teachers as $t) {
            $user = User::updateOrCreate(
                ['username' => $t['username']],
                [
                    'name'      => $t['name'],
                    'nip'       => $t['nip'],
                    'email'     => null,
                    'password'  => Hash::make('12345678'),
                    'is_active' => true,
                ]
            );
            $user->syncRoles($t['roles']);
        }
    }
}
