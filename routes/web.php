<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SpotifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

// 🛡️ KEAMANAN: middleware('auth') memastikan halaman ini hanya bisa diakses kalau sudah login
Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // 1. DASHBOARD & MANAJEMEN TUGAS
    // ==========================================
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

        // Data Aktivitas 7 Hari Terakhir dari Database
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

        // Sistem Streak (Hari berturut-turut menyelesaikan tugas)
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
    })->name('dashboard');

    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // ==========================================
    // 2. HALAMAN PENDUKUNG (AI, Bantuan)
    // ==========================================
    Route::get('/ai-chat', function () {
        return view('ai-chat');
    })->name('ai.chat');

    Route::get('/help', function () {
        return view('help');
    })->name('help');

    // ==========================================
    // 3. PLAYLIST & SPOTIFY INTEGRATION
    // ==========================================
    Route::get('/playlist', function () {
        return view('playlist');
    })->name('playlist');

    // PERBAIKAN: Mengubah jadi PUT dan namanya disesuaikan jadi playlist.update
    Route::put('/playlist/update', function (Request $request) {
        $validatedData = $request->validate([
            'playlist_url' => 'required|url',
        ]);

        $user = Auth::user();
        $user->playlist_url = $validatedData['playlist_url'];
        $user->save();

        return back()->with('success', 'Link playlist berhasil disimpan secara permanen!');
    })->name('playlist.update');

    Route::get('/spotify/login', [SpotifyController::class, 'redirectToSpotify'])->name('spotify.login');
    Route::get('/callback', [SpotifyController::class, 'handleCallback'])->name('spotify.callback');

    // ==========================================
    // 4. PROFIL USER
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__ . '/auth.php';