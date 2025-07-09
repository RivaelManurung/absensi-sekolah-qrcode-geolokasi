<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Teacher;
use App\Models\AcademicYear;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::where('status', 'active')->first();
        $teachers = Teacher::all();

        $classes = [
            ['name' => '10 IPA 1', 'grade_level' => 10],
            ['name' => '10 IPS 2', 'grade_level' => 10],
            ['name' => '11 IPA 1', 'grade_level' => 11],
        ];
        
        foreach ($classes as $index => $classData) {
            Kelas::create([
                'name' => $classData['name'],
                'grade_level' => $classData['grade_level'],
                'academic_year_id' => $activeYear->id,
                // Assign homeroom teacher in rotation
                'homeroom_teacher_id' => $teachers[$index % $teachers->count()]->id,
            ]);
        }
    }
}