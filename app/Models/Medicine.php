<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    // Mendaftarkan kolom weight agar diizinkan menyimpan data ke database
    protected $fillable = [
        'name', 
        'price', 
        'stock', 
        'weight', 
        'image_path'
    ];
}