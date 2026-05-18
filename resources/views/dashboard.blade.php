<x-app-layout>
    @if(auth()->user()->role === 'admin')
        <div class="p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">Halo, Apoteker {{ auth()->user()->name }} 👋</h1>
                <p class="text-slate-500 mt-1 text-lg">Berikut adalah ringkasan aktivitas apotek hari ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 auto-rows-[12rem]">
                <div class="md:col-span-2 md:row-span-2 bg-emerald-600 rounded-[2rem] p-8 flex flex-col justify-between text-white relative overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="relative z-10">
                        <div class="bg-white/20 w-fit p-3 rounded-2xl backdrop-blur-sm mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-medium opacity-90">Antrean Resep</h3>
                        <p class="text-6xl font-bold mt-2 tracking-tight">{{ $totalPendingPrescriptions }}</p> 
                        <p class="mt-3 text-emerald-100 font-medium">Resep masuk butuh verifikasi segera.</p>
                    </div>
                    <a href="{{ route('admin.prescriptions.index') }}" class="relative z-10 w-fit bg-white text-emerald-700 hover:bg-emerald-50 px-6 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center gap-2 mt-4">
                        Cek Antrean Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-white/10 rounded-full blur-2xl"></div>
                </div>

                <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:border-emerald-200 transition-colors group">
                    <div class="flex justify-between items-start"><div class="bg-blue-50 p-4 rounded-2xl group-hover:bg-blue-100 transition-colors"><svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg></div></div>
                    <div><h3 class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Obat</h3><p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalMedicines }} <span class="text-sm font-normal text-slate-400">Variasi</span></p></div>
                </div>

                <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:border-emerald-200 transition-colors group">
                    <div class="flex justify-between items-start"><div class="bg-orange-50 p-4 rounded-2xl group-hover:bg-orange-100 transition-colors"><svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div></div>
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-3">
                        <div><h4 class="text-[11px] font-bold text-slate-400 uppercase">Diproses</h4><p class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalProcessing }}</p></div>
                        <div><h4 class="text-[11px] font-bold text-slate-400 uppercase">Selesai</h4><p class="text-xl font-bold text-emerald-600 mt-0.5">{{ $totalCompleted }}</p></div>
                    </div>
                </div>

                <div class="md:col-span-2 bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between hover:border-red-200 transition-colors group relative overflow-hidden">
                    <div class="flex justify-between items-center relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-2xl {{ $lowStockCount > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400' }}"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                            <div><h3 class="text-sm font-bold text-slate-700">Kritis Inventaris</h3><p class="text-xs text-slate-400 mt-0.5">Ada {{ $lowStockCount }} obat rendah stok</p></div>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2 relative z-10">
                        @foreach($lowStockMedicines as $lowMed)
                            <div class="flex justify-between items-center text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100"><span class="font-semibold text-slate-700">{{ $lowMed->name }}</span><span class="px-2 py-0.5 font-bold rounded-md bg-red-50 text-red-600">Sisa {{ $lowMed->stock }}</span></div>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-2 bg-slate-900 rounded-[2rem] p-8 text-white flex items-center justify-between shadow-lg relative overflow-hidden">
                    <div><h3 class="text-slate-400 text-sm font-semibold uppercase tracking-wider">Pendapatan Hari Ini</h3><p class="text-4xl font-bold mt-2 tracking-tight">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</p></div>
                    <div class="text-right"><span class="bg-emerald-500/20 text-emerald-400 text-sm font-bold px-4 py-1.5 rounded-full">Live DB</span></div>
                </div>
            </div>
        </div>

    @else
        <div class="p-8">
            
            @if($pendingOrder)
                <div class="mb-6 bg-gradient-to-r from-orange-500 to-amber-600 rounded-[2rem] p-6 text-white shadow-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-pulse">
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-base">Resep Dokter Anda Telah Disetujui! 💊</h4>
                            <p class="text-orange-100 text-xs font-medium mt-0.5">Apoteker telah meracik obat Anda. Silakan isi alamat pengiriman rumah untuk menghitung ongkir RajaOngkir.</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.checkout', $pendingOrder->id) }}" class="bg-white text-orange-600 hover:bg-orange-50 font-bold px-5 py-3 rounded-xl text-xs whitespace-nowrap shadow-md transition-all shrink-0">
                        Lengkapi Pengiriman & Bayar
                    </a>
                </div>
            @endif

            <div class="w-full bg-gradient-to-r from-emerald-600 to-teal-700 rounded-[2.5rem] p-10 text-white mb-10 relative overflow-hidden shadow-lg shadow-emerald-900/5">
                <div class="max-w-md relative z-10">
                    <span class="bg-white/20 text-xs font-bold px-3 py-1 rounded-full backdrop-blur-md uppercase tracking-wider">Layanan Kilat 24 Jam</span>
                    <h1 class="text-3xl font-bold mt-4 leading-tight">Cari Obat & Penuhi Kebutuhan Sehat Anda</h1>
                    <p class="text-emerald-100 text-sm mt-2 font-medium">Butuh obat khusus dengan resep dokter? Unggah resep Anda langsung via sidebar kiri untuk divalidasi apoteker resmi kami.</p>
                </div>
                <div class="absolute right-10 bottom-0 top-0 w-80 opacity-15 hidden md:block">
                    <svg fill="currentColor" viewBox="0 0 24 24" class="w-full h-full"><path d="M4.5 10.5C3.67 10.5 3 11.17 3 12s.67 1.5 1.5 1.5h15c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5h-15zM10.5 4.5C10.5 3.67 11.17 3 12 3s1.5.67 1.5 1.5v15c0 .83-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5v-15z"/></svg>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Etalase Produk Obat</h2>
                    <p class="text-slate-400 text-sm mt-0.5 font-medium">Beli vitamin dan suplemen harian tanpa ribet.</p>
                </div>
                
                <a href="{{ route('cart.index') }}" id="cart-main-button" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 py-2.5 px-5 rounded-2xl shadow-sm text-xs font-bold transition-all active:scale-98 relative">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path></svg>
                    Lihat Keranjang Belanja
                    @php $currentItems = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity'); @endphp
                    <span id="cart-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full {{ $currentItems > 0 ? '' : 'hidden' }}">
                        {{ $currentItems }}
                    </span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @forelse($medicines as $product)
                    <div class="bg-white border border-slate-100 rounded-3xl p-4 flex flex-col justify-between hover:shadow-xl hover:border-emerald-100 transition-all duration-300 group cursor-pointer relative product-card">
                        <div>
                            <div class="w-full aspect-square rounded-2xl bg-slate-50 overflow-hidden border border-slate-100 flex items-center justify-center mb-4 relative wrapper-img">
                                @if($product->image_path)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($product->image_path, 'data:image') ? $product->image_path : asset($product->image_path) }}" 
                                        alt="Gambar Obat" 
                                        class="w-full max-w-md rounded-2xl shadow-sm border">
                                @else
                                    <svg class="w-12 h-12 text-slate-300 target-img-placeholder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                @endif
                            </div>
                            <h3 class="font-bold text-slate-800 text-sm line-clamp-2 tracking-tight group-hover:text-emerald-600 transition-colors">{{ $product->name }}</h3>
                        </div>
                        
                        <div class="mt-4">
                            <div class="text-xs text-slate-400 font-semibold mb-1">Stok {{ $product->stock }} pcs <span class="text-[10px] text-slate-300">({{ $product->weight ?? 100 }}g)</span></div>
                            <div class="flex justify-between items-center">
                                <span class="text-base font-extrabold text-slate-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                
                                <form action="{{ route('cart.store') }}" method="POST" class="form-add-to-cart">
                                    @csrf
                                    <input type="hidden" name="medicine_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn-add-cart p-2 bg-slate-50 group-hover:bg-emerald-600 rounded-xl group-hover:text-white text-slate-500 transition-all shadow-sm active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-400 font-medium bg-white rounded-[2rem] border border-slate-100">Etalase obat saat ini sedang kosong.</div>
                @endforelse
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                document.querySelectorAll('.form-add-to-cart').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault(); // Menghentikan redirect bawaan HTML Form
                        
                        const currentForm = this;
                        const card = currentForm.closest('.product-card');
                        const img = card.querySelector('.target-img') || card.querySelector('.target-img-placeholder');
                        const cartBtn = document.getElementById('cart-main-button');
                        
                        // 1. TIMING ENGINE: EKSEKUSI ANIMASI NYATA TERBANG KE KERANJANG
                        if (img && cartBtn) {
                            // Buat elemen kloning tiruan untuk diluncurkan
                            const flyingEl = img.cloneNode(true);
                            flyingEl.removeAttribute('id');
                            flyingEl.classList.add('fixed', 'z-50', 'pointer-events-none', 'rounded-full', 'border', 'border-emerald-200', 'bg-white', 'shadow-md');
                            
                            // Ambil posisi absolut koordinat awal gambar obat dan koordinat akhir tombol keranjang
                            const imgRect = img.getBoundingClientRect();
                            const cartRect = cartBtn.getBoundingClientRect();
                            
                            // Set lokasi start posisi kloning obat sesuai koordinat card etalase
                            flyingEl.style.top = `${imgRect.top}px`;
                            flyingEl.style.left = `${imgRect.left}px`;
                            flyingEl.style.width = `${imgRect.width}px`;
                            flyingEl.style.height = `${imgRect.height}px`;
                            flyingEl.style.transition = 'all 0.8s cubic-bezier(0.25, 1, 0.5, 1)'; // Animasi parabola meluncur lembut
                            
                            document.body.appendChild(flyingEl);
                            
                            // Berikan delay microsecond agar CSS mendeteksi instruksi transisi posisi baru
                            setTimeout(() => {
                                flyingEl.style.top = `${cartRect.top + 8}px`;
                                flyingEl.style.left = `${cartRect.left + 15}px`;
                                flyingEl.style.width = '24px';
                                flyingEl.style.height = '24px';
                                flyingEl.style.transform = 'rotate(360deg)';
                                flyingEl.style.opacity = '0.3';
                            }, 20);
                            
                            // Hapus elemen kloning setelah tiba di tujuan tombol keranjang
                            setTimeout(() => {
                                flyingEl.remove();
                                
                                // Efek feedback: Tombol keranjang membesar sejenak (Pop-up shake effect)
                                cartBtn.classList.add('scale-105', 'bg-emerald-50', 'border-emerald-300');
                                setTimeout(() => {
                                    cartBtn.classList.remove('scale-105', 'bg-emerald-50', 'border-emerald-300');
                                }, 250);
                            }, 820);
                        }

                        // 2. BACKGROUND ENGINE: SUBMIT DATA VIA AJAX FETCH
                        const formData = new FormData(currentForm);
                        fetch(currentForm.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Stok tidak mencukupi atau terjadi kesalahan server.');
                            return res.json();
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                // Update nilai angka badge tanpa reload halaman
                                const badge = document.getElementById('cart-badge');
                                if (badge) {
                                    badge.innerText = data.cart_count;
                                    badge.classList.remove('hidden');
                                }
                            }
                        })
                        .catch(err => {
                            alert(err.message || 'Gagal menambahkan produk ke keranjang.');
                        });
                    });
                });
            });
        </script>
    @endif
</x-app-layout> 