<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $activeAcademicYear = AcademicYear::where('status', 'active')->first();
        $schedules = collect();

        if ($activeAcademicYear) {
            $schedules = Schedule::where('teacher_id', Auth::user()->teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->with(['class', 'subject'])
                ->orderBy('start_time', 'asc')
                ->get();
        }

        $schedulesByDay = $schedules->groupBy('day_of_week');

        return view('guru.schedules.index', [
            'schedulesByDay' => $schedulesByDay,
            'pageTitle' => 'Jadwal Mengajar'
        ]);
    }
}
