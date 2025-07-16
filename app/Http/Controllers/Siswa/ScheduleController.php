<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        // ✅ PERBAIKAN: Langkah 1 - Verifikasi peran pengguna terlebih dahulu
        if (Auth::user()->role !== 'siswa') {
            // Jika yang login bukan siswa (misal: admin atau guru), langsung tolak akses.
            abort(403, 'Akses ditolak. Halaman ini khusus untuk siswa.');
        }

        // Langkah 2 - Setelah yakin ini adalah siswa, ambil profil student-nya
        $student = Auth::user()->student;

        // dd($student);
        // Langkah 3 - Periksa apakah profil student lengkap
        if (!$student) {
            // Error ini sekarang hanya akan muncul jika user memiliki role 'siswa'
            // tapi datanya tidak ada di tabel 'students'.
            abort(403, 'Akses ditolak. Profil siswa tidak ditemukan untuk akun ini.');
        }

        $activeAcademicYear = AcademicYear::where('status', 'active')->first();
        $schedules = collect();
        $attendancesToday = collect();

        if ($activeAcademicYear) {
            $schedules = Schedule::where('class_id', $student->class_id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->with(['teacher', 'subject'])
                ->orderBy('start_time', 'asc')
                ->get();
            
            if ($schedules->isNotEmpty()) {
                $scheduleIds = $schedules->pluck('id');
                $today = now()->toDateString();

                $attendancesToday = Attendance::where('student_id', $student->id)
                    ->whereIn('schedule_id', $scheduleIds)
                    ->where('date', $today)
                    ->get()
                    ->keyBy('schedule_id');
            }
        }

        $schedulesByDay = $schedules->groupBy('day_of_week');

        return view('siswa.schedules.index', [
            'schedulesByDay' => $schedulesByDay,
            'attendancesToday' => $attendancesToday,
            'pageTitle' => 'Jadwal Pelajaran'
        ]);
    }
}