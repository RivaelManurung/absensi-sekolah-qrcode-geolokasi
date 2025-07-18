<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AttendanceController extends Controller
{
    /**
     * Merekam absensi siswa setelah memindai QR code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordAttendance(Request $request)
    {
        // 1. Validasi input dari request siswa
        $validated = $request->validate([
            'token'     => 'required|string|size:40',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // 2. Validasi Token QR
        $cacheKey = "attendance_token:" . $validated['token'];
        $scheduleId = Cache::get($cacheKey);

        if (!$scheduleId) {
            return response()->json(['message' => 'Sesi absensi tidak ditemukan atau sudah kedaluwarsa.'], 404);
        }

        // 3. Validasi Geolokasi
        $schoolLat = config('app.school_latitude');
        $schoolLon = config('app.school_longitude');
        if (!$schoolLat || !$schoolLon) {
            return response()->json(['message' => 'Lokasi sekolah belum diatur oleh admin.'], 500);
        }

        $distance = $this->calculateDistance($schoolLat, $schoolLon, $validated['latitude'], $validated['longitude']);
        // Toleransi jarak 100 meter (0.1 KM)
        if ($distance > 0.1) {
            return response()->json(['message' => 'Anda terdeteksi berada di luar area sekolah!'], 403);
        }

        // 4. Validasi Siswa & Jadwal
        $student = Auth::user()->student;
        $schedule = Schedule::find($scheduleId);

        if ($student->class_id !== $schedule->class_id) {
            return response()->json(['message' => 'Jadwal ini bukan untuk kelas Anda.'], 403);
        }

        // 5. Cek absensi ganda
        $alreadyAttended = Attendance::where('student_id', $student->id)
            ->where('schedule_id', $scheduleId)
            ->where('date', now()->toDateString())
            ->exists();

        if ($alreadyAttended) {
            return response()->json(['message' => 'Anda sudah tercatat hadir untuk jadwal ini.'], 409);
        }

        // 6. Jika semua validasi lolos, rekam absensi
        Attendance::create([
            'student_id'             => $student->id,
            'schedule_id'            => $scheduleId,
            'date'                   => now()->toDateString(),
            'status'                 => 'hadir',
            'recorded_by_teacher_id' => null, // Diisi null karena siswa absen mandiri
            'latitude'               => $validated['latitude'],
            'longitude'              => $validated['longitude'],
        ]);
        
        // 7. Hapus token agar tidak bisa digunakan lagi
        Cache::forget($cacheKey);

        return response()->json(['message' => 'Kehadiran berhasil dicatat!'], 200);
    }

    /**
     * Menghitung jarak antara dua koordinat GPS menggunakan formula Haversine.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam KM
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}