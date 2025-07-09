<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\AcademicYear;

class ScheduleController extends Controller
{
    /**
     * Menampilkan jadwal pelajaran berdasarkan role pengguna yang login.
     */
    public function index()
    {
        $user = Auth::user();
        $activeAcademicYear = AcademicYear::where('status', 'active')->first();
        $schedules = collect(); // Inisialisasi collection kosong

        // Jika tahun ajaran aktif tidak ditemukan, kembalikan view dengan data kosong
        if (!$activeAcademicYear) {
            return view('schedules.index', [
                'schedulesByDay' => $schedules,
                'pageTitle' => 'Jadwal Pelajaran'
            ]);
        }

        if ($user->role === 'guru') {
            // Ambil jadwal untuk guru yang sedang login
            $schedules = Schedule::where('teacher_id', $user->teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->with(['class', 'subject']) // Eager loading untuk performa
                ->orderBy('start_time', 'asc')
                ->get();
        } elseif ($user->role === 'siswa') {
            // Ambil jadwal untuk siswa yang sedang login
            $schedules = Schedule::where('class_id', $user->student->class_id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->with(['teacher', 'subject']) // Eager loading
                ->orderBy('start_time', 'asc')
                ->get();
        }
        
        // Kelompokkan jadwal berdasarkan hari
        $schedulesByDay = $schedules->groupBy('day_of_week');

        return view('schedules.index', [
            'schedulesByDay' => $schedulesByDay,
            'pageTitle' => 'Jadwal Pelajaran'
        ]);
    }
}