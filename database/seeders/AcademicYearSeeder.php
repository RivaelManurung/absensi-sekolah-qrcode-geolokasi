<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::create([
            'year' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
            'status' => 'active',
        ]);
    }
}