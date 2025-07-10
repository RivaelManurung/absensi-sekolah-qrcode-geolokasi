<?php

use Illuminate\Support\Facades\Route;

// Controller Otentikasi
use App\Http\Controllers\Auth\LoginController;

// Controller Umum
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;

// Controller untuk Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\LeaveRequestController;
// PENTING: Tambahkan use statement untuk Admin\ScheduleController
use App\Http\Controllers\Admin\AdminScheduleController ;

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

    // === ROUTE UNTUK GURU & SISWA ===
    Route::get('/dashboard', [ScheduleController::class, 'index'])->name('dashboard'); // Arahkan ke jadwal setelah login

    // === GRUP ROUTE KHUSUS UNTUK GURU ===
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/absensi/ambil/{schedule}', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/absensi/simpan/{schedule}', [AttendanceController::class, 'store'])->name('attendances.store');
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

        // !! PERBAIKAN DI SINI !!
        // Gunakan AdminScheduleController untuk route admin
        Route::resource('schedules', AdminScheduleController::class); 

        // Route untuk pengajuan izin
        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::patch('/leave-requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.updateStatus');
    });
});