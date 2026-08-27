<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
        
            // 🛡️ KEAMANAN: Memastikan tugas ini milik siapa. 
            // Kalau user dihapus, tugasnya otomatis ikut terhapus (cascade).
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
        
            $table->string('title'); // Nama/Judul tugas
            $table->boolean('is_completed')->default(false); // Status selesai/belum (default: belum)
            $table->timestamps(); // Mencatat kapan dibuat & diupdate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
