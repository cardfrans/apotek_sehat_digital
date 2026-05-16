<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin / Apoteker
        User::create([
            'name' => 'Admin Apotek',
            'email' => 'admin@apotek.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Customer / Pasien
        User::create([
            'name' => 'Cardino Pasien',
            'email' => 'customer@apotek.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}