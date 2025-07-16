<?php

use Illuminate\Support\Facades\Route;

// Controller Otentikasi & Umum
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;

// Menggunakan namespace untuk merapikan pemanggilan controller
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', function () {
    // Arahkan ke halaman login jika belum login, atau ke dashboard jika sudah
    return auth()->check() ? redirect(app(LoginController::class)->redirectTo()) : view('admin.auth.login');
});


// --- Route Otentikasi ---
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// --- Grup route yang memerlukan login (auth) ---
Route::middleware(['auth'])->group(function () {

    // Route Profil (bisa diakses semua role)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('change-password');
    });

    // === GRUP ROUTE KHUSUS UNTUK ADMIN ===
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('students', Admin\StudentController::class);
        Route::resource('teachers', Admin\TeacherController::class);
        Route::resource('classes', Admin\ClassController::class)->parameters(['classes' => 'kela']);
        Route::resource('subjects', Admin\SubjectController::class);
        Route::resource('academic-years', Admin\AcademicYearController::class);
        Route::resource('schedules', Admin\AdminScheduleController::class);

        Route::get('/leave-requests', [Admin\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::patch('/leave-requests/{leaveRequest}/status', [Admin\LeaveRequestController::class, 'updateStatus'])->name('leave-requests.updateStatus');

        Route::get('/attendance-reports', [Admin\AttendanceReportController::class, 'index'])->name('attendance-reports.index');
        Route::delete('/attendance-reports/{attendance}', [Admin\AttendanceReportController::class, 'destroy'])->name('attendance-reports.destroy');
    });

    // === GRUP ROUTE KHUSUS UNTUK GURU ===
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [Guru\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/absensi/ambil/{schedule}', [Guru\AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/absensi/simpan/{schedule}', [Guru\AttendanceController::class, 'store'])->name('attendances.store');
    });
    /*
    |--------------------------------------------------------------------------
    | GRUP ROUTE KHUSUS UNTUK SISWA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        // Dashboard siswa adalah jadwal pelajarannya
        Route::get('/dashboard', [Siswa\ScheduleController::class, 'index'])->name('schedules.index');

        // Manajemen Pengajuan Izin
        Route::resource('leave-requests', Siswa\LeaveRequestController::class)->except(['edit', 'update', 'destroy']);

        // Melihat riwayat kehadiran pribadi
        // Route::get('/kehadiran', [Siswa\AttendanceController::class, 'index'])->name('attendances.index');
    });
});
