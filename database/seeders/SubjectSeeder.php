<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'MTK-01', 'name' => 'Matematika Wajib'],
            ['code' => 'FIS-01', 'name' => 'Fisika'],
            ['code' => 'BIO-01', 'name' => 'Biologi'],
            ['code' => 'KIM-01', 'name' => 'Kimia'],
            ['code' => 'BIN-01', 'name' => 'Bahasa Indonesia'],
            ['code' => 'BIG-01', 'name' => 'Bahasa Inggris'],
            ['code' => 'EKO-01', 'name' => 'Ekonomi'],
            ['code' => 'GEO-01', 'name' => 'Geografi'],
            ['code' => 'PJOK-01', 'name' => 'Pendidikan Jasmani'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}