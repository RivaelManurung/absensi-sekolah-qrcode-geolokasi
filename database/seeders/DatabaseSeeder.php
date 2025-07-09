<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AcademicYearSeeder::class,
            SubjectSeeder::class,
            UserRoleSeeder::class, // Creates users and teachers
            ClassSeeder::class,      // Depends on years and teachers
            StudentSeeder::class,    // Depends on classes and users
            ScheduleSeeder::class,   // Depends on all of the above
        ]);
    }
}