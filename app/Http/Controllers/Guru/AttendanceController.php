<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function create(Schedule $schedule)
    {
        if (Auth::user()->teacher->id !== $schedule->teacher_id) {
            abort(403, 'AKSES DITOLAK');
        }

        $students = Student::where('class_id', $schedule->class_id)->orderBy('full_name', 'asc')->get();
        $today = now()->toDateString();

        $existingAttendance = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');

        return view('guru.attendances.create', [
            'schedule' => $schedule,
            'students' => $students,
            'existingAttendance' => $existingAttendance,
            'pageTitle' => 'Ambil Absensi'
        ]);
    }

    public function store(Request $request, Schedule $schedule)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        $today = now()->toDateString();
        $teacherId = Auth::user()->teacher->id;

        DB::transaction(function () use ($request, $schedule, $today, $teacherId) {
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
        });

        return redirect()->route('guru.schedules.index')->with('success', 'Absensi berhasil disimpan!');
    }
}
