<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Manajemen Pesanan Masuk</h1>
            <p class="text-slate-500 mt-1">Pantau pembayaran, kelola logistik ongkir RajaOngkir, dan input nomor resi pengiriman kurir.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">ID Invoice & Pasien</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Alamat Pengiriman (RajaOngkir)</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Biaya Logistik</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Status Alur</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600 text-right">Kelola Resi / Pengiriman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-400 block tracking-wider">#ORD-{{ $order->id }}</span>
                            <div class="font-extrabold text-slate-800 mt-0.5">{{ $order->user->name }}</div>
                            <div class="text-xs text-slate-400 font-medium">{{ $order->user->email }}</div>
                        </td>

                        <td class="px-6 py-5 max-w-xs">
                            @if($order->address)
                                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                    {{ $order->address }}, <span class="font-bold text-slate-800">{{ $order->city }}, {{ $order->province }}</span>
                                </p>
                            @else
                                <span class="text-xs text-amber-600 italic font-semibold bg-amber-50 p-2 rounded-xl border border-amber-100">Belum isi alamat checkout</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 font-semibold text-slate-700">
                            <div>Total: <span class="font-extrabold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
                            <div class="text-[11px] text-slate-400 font-medium mt-0.5">Ongkir: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }} via <span class="uppercase font-bold text-slate-600">{{ $order->courier ?? '-' }}</span></div>
                        </td>

                        <td class="px-6 py-5">
                            @if($order->status === 'processing')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">Dikemas / Proses</span>
                            @elseif($order->status === 'completed')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">Selesai Dikirim</span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-100">Dibatalkan</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">Pending Checkout</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            @if($order->address)
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline-flex flex-wrap items-center gap-2 justify-end min-w-[18rem]">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Isi Nomor Resi Baru" class="border-slate-200 rounded-xl text-xs py-2 px-3 w-full sm:w-40 focus:ring-emerald-500 focus:border-emerald-500">
                                    
                                    <select name="status" class="bg-slate-50 border-slate-200 rounded-xl py-1.5 px-2 text-xs font-bold text-slate-700 focus:ring-emerald-500 focus:border-emerald-500 flex-1 sm:flex-none">
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Dikemas</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai / Kirim</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Batalkan</option>
                                    </select>

                                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-3 rounded-xl text-xs transition-colors flex-1 sm:flex-none">Update</button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 italic">Menunggu Tindakan User</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 font-medium">Belum ada aktivitas pesanan e-commerce masuk harian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
