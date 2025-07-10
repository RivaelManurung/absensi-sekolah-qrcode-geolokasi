<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Kelas;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    /**
     * Menampilkan laporan absensi dengan filter.
     */
    public function index(Request $request)
    {
        // Ambil query builder untuk model Attendance
        $query = Attendance::with(['student.class', 'schedule.subject', 'teacher']);

        // Filter berdasarkan tanggal jika ada input
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            // Default: tampilkan data hari ini
            $query->whereDate('date', now()->toDateString());
        }

        // Filter berdasarkan kelas jika ada input
        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $attendances = $query->latest()->paginate(20)->withQueryString();
        $classes = Kelas::orderBy('name')->get();

        return view('admin.attendance-reports.index', [
            'attendances' => $attendances,
            'classes' => $classes,
            'filters' => $request->only(['date', 'class_id']),
        ]);
    }

    /**
     * Menghapus data absensi (jika diperlukan).
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Data absensi berhasil dihapus.');
    }
}