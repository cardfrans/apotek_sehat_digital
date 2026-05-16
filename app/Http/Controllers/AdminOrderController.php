<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Menampilkan semua daftar pesanan masuk ala E-commerce
    public function index(Request $request)
    {
        // Fitur filter status opsional jika admin ingin memilah pesanan
        $statusFilter = $request->query('status');

        $ordersQuery = Order::with(['user', 'prescription'])->orderBy('created_at', 'desc');

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->get();

        return view('admin.orders.index', compact('orders'));
    }

    // Memperbarui status pesanan & menginput nomor resi jika statusnya 'shipped'
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'tracking_number' => 'nullable|required_if:status,shipped|string|max:100',
        ]);

        $order = Order::findOrFail($id);

        $updateData = ['status' => $request->status];

        // Jika status diubah menjadi shipped (dikirim), masukkan nomor resinya
        if ($request->status === 'shipped') {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Status pesanan ID #' . $order->id . ' berhasil diperbarui!');
    }
}