<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Kelas;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $classes = Kelas::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $activeYear = AcademicYear::where('status', 'active')->first();

        $timeSlots = [
            ['start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['start_time' => '09:30:00', 'end_time' => '11:00:00'],
            ['start_time' => '11:00:00', 'end_time' => '12:30:00'],
        ];

        foreach ($classes as $class) {
            // Buat jadwal untuk hari Senin - Jumat
            for ($day = 1; $day <= 5; $day++) {
                foreach ($timeSlots as $slot) {
                    Schedule::create([
                        'class_id' => $class->id,
                        'subject_id' => $subjects->random()->id,
                        'teacher_id' => $teachers->random()->id,
                        'academic_year_id' => $activeYear->id,
                        'day_of_week' => $day,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                    ]);
                }
            }
        }
    }
}