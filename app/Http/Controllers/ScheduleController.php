<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\AcademicYear;

class ScheduleController extends Controller
{
    /**
     * Menampilkan jadwal pelajaran sesuai role pengguna (guru/siswa).
     */
    public function index()
    {
        $user = Auth::user();
        
        // Arahkan admin ke dashboard admin jika mengakses route ini
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $activeAcademicYear = AcademicYear::where('status', 'active')->first();
        $schedules = collect();

        if ($activeAcademicYear) {
            if ($user->role === 'guru') {
                $schedules = Schedule::where('teacher_id', $user->teacher->id)
                    ->where('academic_year_id', $activeAcademicYear->id)
                    ->with(['class', 'subject'])
                    ->orderBy('start_time', 'asc')
                    ->get();
            } elseif ($user->role === 'siswa') {
                $schedules = Schedule::where('class_id', $user->student->class_id)
                    ->where('academic_year_id', $activeAcademicYear->id)
                    ->with(['teacher', 'subject'])
                    ->orderBy('start_time', 'asc')
                    ->get();
            }
        }

        // Kelompokkan jadwal berdasarkan hari untuk ditampilkan
        $schedulesByDay = $schedules->groupBy('day_of_week');

        // Arahkan ke view khusus untuk guru/siswa
        return view('user.schedules.index', [
            'schedulesByDay' => $schedulesByDay,
            'pageTitle' => 'Jadwal Pelajaran'
        ]);
    }
}