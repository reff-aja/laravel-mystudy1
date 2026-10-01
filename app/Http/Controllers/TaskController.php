<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil kata kunci filter dari URL (misal: ?filter=active)
        $filter = $request->query('filter', 'all');

        // 2. Siapkan query dasar: ambil tugas milik user yang sedang login
        $query = Task::where('user_id', Auth::id())->latest();

        // 3. Terapkan filter jika ada
        if ($filter === 'active') {
            $query->where('is_completed', false); // Hanya tugas yang BELUM selesai
        } elseif ($filter === 'completed') {
            $query->where('is_completed', true);  // Hanya tugas yang SUDAH selesai
        }

        // 4. Eksekusi query untuk mendapatkan datanya
        $tasks = $query->get();
    
        // 5. Kirim data tasks dan status filter saat ini ke tampilan dashboard
        return view('dashboard', [
            'tasks' => $tasks,
            'currentFilter' => $filter
        ]); 
    }

    public function store(Request $request)
    {
        // 🛡️ KEAMANAN: Validasi ketat! Judul tugas wajib diisi & maksimal 255 karakter
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'reminder_at' => ['nullable', 'date_format:Y-m-d\\TH:i'],
            'reminder_timezone' => ['required_with:reminder_at', 'timezone'],
        ]);

        $reminderAt = null;
        if (!empty($validatedData['reminder_at'])) {
            $reminderAt = CarbonImmutable::createFromFormat(
                'Y-m-d\\TH:i',
                $validatedData['reminder_at'],
                $validatedData['reminder_timezone'],
            );

            if ($reminderAt === false || $reminderAt->lessThanOrEqualTo(now())) {
                throw ValidationException::withMessages([
                    'reminder_at' => 'Pilih waktu pengingat setelah waktu sekarang.',
                ]);
            }

            $reminderAt = $reminderAt->utc();
        }

        // Menyimpan tugas ke database
        Task::create([
            'user_id' => Auth::id(), // 🛡️ Otomatis mengaitkan tugas dengan user yang sedang login
            'title' => $validatedData['title'],
            'reminder_at' => $reminderAt,
        ]);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function update(Request $request, Task $task)
    {
        // 🛡️ KEAMANAN: Memastikan user hanya bisa meng-update tugasnya sendiri (bukan milik orang lain)
        if ($task->user_id !== Auth::id()) {
            abort(403); 
        }

        // Membalikkan status (jika belum selesai jadi selesai, jika selesai jadi belum)
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return back();
    }

    public function destroy(Task $task)
    {
        // 🛡️ KEAMANAN: Memastikan user hanya bisa menghapus tugasnya sendiri
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus dari database
        $task->delete();

        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}