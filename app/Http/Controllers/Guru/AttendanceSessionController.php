<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AttendanceSessionController extends Controller
{
    /**
     * Memulai sesi absensi dan menampilkan halaman QR code.
     * QR code ini berisi token unik yang berlaku sementara.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\View\View
     */
    public function startSession(Schedule $schedule)
    {
        // 1. Buat token unik sebagai "kunci" sesi absensi
        $token = Str::random(40);

        // 2. Buat key untuk cache menggunakan token tersebut
        $cacheKey = "attendance_token:" . $token;

        // 3. Simpan ID jadwal ke dalam cache dengan kunci token.
        //    Token ini hanya berlaku selama 2 menit.
        Cache::put($cacheKey, $schedule->id, now()->addMinutes(2));

        // 4. Tampilkan view yang akan merender QR code
        return view('teacher.attendance.display_qr', [
            'token' => $token, // Token ini yang akan di-render menjadi QR code
            'schedule' => $schedule,
            'expires_at' => now()->addMinutes(2),
        ]);
    }
}