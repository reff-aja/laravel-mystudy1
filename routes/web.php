<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

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

    // Rute Halaman Chat AI (Coming Soon)
    Route::get('/ai-chat', function () {
        return view('ai-chat');
    })->name('ai.chat');

    // Rute Halaman Playlist Musik
    Route::get('/playlist', function () {
        return view('playlist');
    })->name('playlist');

    // Rute untuk menyimpan link playlist ke database secara permanen
    Route::post('/playlist/save', function (Request $request) {
        $validatedData = $request->validate([
            'playlist_url' => 'required|url',
        ]);

        $user = Auth::user();
        $user->playlist_url = $validatedData['playlist_url'];
        $user->save();

        return back()->with('success', 'Link playlist berhasil disimpan secara permanen!');
    })->name('playlist.save');

    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function (Request $request) {
        $user = Auth::user();
        $filter = $request->query('filter', 'all');

        // Query tugas berdasarkan filter
        $query = $user->tasks();
        if ($filter === 'active') {
            $query->where('is_completed', false);
        } elseif ($filter === 'completed') {
            $query->where('is_completed', true);
        }
        $tasks = $query->latest()->get();

        // 1. Data Aktivitas 7 Hari Terakhir dari Database
        $activityDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = $user->tasks()
                ->where('is_completed', true)
                ->whereDate('updated_at', $date)
                ->count();

            $activityDays[] = [
                'day' => $date->translatedFormat('D'),
                'date' => $date->format('Y-m-d'),
                'completed_count' => $count,
                'active' => $count > 0
            ];
        }

        // 2. Sistem Streak (Hari berturut-turut menyelesaikan tugas)
        $streak = 0;
        $checkDate = Carbon::today();

        if ($user->tasks()->where('is_completed', true)->whereDate('updated_at', $checkDate)->count() == 0) {
            $checkDate->subDay();
        }

        while (true) {
            $hasCompleted = $user->tasks()
                ->where('is_completed', true)
                ->whereDate('updated_at', $checkDate)
                ->exists();

            if ($hasCompleted) {
                $streak++;
                $checkDate->subDay();
            } else {
                break;
            }
        }

        return view('dashboard', [
            'tasks' => $tasks,
            'currentFilter' => $filter,
            'activityDays' => $activityDays,
            'streak' => $streak
        ]);
    })->middleware(['auth', 'verified'])->name('dashboard');
});

require __DIR__ . '/auth.php';