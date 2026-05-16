<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\Prescription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. LOGIK UNTUK SISI ADMIN APOTEKER
        if (auth()->user()->role === 'admin') {
            $totalPendingPrescriptions = Prescription::where('status', 'pending')->count();
            $totalMedicines = Medicine::count();
            $lowStockCount = Medicine::where('stock', '<=', 10)->count();
            $lowStockMedicines = Medicine::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(3)->get();
            $totalProcessing = Order::where('status', 'processing')->count();
            $totalCompleted = Order::where('status', 'completed')->count();
            $totalRevenueToday = Order::where('status', 'completed')
                ->whereDate('created_at', today())
                ->get()
                ->sum(function($order) {
                    return $order->total_price + $order->shipping_cost;
                });

            return view('dashboard', compact(
                'totalPendingPrescriptions',
                'totalMedicines',
                'lowStockCount',
                'lowStockMedicines',
                'totalProcessing',
                'totalCompleted',
                'totalRevenueToday'
            ));
        }

        // 2. LOGIK UNTUK SISI CUSTOMER / PASIEN
        $medicines = Medicine::where('stock', '>', 0)->orderBy('name', 'asc')->get();
        
        // FIX KOKOH: Hanya tampilkan notifikasi jika order pending berasal dari RESEP DOKTER asli (bukan belanja mandiri)
        $pendingOrder = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->whereNotNull('prescription_id') // Kunci utama penyelesaian bug notifikasi pengganggu
            ->first();
        
        return view('dashboard', compact('medicines', 'pendingOrder'));
    }
}