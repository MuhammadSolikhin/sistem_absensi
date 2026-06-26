<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JamaahController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

// Halaman Depan (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Group Middleware Auth 
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD (Semua User Login: Admin, Pengurus, Guru)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- PROFILE USER
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- SHARED ACCESS (Admin, Pengurus, Guru)
    Route::middleware(['role:admin,pengurus,guru'])->group(function () {
        // Laporan
        Route::get('/laporan', [AttendanceController::class, 'report'])->name('laporan.index');

        // Lihat Database Jamaah (Read-Only access for safety default, logic can be inside controller for edit)
        // Adjusting based on common sense: Admin & Pengurus Manage, Guru Views? 
        // For now, let's open specific routes.
    });

    // --- MANAGEMEN RAPOT (Admin & Guru Only - karena Guru yang nilai)
    Route::middleware(['role:admin,guru'])->group(function () {
        Route::resource('rapot', App\Http\Controllers\RapotController::class);

        // Scan Access
        Route::get('/attendance/scan', [AttendanceController::class, 'scanPage'])->name('attendance.scan');
        Route::post('/attendance/scan', [AttendanceController::class, 'processScan'])->name('attendance.process');
    });

    // --- MANAJEMEN JAMAAH (Admin, Pengurus, Guru)
    Route::middleware(['role:admin,pengurus,guru'])->group(function () {
        Route::resource('jamaah', JamaahController::class);
        Route::post('jamaah/{id}/upload-foto', [JamaahController::class, 'uploadDataset'])->name('jamaah.upload');
        Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Pengajian Management (Admin & Pengurus allowed to manage groups/schedules)
        Route::resource('pengajian', App\Http\Controllers\PengajianController::class);
        Route::delete('/pengajian/schedule/{id}', [App\Http\Controllers\PengajianController::class, 'destroySchedule'])->name('pengajian.destroySchedule');
        Route::post('/pengajian/{id}/schedule', [App\Http\Controllers\PengajianController::class, 'storeSchedule'])->name('pengajian.storeSchedule');
    });

    // --- ADMIN ONLY
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::post('/system/train-model', [DashboardController::class, 'triggerTraining'])->name('system.train');
    });
});

// Memuat routes auth bawaan Breeze (Login, Register, dll)
require __DIR__ . '/auth.php';