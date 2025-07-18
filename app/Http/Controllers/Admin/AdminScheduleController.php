<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Kelas;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    /**
     * Menampilkan daftar semua jadwal pelajaran.
     */
    public function index()
    {
        $schedules = Schedule::with(['class', 'subject', 'teacher'])
            ->latest()
            ->paginate(15);
            
        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        $data = [
            'classes' => Kelas::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::orderBy('full_name')->get(),
            'academicYears' => AcademicYear::orderBy('year', 'desc')->get(),
            'days' => ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu']
        ];
        return view('admin.schedules.create', $data);
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week' => 'required|integer|between:1,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit jadwal.
     */
    public function edit(Schedule $schedule)
    {
        $data = [
            'schedule' => $schedule,
            'classes' => Kelas::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::orderBy('full_name')->get(),
            'academicYears' => AcademicYear::orderBy('year', 'desc')->get(),
            'days' => ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu']
        ];
        return view('admin.schedules.edit', $data);
    }

    /**
     * Memperbarui jadwal.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week' => 'required|integer|between:1,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        // Pengecekan ini memerlukan relasi 'attendances' di model Schedule
        if ($schedule->attendances()->exists()) {
            return back()->with('error', 'Gagal menghapus. Jadwal ini sudah memiliki riwayat absensi.');
        }

        $schedule->delete();
        
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}