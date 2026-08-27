<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        // Menyimpan tugas ke database
        Task::create([
            'user_id' => Auth::id(), // 🛡️ Otomatis mengaitkan tugas dengan user yang sedang login
            'title' => $validatedData['title'],
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