<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    /**
     * Mencari kota/kecamatan secara langsung untuk mendapatkan ID Destinasi (Subdistrict ID).
     */
    public function searchDestination(Request $request)
    {
        // Validasi input pencarian, minimal 3 karakter agar pencarian akurat
        $request->validate([
            'search' => 'required|string|min:3',
        ]);

        $searchQuery = $request->query('search');

        // Mengambil konfigurasi dari config/services.php
        $apiKey = config('services.rajaongkir.key');
        $baseUrl = config('services.rajaongkir.base_url');

        // Eksekusi HTTP Request ke API RajaOngkir Komerce
        $response = Http::withHeaders([
            'key' => $apiKey
        ])->get($baseUrl . '/destination/domestic-destination', [
            'search' => $searchQuery,
            'limit' => 5,
            'offset' => 0
        ]);

        // Cek jika API mengalami kegagalan/error
        if ($response->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung dengan layanan pencarian lokasi.',
                'error' => $response->json()
            ], $response->status());
        }

        // Kembalikan response sukses dalam format JSON untuk kebutuhan Frontend
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }
}