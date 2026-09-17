<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // 🛡️ KEAMANAN: Mencegah Mass Assignment Vulnerability.
    // Hanya kolom ini yang boleh diisi dari inputan user/form.
    protected $fillable = [
        'user_id',
        'title',
        'is_completed',
        'reminder_at',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'reminder_at' => 'datetime',
        ];
    }

    // Relasi: Setiap task dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}