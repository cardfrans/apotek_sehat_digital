<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    // =========================================================================
    // SISI PASIEN / CUSTOMER
    // =========================================================================
    public function index()
    {
        $prescriptions = Prescription::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        $prescription = new Prescription();
        $prescription->user_id = auth()->id();
        $prescription->notes = $request->notes;
        $prescription->status = 'pending';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Mengubah file gambar resep menjadi string Base64 yang sah
            $base64Data = base64_encode(file_get_contents($image));
            $base64String = 'data:' . $image->getMimeType() . ';base64,' . $base64Data;
            
            // Simpan teks panjang tersebut langsung ke properti model resep
            $prescription->image_path = $base64String; 
        }

        $prescription->save();

        return redirect()->back()->with('success', 'Resep dokter berhasil diunggah! Mohon tunggu verifikasi apoteker.');
    }

    // =========================================================================
    // SISI ADMIN APOTEKER (DIKUNCI DENGAN PERHITUNGAN BERAT DINAMIS)
    // =========================================================================
    public function adminIndex()
    {
        $prescriptions = Prescription::with('user')->orderBy('created_at', 'desc')->get();
        $medicines = Medicine::where('stock', '>', 0)->orderBy('name', 'asc')->get();

        return view('admin.prescriptions.index', compact('prescriptions', 'medicines'));
    }

    public function updateStatus(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);

        if ($request->status === 'verified') {
            $request->validate([
                'medicine_id' => 'required|exists:medicines,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $medicine = Medicine::findOrFail($request->medicine_id);

            if ($medicine->stock < $request->quantity) {
                return redirect()->back()->with('error', 'Gagal memverifikasi! Stok ' . $medicine->name . ' tidak mencukupi.');
            }

            // A. Update status resep dokumen dokter
            $prescription->update([
                'status' => 'verified',
                'verified_at' => now(),
            ]);

            // B. Hitung Kalkulasi Berat Total Pesanan Pasien
            $calculatedWeight = ($medicine->weight ?? 100) * $request->quantity;

            // C. Buat lembar pesanan otomatis dengan mengunci data BERAT asli dari gudang
            Order::create([
                'user_id' => $prescription->user_id,
                'prescription_id' => $prescription->id,
                'total_price' => $medicine->price * $request->quantity,
                'weight' => $calculatedWeight, // Berat dikunci di sini agar user tinggal pakai saat checkout
                'shipping_cost' => 0, 
                'status' => 'pending', 
                'privacy_packaging' => true,
            ]);

            // D. Potong kuantitas stok inventaris obat
            $medicine->decrement('stock', $request->quantity);

            return redirect()->back()->with('success', 'Resep berhasil disetujui! Pesanan baru otomatis terbuat dengan total berat paket ' . $calculatedWeight . ' gram.');
        }

        if ($request->status === 'rejected') {
            $prescription->update([
                'status' => 'rejected',
                'verified_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Resep dokter telah ditolak.');
        }
    }
}