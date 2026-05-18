<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    // Menampilkan halaman list inventaris obat
    public function index()
    {
        $medicines = Medicine::orderBy('name', 'asc')->get();
        return view('admin.medicines.index', compact('medicines'));
    }

    // Menyimpan data obat baru beserta berat paketnya
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1', // Validasi berat minimal 1 gram
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $medicine = new Medicine();
        $medicine->name = $request->name;
        $medicine->price = $request->price;
        $medicine->stock = $request->stock;
        $medicine->weight = $request->weight;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Mengubah file gambar resep menjadi string Base64 yang sah
            $base64Data = base64_encode(file_get_contents($image));
            $base64String = 'data:' . $image->getMimeType() . ';base64,' . $base64Data;
            
            // Simpan teks panjang tersebut langsung ke properti model resep
            $medicine->image_path = $base64String; 
        }

        $medicine->save();

        return redirect()->back()->with('success', 'Obat baru berhasil ditambahkan ke inventaris!');
    }

    // Memperbarui detail data obat dan berat (Proses Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1', // Validasi update berat obat
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $medicine = Medicine::findOrFail($id);
        $medicine->name = $request->name;
        $medicine->price = $request->price;
        $medicine->stock = $request->stock;
        $medicine->weight = $request->weight;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Mengubah file gambar baru menjadi string Base64 yang sah
            $base64Data = base64_encode(file_get_contents($image));
            $base64String = 'data:' . $image->getMimeType() . ';base64,' . $base64Data;
            
            // Langsung timpa data teks Base64 lama di database dengan yang baru
            $medicine->image_path = $base64String;
        }   

        $medicine->save();

        return redirect()->back()->with('success', 'Detail data obat berhasil diperbarui!');
    }
}