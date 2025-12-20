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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- ABSENSI
    Route::get('/attendance/scan', [AttendanceController::class, 'scanPage'])->name('attendance.scan');
    Route::post('/attendance/scan', [AttendanceController::class, 'processScan'])->name('attendance.process');

    // --- LAPORAN
    Route::get('/laporan', [AttendanceController::class, 'report'])->name('laporan.index');

    // --- PROFILE USER
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->group(function () {
        
        // CRUD Data Jamaah
        Route::resource('jamaah', JamaahController::class);
        
        Route::post('jamaah/{id}/upload-foto', [JamaahController::class, 'uploadDataset'])->name('jamaah.upload');
        Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::post('/system/train-model', [DashboardController::class, 'triggerTraining'])->name('system.train');
    });
});

// Memuat routes auth bawaan Breeze (Login, Register, dll)
require __DIR__.'/auth.php';