<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Sekolah',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Guru
        $teachers = [
            ['full_name' => 'Budi Santoso, S.Pd.', 'nuptk' => '1234567890123451'],
            ['full_name' => 'Siti Aminah, S.Kom.', 'nuptk' => '1234567890123452'],
            ['full_name' => 'Agus Wijaya, M.Pd.', 'nuptk' => '1234567890123453'],
            ['full_name' => 'Dewi Lestari, S.S.', 'nuptk' => '1234567890123454'],
            ['full_name' => 'Eko Prasetyo, S.Or.', 'nuptk' => '1234567890123455'],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::create([
                'name' => $teacherData['full_name'],
                'username' => strtolower(explode(' ', $teacherData['full_name'])[0]), // e.g., 'budi'
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'full_name' => $teacherData['full_name'],
                'nuptk' => $teacherData['nuptk'],
                'phone_number' => '08123456789' . rand(0, 9),
            ]);
        }
    }
}