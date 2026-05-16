<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['user_id', 'image_path', 'notes', 'status', 'verified_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // SOLUSI ERROR: Paksa kolom verified_at menjadi tipe datetime/date object
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function order() {
        return $this->hasOne(Order::class);
    }
}
