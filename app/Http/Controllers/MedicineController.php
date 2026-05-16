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
            $medicine->image_path = $request->file('image')->store('medicines', 'public');
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
            // Hapus gambar lama jika ada untuk menghemat ruang disk storage
            if ($medicine->image_path) {
                Storage::disk('public')->delete($medicine->image_path);
            }
            $medicine->image_path = $request->file('image')->store('medicines', 'public');
        }

        $medicine->save();

        return redirect()->back()->with('success', 'Detail data obat berhasil diperbarui!');
    }
}