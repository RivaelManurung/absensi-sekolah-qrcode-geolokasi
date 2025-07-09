<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
// Tambahkan use statement untuk controller baru
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;


// ... (Route lain yang sudah ada)

Route::middleware(['auth'])->group(function () {

    // ... (Route guru & siswa yang sudah ada)

    // -- GRUP ROUTE UNTUK ADMIN --
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Resource Controllers
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('classes', ClassController::class)->parameters(['classes' => 'kela']); // parameter 'kela'
        Route::resource('subjects', SubjectController::class);
    });
});
