<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Cek Fitur Maintenance
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Muat Autoload Vendor
require __DIR__.'/../vendor/autoload.php';

// 3. Muat Aplikasi
$app = require_once __DIR__.'/../bootstrap/app.php';

// ====================================================================
// SUNTIKAN PEMBONGKAR EROR ASLI (MENGGUNAKAN CLOSURE YANG SAH)
// ====================================================================
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    function () {
        return new class implements Illuminate\Contracts\Debug\ExceptionHandler {
            public function report(\Throwable $e) {}
            public function shouldReport(\Throwable $e) { return false; }
            public function render($request, \Throwable $e) {
                header('Content-Type: text/plain', true, 500);
                echo "=== [BERHASIL DIBONGKAR] EROR ASLI YANG SEBENARNYA ===\n";
                echo "Jenis Eror  : " . get_class($e) . "\n";
                echo "Pesan Eror  : " . $e->getMessage() . "\n";
                echo "Lokasi File : " . $e->getFile() . " (Baris: " . $e->getLine() . ")\n\n";
                echo "=== STACK TRACE LENGKAP ===\n";
                echo $e->getTraceAsString();
                exit;
            }
            public function renderForConsole($output, \Throwable $e) {}
        };
    }
);
// ====================================================================

// 4. Jalankan Kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);