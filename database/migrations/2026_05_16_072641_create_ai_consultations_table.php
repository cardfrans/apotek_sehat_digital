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
    Schema::create('ai_consultations', function (Blueprint $table) {
        $table->id();
        // Menghubungkan chat dengan id user/pasien yang sedang login
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->text('message'); // Kolom untuk menyimpan pesan pasien
        $table->text('ai_response'); // Kolom untuk menyimpan balasan dari Gemini AI
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_consultations');
    }
};
