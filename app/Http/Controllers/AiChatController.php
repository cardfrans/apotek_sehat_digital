<?php

namespace App\Http\Controllers;

use App\Models\AiConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        // Pastikan API Key aman dan terbaca
        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key Gemini tidak terbaca di .env. Silakan jalankan "php artisan config:clear"'
            ], 500);
        }

        // Struktur prompt gabungan agar lolos validasi payload v1 stabil
        $systemInstruction = "Anda adalah Asisten Konsultasi AI resmi dari Apotek Sehat Digital. Tugas Anda membantu memberikan rekomendasi vitamin atau obat ringan bebas yang relevan dengan keluhan pasien. Gunakan bahasa ramah, sopan, dan sertakan disclaimer medis di akhir kalimat.";
        $combinedPrompt = $systemInstruction . "\n\nPertanyaan Pasien: " . $userMessage;

        // =========================================================================
        // STANDAR 2026: Menggunakan Gemini 2.5 Flash sebagai lini model utama
        // =========================================================================
        $model = 'gemini-2.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::withoutVerifying()->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $combinedPrompt]
                    ]
                ]
            ]
        ]);

        // =========================================================================
        // AUTOPILOT FALLBACK: Jika akun/region belum beralih ke 2.5, otomatis pakai 2.0
        // =========================================================================
        if ($response->failed()) {
            $modelFallback = 'gemini-2.0-flash';
            $urlFallback = "https://generativelanguage.googleapis.com/v1/models/{$modelFallback}:generateContent?key={$apiKey}";
            
            $response = Http::withoutVerifying()->post($urlFallback, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $combinedPrompt]
                        ]
                    ]
                ]
            ]);
        }

        // Jika kedua model generasi baru tersebut masih ditolak (misal masalah kuota key)
        if ($response->failed()) {
            Log::error('Gemini API Error Log: ' . $response->body());
            
            $errorResponse = $response->json();
            $errorMessage = $errorResponse['error']['message'] ?? 'Ditolak oleh Google AI Studio.';

            return response()->json([
                'status' => 'error',
                'message' => 'Google API Terkendala: ' . $errorMessage
            ], 500);
        }

        // Ekstraksi teks sukses
        $responseData = $response->json();
        $aiResponse = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya sedang tidak bisa merespon.';

        // Simpan ke database riwayat chat user
        AiConsultation::create([
            'user_id' => auth()->id(),
            'message' => $userMessage,
            'ai_response' => $aiResponse,
        ]);

        return response()->json([
            'status' => 'success',
            'response' => $aiResponse
        ]);
    }
}