<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiConsultation extends Model
{
    // Izinkan kolom ini diisi oleh Controller
    protected $fillable = ['user_id', 'message', 'ai_response'];

    // Relasi opsional balik ke data User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}