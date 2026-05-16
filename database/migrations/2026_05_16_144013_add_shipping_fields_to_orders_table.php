<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek satu per satu, jika belum ada baru tambahkan kolomnya
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('province');
            }
            
            if (!Schema::hasColumn('orders', 'courier')) {
                $table->string('courier')->nullable()->after('city');
            }
            
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('courier');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom hanya jika kolom tersebut eksis di database
            $columnsToDrop = [];
            
            if (Schema::hasColumn('orders', 'address')) $columnsToDrop[] = 'address';
            if (Schema::hasColumn('orders', 'province')) $columnsToDrop[] = 'province';
            if (Schema::hasColumn('orders', 'city')) $columnsToDrop[] = 'city';
            if (Schema::hasColumn('orders', 'courier')) $columnsToDrop[] = 'courier';
            if (Schema::hasColumn('orders', 'tracking_number')) $columnsToDrop[] = 'tracking_number';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
}