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
        'is_completed'
    ];
}