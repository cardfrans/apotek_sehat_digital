<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    }

    public function getProvinces()
    {
        $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/destination/province");
        $jsonData = $response->json();
        return response()->json($jsonData['data'] ?? $jsonData['rajaongkir']['results'] ?? $jsonData ?? []);
    }

    public function getCities($province_id)
    {
        $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/destination/city/{$province_id}");
        $jsonData = $response->json();
        $cities = $jsonData['data'] ?? $jsonData['rajaongkir']['results'] ?? $jsonData ?? [];
        
        if (empty($cities)) {
            $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/destination/city?province_id={$province_id}");
            $jsonData = $response->json();
            $cities = $jsonData['data'] ?? $jsonData['rajaongkir']['results'] ?? $jsonData ?? [];
        }
        return response()->json($cities);
    }

    // FIX LOGIK BERAT: Jika order_id bernilai 'cart', akumulasikan berat total langsung dari data keranjang aktif
    public function checkOngkir(Request $request)
    {
        if ($request->order_id === 'cart') {
            $cartItems = CartItem::with('medicine')->where('user_id', auth()->id())->get();
            $packageWeight = $cartItems->sum(function($item) {
                return ($item->medicine->weight ?? 100) * $item->quantity;
            });
        } else {
            $order = Order::findOrFail($request->order_id);
            $packageWeight = $order->weight ?? 200;
        }

        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->asForm()->post("{$this->baseUrl}/calculate/district/domestic-cost", [
            'origin'        => '1391', // Kota Batam regional Komerce
            'destination'   => $request->destination,
            'weight'        => $packageWeight,
            'courier'       => 'jne:pos:tiki',
            'price'         => 'lowest'
        ]);

        $jsonData = $response->json();
        $rawServices = $jsonData['data'] ?? $jsonData['rajaongkir']['results'] ?? $jsonData ?? [];
        $combinedServices = [];

        foreach ($rawServices as $s) {
            $costValue = $s['cost'] ?? $s['tarif'] ?? $s['biaya'] ?? 0;
            if (is_array($costValue)) $costValue = $costValue[0]['value'] ?? $costValue['value'] ?? 0;

            $etdValue = $s['etd'] ?? $s['estimasi'] ?? $s['duration'] ?? '-';
            if (is_array($etdValue)) $etdValue = $etdValue[0]['etd'] ?? $etdValue['etd'] ?? '-';
            $etdValue = str_replace(['HARI', 'Hari', 'hari'], '', $etdValue);

            if ($costValue > 0) {
                $combinedServices[] = [
                    'courier'     => strtoupper($s['courier'] ?? $s['code'] ?? 'KURIR'),
                    'service'     => $s['service'] ?? $s['paket'] ?? $s['service_name'] ?? 'REG',
                    'description' => $s['description'] ?? $s['nama_paket'] ?? '',
                    'cost'        => $costValue,
                    'etd'         => trim($etdValue)
                ];
            }
        }
        return response()->json($combinedServices);
    }

    // FIX ALUR CHECKOUT: Bangun Virtual Object jika memproses checkout langsung dari keranjang belanja
    public function checkoutView($id = null)
    {
        if ($id === 'cart') {
            $cartItems = CartItem::with('medicine')->where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return redirect()->route('dashboard')->with('error', 'Keranjang belanja Anda kosong.');
            }

            // Membungkus data belanja ke objek tiruan agar tidak merusak variabel visual Blade index
            $pendingOrder = new \stdClass();
            $pendingOrder->id = 'cart';
            $pendingOrder->total_price = $cartItems->sum(function($item) { return $item->medicine->price * $item->quantity; });
            $pendingOrder->weight = $cartItems->sum(function($item) { return ($item->medicine->weight ?? 100) * $item->quantity; });
            $pendingOrder->prescription_id = null;
        } else {
            if ($id) {
                $pendingOrder = Order::where('user_id', auth()->id())->where('id', $id)->where('status', 'pending')->first();
            } else {
                $pendingOrder = Order::where('user_id', auth()->id())->where('status', 'pending')->whereNotNull('prescription_id')->orderBy('created_at', 'desc')->first();
            }
        }

        if (!$pendingOrder) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada pesanan aktif yang butuh checkout saat ini.');
        }

        return view('orders.checkout', compact('pendingOrder'));
    }

    // FIX DATABASE TRANSACTION: Data pesanan baru benar-benar dilahirkan di sini saat tombol bayar ditekan
    public function checkoutStore(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:500',
            'province_name' => 'required|string',
            'city_name' => 'required|string',
            'courier' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        if ($id === 'cart') {
            $cartItems = CartItem::with('medicine')->where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return redirect()->route('dashboard')->with('error', 'Keranjang kosong atau pesanan telah diproses.');
            }

            $totalPrice = 0;
            $totalWeight = 0;
            foreach ($cartItems as $item) {
                if ($item->medicine->stock < $item->quantity) {
                    return redirect()->route('cart.index')->with('error', 'Stok ' . $item->medicine->name . ' tidak cukup.');
                }
                $totalPrice += $item->medicine->price * $item->quantity;
                $totalWeight += ($item->medicine->weight ?? 100) * $item->quantity;
            }

            // 1. Buat data Order resmi untuk riwayat transaksi mandiri
            $order = Order::create([
                'user_id' => auth()->id(),
                'prescription_id' => null,
                'total_price' => $totalPrice + $request->shipping_cost,
                'weight' => $totalWeight,
                'shipping_cost' => $request->shipping_cost,
                'status' => 'processing',
                'address' => $request->address,
                'province' => $request->province_name,
                'city' => $request->city_name,
                'courier' => strtoupper($request->courier),
                'privacy_packaging' => false
            ]);

            // 2. Turunkan item belanja ke tabel rincian multi-produk & bersihkan isi keranjang
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'medicine_id' => $item->medicine_id,
                    'quantity'    => $item->quantity,
                    'price'       => $item->medicine->price,
                    'weight'      => $item->medicine->weight ?? 100,
                ]);
                $item->medicine->decrement('stock', $item->quantity);
                $item->delete();
            }
        } else {
            // Alur untuk Verifikasi Resep Dokter
            $order = Order::findOrFail($id);
            $order->update([
                'address' => $request->address,
                'province' => $request->province_name,
                'city' => $request->city_name,
                'courier' => strtoupper($request->courier),
                'shipping_cost' => $request->shipping_cost,
                'total_price' => $order->total_price + $request->shipping_cost,
                'status' => 'processing'
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dikonfirmasi! Mohon tunggu admin menginput nomor resi logistik.');
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    // =========================================================================
    // SISI ADMIN APOTEKER
    // =========================================================================
    public function adminIndex()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function adminUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:processing,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number
        ]);

        return redirect()->back()->with('success', 'Status pesanan dan nomor resi logistik berhasil diperbarui!');
    }
}