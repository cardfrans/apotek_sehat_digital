<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Pelacakan Pesanan</h1>
            <p class="text-slate-500 mt-1">Pantau status pengiriman obat resep Anda secara real-time dari kurir pilihan.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-5">
            @forelse($orders as $order)
                <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between gap-4 hover:border-emerald-100 transition-all duration-300">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="space-y-2 max-w-lg">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">INV/ORD/{{ $order->id }}</span>
                                
                                @if($order->status === 'processing')
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">Sedang Dikemas</span>
                                @elseif($order->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">Selesai / Tiba</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">Dibatalkan</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100 animate-pulse">Menunggu Pembayaran Checkout</span>
                                @endif
                            </div>
                            
                            <h3 class="font-extrabold text-slate-800 text-lg">Total Transaksi: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>
                            
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                <span class="font-bold text-slate-700 block mb-0.5">Tujuan Pengiriman:</span>
                                @if($order->address)
                                    {{ $order->address }}, {{ $order->city }}, {{ $order->province }}
                                @define
                                @else
                                    <span class="text-amber-600 font-semibold italic bg-amber-50 px-2 py-1 rounded-md border border-amber-100/50">Alamat belum dilengkapi, silakan lakukan checkout</span>
                                @endif
                            </p>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 w-full md:w-fit min-w-[240px]">
                            <div class="text-xs font-bold text-slate-700 mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                                Kurir: <span class="uppercase font-extrabold text-slate-800">{{ $order->courier ?? 'Belum Ditentukan' }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">
                                No. Resi Pengiriman: 
                                <span class="block font-bold text-slate-800 mt-0.5 text-xs bg-white p-2 rounded-lg border border-slate-200 select-all tracking-wider">
                                    {{ $order->tracking_number ?? 'Resi Belum Di-input Admin' }}
                                </span>
                            </div>
                            @if($order->status === 'processing' && $order->tracking_number)
                                <p class="text-[9px] text-emerald-600 font-bold mt-1.5 animate-pulse">✓ Paket sedang dibawa kurir ke lokasi Anda</p>
                            @endif
                        </div>
                    </div>

                    @if($order->status === 'pending')
                        <div class="mt-2 pt-4 border-t border-slate-100/70 flex justify-end">
                            <a href="{{ route('orders.checkout', $order->id) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold py-3 px-6 rounded-2xl text-xs shadow-md shadow-orange-500/20 transition-all duration-200 active:scale-98">
                                Lengkapi Alamat & Bayar Sekarang
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    @endif

                </div>
            @empty
                <div class="py-16 text-center text-slate-400 font-medium bg-white rounded-[2rem] border border-slate-100 shadow-sm">Belum ada aktivitas transaksi atau riwayat pelacakan pesanan obat terdaftar harian Anda.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>