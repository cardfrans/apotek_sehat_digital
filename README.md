# ➕ Apotek Sehat Digital

[![Laravel Version](https://img.shields.io/badge/Laravel-v12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.2-777BB4?logo=php&logoColor=white)](https://php.net)
[![TailwindCSS Version](https://img.shields.io/badge/TailwindCSS-v3.x-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-v5.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)

Apotek Sehat Digital adalah sistem informasi manajemen farmasi modern berbasis **Laravel 12** dan **PHP 8.2** yang dirancang dengan estetika minimalis, bersih, dan profesional. Aplikasi ini berfokus pada efisiensi alur kerja apoteker dalam mengelola inventori obat, pencatatan resep digital, serta rekapitulasi laporan transaksi harian tanpa kerumitan.

---

# ✨ Fitur Utama

## 📦 Manajemen Inventori Cerdas
- Pemantauan stok obat secara real-time
- Manajemen batch dan tanggal kedaluwarsa
- Notifikasi stok minimum otomatis

## 💊 Pencatatan Resep Digital
- Input resep dokter secara digital
- Validasi dosis dan jumlah obat
- Riwayat transaksi resep pasien
- Meminimalisir kesalahan pembacaan resep

## 🎨 Antarmuka Modern
- Desain bertema *Clean Medical Teal*
- Responsif untuk desktop dan mobile
- UI minimalis dan nyaman digunakan
- Konsistensi komponen antarmuka

---

# 🧱 Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| Laravel 12 | Backend Framework |
| PHP 8.2 | Bahasa Pemrograman |
| MySQL | Database |
| TailwindCSS | Styling UI |
| Vite | Frontend Bundler |
| Blade | Template Engine |
| Eloquent ORM | Database ORM Laravel |

---

# 🚀 Prasyarat Sistem

Sebelum memulai instalasi, pastikan perangkat Anda telah memenuhi kebutuhan berikut:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB
- Git
- XAMPP / Laragon (Opsional)

---

# 🛠️ Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal.

---

## 1️⃣ Clone Repository

```bash
git clone https://github.com/username-anda/apotek-sehat-digital.git
cd apotek-sehat-digital
```

---

## 2️⃣ Install Dependency Backend

Install seluruh dependency Laravel menggunakan Composer:

```bash
composer install
```

---

## 3️⃣ Install Dependency Frontend

Install dependency frontend menggunakan NPM:

```bash
npm install
```

---

## 4️⃣ Konfigurasi File Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Lalu ubah konfigurasi database pada file `.env`:

```env
APP_NAME="Apotek Sehat Digital"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek_sehat_digital
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

> ⚠️ Pastikan database `apotek_sehat_digital` sudah dibuat terlebih dahulu melalui phpMyAdmin atau database manager lainnya.

---

## 5️⃣ Generate Application Key

Generate application key Laravel:

```bash
php artisan key:generate
```

---

## 6️⃣ Jalankan Migration & Seeder

Migrasikan struktur tabel database beserta data awal:

```bash
php artisan migrate --seed
```

---

## 7️⃣ Jalankan Development Server Frontend

Aktifkan Vite development server:

```bash
npm run dev
```

---

## 8️⃣ Jalankan Laravel Server

Buka terminal baru lalu jalankan:

```bash
php artisan serve
```

Aplikasi akan berjalan di:

```txt
http://127.0.0.1:8000
```

---

# 📁 Struktur Direktori

```bash
app/
├── Models
├── Http
├── Services

database/
├── migrations
├── seeders

resources/
├── views
├── css
├── js

routes/
├── web.php
├── api.php
```

---

# 🔐 Role Pengguna

| Role | Hak Akses |
|---|---|
| Admin | Mengelola seluruh sistem |
| User | Melakukan transaksi pembelian |

---

# 📈 Fitur Pengembangan Selanjutnya

- Integrasi pembayaran digital
- Sistem notifikasi WhatsApp
- Multi-cabang apotek
- Integrasi barcode scanner
- Export laporan otomatis

---

# 🤝 Kontribusi

Kontribusi sangat terbuka untuk pengembangan proyek ini.

Langkah kontribusi:

```bash
1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request
```

---

# 📄 Lisensi

Project ini menggunakan lisensi MIT License.

---

# 👨‍💻 Developer

Dikembangkan dengan ❤️ menggunakan Laravel 12 & TailwindCSS.
