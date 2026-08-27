<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController; // Tambahkan baris ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 🛡️ KEAMANAN: middleware('auth') memastikan halaman ini hanya bisa diakses kalau sudah login
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Mengubah rute dashboard bawaan Breeze untuk menggunakan TaskController
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    
    // Rute untuk menyimpan tugas baru
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Rute untuk meng-update status centang (selesai/belum)
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    // Rute untuk menghapus tugas
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';