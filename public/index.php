<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Cek Autoload Vendor
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

// 2. Muat Aplikasi Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// ====================================================================
// BAJAK EXCEPTION HANDLER: Paksa cetak teks asli jika terjadi eror awal!
// ====================================================================
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    new class implements Illuminate\Contracts\Debug\ExceptionHandler {
        public function report(\Throwable $e) {}
        public function shouldReport(\Throwable $e) { return false; }
        public function render($request, \Throwable $e) {
            header('Content-Type: text/plain', true, 500);
            echo "=== [BERHASIL DIBONGKAR] EROR ASLI APOTEK ===\n";
            echo "Pesan Masalah : " . $e->getMessage() . "\n";
            echo "Terjadi di File : " . $e->getFile() . " (Baris: " . $e->getLine() . ")\n\n";
            echo "=== STACK TRACE LENGKAP ===\n";
            echo $e->getTraceAsString();
            exit;
        }
        public function renderForConsole($output, \Throwable $e) {}
    }
);
// ====================================================================

// 3. Jalankan Aplikasi
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);