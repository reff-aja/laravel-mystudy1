<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Menghubungkan tugas ke pemilik akun
            $table->string('title'); // Judul tugas
            $table->text('description')->nullable(); // Deskripsi opsional
            $table->string('priority')->default('Normal'); // Prioritas (High, Normal, dll)
            $table->boolean('is_completed')->default(false); // Status selesai atau belum
            $table->timestamps();
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
