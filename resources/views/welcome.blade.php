<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Apotek Sehat Digital') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488', // Primary Teal
                            700: '#0f766e',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-white text-slate-800 font-sans selection:bg-brand-600 selection:text-white relative">

    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between gap-3">
            <a href="#beranda" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold text-xl transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="font-extrabold text-lg sm:text-xl tracking-tight text-slate-900">
                    Apotek<span class="text-brand-600">Sehat</span>
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-10 text-sm font-medium text-slate-500">
                <a href="#beranda" class="hover:text-brand-600 transition-colors">Beranda</a>
                <a href="#fitur" class="hover:text-brand-600 transition-colors">Fitur</a>
                <a href="#keunggulan" class="hover:text-brand-600 transition-colors">Keunggulan</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs sm:text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-3 sm:px-6 h-10 sm:h-11 inline-flex items-center justify-center rounded-xl transition-all shadow-sm">
                            Masuk Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-brand-600 px-2 sm:px-4 transition-colors">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs sm:text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 px-3 sm:px-5 h-10 sm:h-11 inline-flex items-center justify-center rounded-xl transition-all">
                                Daftar Baru
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <section id="beranda" class="relative pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-50 text-brand-700 text-xs font-bold uppercase tracking-wider mb-8 border border-brand-100">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                Manajemen Farmasi Cerdas
            </div>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 max-w-4xl mx-auto leading-tight">
                Digitalisasi Apotek Anda dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-emerald-500">Sempurna.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto font-light leading-relaxed mb-10">
                Sistem informasi minimalis dan modern untuk mengelola stok obat, transaksi resep, dan laporan keuangan tanpa kerumitan.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto h-14 px-8 font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl flex items-center justify-center transition-transform hover:-translate-y-1 shadow-lg shadow-brand-500/25">
                    Mulai Kelola Apotek
                </a>
                <a href="#fitur" class="w-full sm:w-auto h-14 px-8 font-semibold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center transition-colors">
                    Lihat Cara Kerjanya
                </a>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-16 sm:py-24 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Fitur Inti yang Tepat Sasaran</h2>
                <p class="text-slate-500 font-light">Kami membuang fitur yang tidak perlu dan fokus pada alat komprehensif yang benar-benar mempercepat pekerjaan apoteker.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8">
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 hover:border-brand-200 transition-colors">
                    <div class="w-12 h-12 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Inventori</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Lacak pergerakan stok obat, kelola *batch* kedaluwarsa, dan dapatkan peringatan otomatis saat stok menipis.</p>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 hover:border-brand-200 transition-colors">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pencatatan Resep</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Digitalisasi resep dokter untuk meminimalisir kesalahan baca, lengkap dengan riwayat medis pasien terintegrasi.</p>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 hover:border-brand-200 transition-colors">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Laporan Analitik</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Visualisasi data penjualan dan rekap omzet harian yang disajikan secara bersih untuk mendukung keputusan bisnis Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="keunggulan" class="py-16 sm:py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            
            <div class="relative order-2 lg:order-1">
                <div class="absolute inset-0 bg-gradient-to-tr from-brand-100 to-white rounded-3xl transform -rotate-3 scale-105 -z-10"></div>
                <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-hidden">
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="w-32 h-6 bg-slate-200 rounded-md"></div>
                            <div class="w-24 h-8 bg-brand-100 rounded-lg"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="w-full h-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center px-4"><div class="w-1/3 h-3 bg-slate-200 rounded"></div></div>
                            <div class="w-full h-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center px-4"><div class="w-1/2 h-3 bg-slate-200 rounded"></div></div>
                            <div class="w-full h-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center px-4"><div class="w-1/4 h-3 bg-slate-200 rounded"></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2 space-y-8">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight">Desain Antarmuka yang Mengerti <span class="text-brand-600">Alur Kerja Anda.</span></h2>
                
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0 text-brand-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Bersih & Minimalis</h4>
                            <p class="text-slate-500 text-sm mt-1">Kami merancang UI yang profesional dan bersih. Tidak ada elemen visual yang mengganggu fokus pelayanan pasien.</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0 text-brand-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Akses Cepat Secepat Kilat</h4>
                            <p class="text-slate-500 text-sm mt-1">Sistem satu halaman (*Single Page Feeling*) membuat pencarian obat dan kasir berjalan mulus tanpa hambatan *loading*.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="py-16 sm:py-20 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-brand-900/20"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Mengoptimalkan Apotek Anda?</h2>
            <p class="text-slate-400 mb-10 text-lg">Bergabunglah dan rasakan kemudahan manajemen operasional dengan sistem digital kami.</p>
            <a href="{{ route('register') }}" class="inline-flex h-14 px-10 font-bold text-slate-900 bg-white hover:bg-slate-100 rounded-xl items-center justify-center transition-transform hover:scale-105">
                Buat Akun Gratis
            </a>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-slate-900 flex items-center justify-center text-white font-bold text-xs">
                    +
                </div>
                <span class="font-bold text-slate-900">Apotek Sehat Digital</span>
            </div>
            
            <p class="text-sm text-slate-500">
                &copy; {{ date('Y') }} Apotek Sehat Digital. Dibangun untuk efisiensi.
            </p>
        </div>
    </footer>

</body>
</html>
