<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman untuk mengambil absensi.
     */
    public function create(Schedule $schedule)
    {
        if (Auth::user()->teacher->id !== $schedule->teacher_id) {
            abort(403, 'AKSES DITOLAK');
        }

        $students = Student::where('class_id', $schedule->class_id)->orderBy('full_name', 'asc')->get();
        $today = now()->toDateString();

        // --- PERUBAHAN DI SINI ---
        // Ambil seluruh data absensi yang ada dan jadikan student_id sebagai key
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id'); // Hasilnya adalah collection of Attendance models

        // PERBAIKAN: Mengarahkan ke view yang benar
        return view('user.attendances.create', [
            'schedule' => $schedule,
            'students' => $students,
            'existingAttendance' => $existingAttendance,
            'pageTitle' => 'Ambil Absensi'
        ]);
    }

    /**
     * Menyimpan data absensi yang di-submit oleh guru.
     */
    public function store(Request $request, Schedule $schedule)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);
        
        $today = now()->toDateString();
        $teacherId = Auth::user()->teacher->id;

        DB::beginTransaction();
        try {
            foreach ($request->attendances as $attendanceData) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $attendanceData['student_id'],
                        'schedule_id' => $schedule->id,
                        'date' => $today,
                    ],
                    [
                        'status' => $attendanceData['status'],
                        'recorded_by_teacher_id' => $teacherId,
                        'notes' => $attendanceData['notes'] ?? null,
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data absensi.');
        }
        
        // PERBAIKAN: Menggunakan nama route yang benar
        return redirect()->route('user.schedules.index')->with('success', 'Absensi berhasil disimpan!');
    }
}