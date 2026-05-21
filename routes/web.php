<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController; // Import Controller baru
use Illuminate\Support\Facades\Route;

// Halaman Utama / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Utama Bawaan Breeze (Wajib Login & Verifikasi)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Rute Pengisian Lembar Unggah Resep Dokter
Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');

// =========================================================================
// GROUP ROUTE UTAMA (Semua Fitur di Bawah Ini Wajib Login / Terautentikasi)
// =========================================================================
Route::middleware('auth')->group(function () {
    
    // [Breeze] Pengaturan Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul Riwayat Belanja & Checkout Pembayaran Pasien
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/checkout/{id?}', [OrderController::class, 'checkoutView'])->name('orders.checkout');
    Route::post('/checkout/{id}', [OrderController::class, 'checkoutStore'])->name('orders.checkout.store');

    // API Jembatan Proxy AJAX RajaOngkir Komerce (Gudang Asal Batam Fixed)
    Route::get('/logistik/provinces', [OrderController::class, 'getProvinces']);
    Route::get('/logistik/cities/{province_id}', [OrderController::class, 'getCities']);
    Route::post('/logistik/check-ongkir', [OrderController::class, 'checkOngkir']);
    
    // -----------------------------------------------------------------
    // MODUL BARU: KERANJANG BELANJA MANDIRI PASIEN (NON-RESEP)
    // -----------------------------------------------------------------
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    // Menggunakan delete untuk mengeluarkan baris obat dari tampungan
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // -----------------------------------------------------------------
    // FITUR INTEGRASI BAWAAN SEBELUMNYA
    // -----------------------------------------------------------------

    // 1. Modul Pengunggahan Resep (Sisi Pelanggan/Customer)
    Route::post('/prescriptions/upload', [PrescriptionController::class, 'store'])->name('prescriptions.store_upload');

    // 2. Modul Pengiriman & Logistik (Integrasi API RajaOngkir lama)
    Route::get('/shipping/search-destination', [ShippingController::class, 'searchDestination'])->name('shipping.search');

    // 3. Modul Manajemen & Verifikasi Resep (Sisi Admin/Apoteker)
    Route::middleware(['admin'])->group(function () {
        // Verifikasi dokumen resep dari pasien
        Route::get('/admin/prescriptions', [PrescriptionController::class, 'adminIndex'])->name('admin.prescriptions.index');
        Route::patch('/admin/prescriptions/{id}/status', [PrescriptionController::class, 'updateStatus'])->name('admin.prescriptions.update');

        // Manajemen Inventaris Obat (Fitur Tambah & Update Berat Barang)
        Route::get('/admin/medicines', [MedicineController::class, 'index'])->name('admin.medicines.index');
        Route::post('/admin/medicines', [MedicineController::class, 'store'])->name('admin.medicines.store');
        Route::patch('/admin/medicines/{id}', [MedicineController::class, 'update'])->name('admin.medicines.update');

        // Manajemen Pengiriman Resi & Status Logistik Pesanan Masuk
        Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
        Route::patch('/admin/orders/{id}/status', [OrderController::class, 'adminUpdateStatus'])->name('admin.orders.update-status');
    });

    // 4. Modul AI Chatbot Konsultasi Digital Medis
    Route::get('/chatbot', [AiChatController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/send', [AiChatController::class, 'sendMessage'])->name('chatbot.send');
});

// Memanggil seluruh sistem login, register, forgot password dari Laravel Breeze
require __DIR__.'/auth.php';