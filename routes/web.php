<?php

use Illuminate\Support\Facades\Route;

// Controller Otentikasi
// Pastikan Anda memiliki Auth Controller, jika tidak, Anda bisa membuatnya atau menggunakan package laravel/ui
// use App\Http\Controllers\Auth\LoginController; 

// Controller Umum
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;

// Controller untuk Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route untuk halaman utama (landing page)
Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE OTENTIKASI (LOGIN & LOGOUT) ---
// Jika Anda menggunakan package `laravel/ui`, cukup jalankan baris ini:
// Auth::routes(['register' => false]); // Menonaktifkan route registrasi

// Jika Anda ingin mendefinisikannya secara manual:
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


// Grup route yang memerlukan login (auth)
Route::middleware(['auth'])->group(function () {

    // === ROUTE UNTUK SEMUA ROLE YANG LOGIN (SISWA & GURU) ===
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('schedules.index');
    
    // === GRUP ROUTE KHUSUS UNTUK GURU ===
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/absensi/ambil/{schedule}', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/absensi/simpan/{schedule}', [AttendanceController::class, 'store'])->name('attendances.store');
    });

    // === GRUP ROUTE KHUSUS UNTUK ADMIN ===
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('classes', ClassController::class)->parameters(['classes' => 'kela']);
        Route::resource('subjects', SubjectController::class);
    });

});