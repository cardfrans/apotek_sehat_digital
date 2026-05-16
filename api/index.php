<?php

// 1. Muat inisialisasi aplikasi Laravel secara mandiri
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 2. BAJAK EXCEPTION HANDLER: Paksa cetak teks asli jika terjadi eror apa pun!
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    new class implements Illuminate\Contracts\Debug\ExceptionHandler {
        public function report(\Throwable $e) {}
        public function shouldReport(\Throwable $e) { return false; }
        public function render($request, \Throwable $e) {
            header('Content-Type: text/plain', true, 500);
            echo "=== [BERHASIL DIBONGKAR] REAL ROOT ERROR APOTEK ===\n";
            echo "Pesan Eror Asli : " . $e->getMessage() . "\n";
            echo "Terjadi di File : " . $e->getFile() . " (Baris: " . $e->getLine() . ")\n\n";
            echo "=== STACK TRACE LENGKAP ===\n";
            echo $e->getTraceAsString();
            exit;
        }
        public function renderForConsole($output, \Throwable $e) {}
    }
);

// 3. Jalankan pemrosesan HTTP Kernel seperti biasa
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);