<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightFieldsToTables extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom berat di tabel produk obat jika belum ada
        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'weight')) {
                $table->integer('weight')->default(100)->after('price'); // Default 100 gram per item
            }
        });

        // 2. Tambahkan kolom total berat di tabel pesanan jika belum ada
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'weight')) {
                $table->integer('weight')->default(100)->after('total_price'); // Menyimpan total berat belanjaan
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (Schema::hasColumn('medicines', 'weight')) $table->dropColumn('weight');
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'weight')) $table->dropColumn('weight');
        });
    }
}