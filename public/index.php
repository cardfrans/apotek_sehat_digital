<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Cek Fitur Maintenance
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Muat Autoload Vendor Dependencies
require __DIR__.'/../vendor/autoload.php';

// 3. JALANKAN DENGAN WRAPPER AMAN UNTUK MEMBONGKAR EROR ASLI
try {
    
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    );

    $response->send();

    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    // Jika terjadi eror booting/database/apapun, tangkap dan langsung cetak tulisan putih polos
    header('Content-Type: text/plain', true, 500);
    echo "=== [BERHASIL DIBONGKAR] EROR ASLI APOTEK ===\n";
    echo "Pesan Masalah : " . $e->getMessage() . "\n";
    echo "Terjadi di File : " . $e->getFile() . " (Baris: " . $e->getLine() . ")\n\n";
    echo "=== STACK TRACE LENGKAP ===\n";
    echo $e->getTraceAsString();
    exit;
}