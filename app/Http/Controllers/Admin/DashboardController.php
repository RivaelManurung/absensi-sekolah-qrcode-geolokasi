<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Kelas;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data Statistik Utama ---
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $classCount = Kelas::count();
        $activeAcademicYear = AcademicYear::where('status', 'active')->first();

        // --- Data Untuk Grafik Absensi Hari Ini ---
        $today = now()->toDateString();
        $attendanceToday = Attendance::where('date', $today)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status'); // Hasil: ['hadir' => 50, 'sakit' => 2, ...]

        // Siapkan data untuk chart
        $attendanceChartData = [
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Alpa'],
            'series' => [
                $attendanceToday->get('hadir', 0),
                $attendanceToday->get('sakit', 0),
                $attendanceToday->get('izin', 0),
                $attendanceToday->get('alpa', 0),
            ]
        ];

        // --- Data Pengajuan Izin Terbaru ---
        $recentLeaveRequests = LeaveRequest::with('student.class')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', [
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classCount' => $classCount,
            'activeAcademicYear' => $activeAcademicYear,
            'attendanceChartData' => $attendanceChartData,
            'recentLeaveRequests' => $recentLeaveRequests,
        ]);
    }
}