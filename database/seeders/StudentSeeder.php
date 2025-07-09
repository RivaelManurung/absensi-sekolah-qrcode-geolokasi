<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan data Faker Indonesia
        $classes = Kelas::all();

        foreach ($classes as $class) {
            for ($i = 1; $i <= 15; $i++) {
                $gender = $faker->randomElement(['laki-laki', 'perempuan']);
                $fullName = $faker->name($gender == 'laki-laki' ? 'male' : 'female');

                $user = User::create([
                    'name' => $fullName,
                    'username' => 'siswa' . $class->id . str_pad($i, 2, '0', STR_PAD_LEFT), // e.g., siswa101
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'class_id' => $class->id,
                    'nisn' => rand(1000000000, 9999999999),
                    'nis' => 'S' . $class->id . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'full_name' => $fullName,
                    'gender' => $gender,
                    'date_of_birth' => $faker->dateTimeBetween('-17 years', '-15 years'),
                ]);
            }
        }
    }
}