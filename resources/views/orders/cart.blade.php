<x-app-layout>
    <div class="p-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Keranjang Belanja Mandiri</h1>
            <p class="text-slate-500 mt-1">Kelola daftar obat bebas atau suplemen kesehatan sebelum melanjutkan pengisian kurir.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @php $totalBasePrice = 0; @endphp
                @forelse($cartItems as $item)
                    @php $totalBasePrice += $item->medicine->price * $item->quantity; @endphp
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-5 shadow-sm flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl border border-slate-100 bg-slate-50 overflow-hidden shrink-0">
                                @if($item->medicine->image_path)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($medicine->image_path, 'data:image') ? $medicine->image_path : asset($medicine->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg></div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $item->medicine->name }}</h4>
                                <p class="text-xs text-emerald-600 font-extrabold mt-1">Rp {{ number_format($item->medicine->price, 0, ',', '.') }} <span class="text-slate-400 font-normal">/ pcs</span></p>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Berat: {{ $item->medicine->weight ?? 100 }} gram</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center font-bold text-slate-500 bg-white hover:bg-slate-100 rounded-lg shadow-sm text-xs">-</button>
                                </form>
                                <span class="text-xs font-bold text-slate-800 px-2 min-w-[20px] text-center">{{ $item->quantity }}</span>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center font-bold text-slate-500 bg-white hover:bg-slate-100 rounded-lg shadow-sm text-xs">+</button>
                                </form>
                            </div>

                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 border border-slate-100 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
               
                @empty
                    <div class="py-16 text-center text-slate-400 font-medium bg-white rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center justify-center gap-3">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path></svg>
                        Keranjang mandiri Anda kosong. Sila pilih obat bebas di dashboard utama!
                    </div>
                @endforelse
            </div>

            @if(!$cartItems->isEmpty())
                <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-xl h-fit flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold tracking-wide border-b border-slate-800 pb-3 mb-4">Total Sementara</h3>
                        <div class="flex justify-between items-baseline mb-2">
                            <span class="text-xs text-slate-400 font-semibold">Subtotal Produk</span>
                            <span class="text-xl font-extrabold text-emerald-400">Rp {{ number_format($totalBasePrice, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed mt-2 border-t border-slate-800 pt-3">✓ Biaya di atas belum termasuk ongkir RajaOngkir. Alamat & opsi kurir akan diisi lengkap di halaman berikutnya.</p>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full mt-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl text-center shadow-lg shadow-emerald-900/40 transition-all text-sm">Lanjut ke Pengiriman</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>