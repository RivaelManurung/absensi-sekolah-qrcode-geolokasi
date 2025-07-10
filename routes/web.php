<?php

use Illuminate\Support\Facades\Route;

// Controller Otentikasi
use App\Http\Controllers\Auth\LoginController;

// Controller Umum
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;

// Controller untuk Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\AdminScheduleController ;
use App\Http\Controllers\Admin\LeaveRequestController as AdminLeaveRequestController;
use App\Http\Controllers\Admin\AttendanceReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Route Otentikasi
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// Grup route yang memerlukan login (auth)
Route::middleware(['auth'])->group(function () {

    // === ROUTE UNTUK GURU & SISWA SETELAH LOGIN ===
    // PERBAIKAN: Nama route diubah menjadi 'schedules.index' untuk mengatasi error.
    // URL tetap /dashboard, namun nama route-nya adalah schedules.index
    Route::get('/dashboard', [ScheduleController::class, 'index'])->name('user.schedules.index');

    // Route untuk Profil (bisa diakses semua role)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');


    // === GRUP ROUTE KHUSUS UNTUK GURU ===
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/absensi/ambil/{schedule}', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/absensi/simpan/{schedule}', [AttendanceController::class, 'store'])->name('attendances.store');
    });


    // === GRUP ROUTE KHUSUS UNTUK SISWA ===
    Route::middleware(['role:siswa'])->group(function() {
        Route::resource('leave-requests', LeaveRequestController::class)->except(['show', 'edit', 'update', 'destroy']);
    });


    // === GRUP ROUTE KHUSUS UNTUK ADMIN ===
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Resource Controllers
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('classes', ClassController::class)->parameters(['classes' => 'kela']);
        Route::resource('subjects', SubjectController::class);
        Route::resource('academic-years', AcademicYearController::class);
        Route::resource('schedules', AdminScheduleController::class);

        // Route untuk Pengajuan Izin dari sisi Admin
        Route::get('/leave-requests', [AdminLeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::patch('/leave-requests/{leaveRequest}/status', [AdminLeaveRequestController::class, 'updateStatus'])->name('leave-requests.updateStatus');

        // Route untuk Laporan Absensi
        Route::get('/attendance-reports', [AttendanceReportController::class, 'index'])->name('attendance-reports.index');
        Route::delete('/attendance-reports/{attendance}', [AttendanceReportController::class, 'destroy'])->name('attendance-reports.destroy');
    });
});