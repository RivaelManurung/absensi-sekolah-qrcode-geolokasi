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
     * Menampilkan halaman untuk mengambil absensi suatu kelas pada jadwal tertentu.
     * Route-Model Binding digunakan di sini untuk mengambil data jadwal secara otomatis.
     */
    public function create(Schedule $schedule)
    {
        // Pastikan guru yang mengakses adalah guru yang mengajar di jadwal tsb
        if (Auth::user()->teacher->id !== $schedule->teacher_id) {
            abort(403, 'AKSES DITOLAK');
        }

        // Ambil semua siswa dari kelas yang terkait dengan jadwal ini
        $students = Student::where('class_id', $schedule->class_id)->orderBy('full_name', 'asc')->get();

        // Cek apakah absensi untuk jadwal ini sudah pernah dibuat hari ini
        $today = now()->toDateString();
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $today)
            ->pluck('status', 'student_id'); // Ambil status berdasarkan student_id

        return view('attendances.create', [
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
        // Validasi input
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);
        
        $today = now()->toDateString();
        $teacherId = Auth::user()->teacher->id;

        // Gunakan transaction untuk memastikan semua data tersimpan atau tidak sama sekali
        DB::beginTransaction();
        try {
            foreach ($request->attendances as $attendanceData) {
                // Gunakan updateOrCreate untuk membuat data baru atau update jika sudah ada
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
            DB::commit(); // Simpan semua perubahan jika berhasil
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data absensi.');
        }

        return redirect()->route('schedules.index')->with('success', 'Absensi berhasil disimpan!');
    }
}