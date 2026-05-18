<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Verifikasi Resep Dokter</h1>
            <p class="text-slate-500 mt-1">Periksa keaslian foto resep dari pasien untuk memberikan izin pembelian obat.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Pasien / Pengirim</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Pratinjau Resep</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Tanggal Kirim</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600">Status</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-600 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($prescriptions as $prescription)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        
                        <td class="px-6 py-5 max-w-xs">
                            <div class="font-bold text-slate-800">{{ $prescription->user->name }}</div>
                            <div class="text-xs text-slate-400 mt-0.5 mb-2">{{ $prescription->user->email }}</div>
                            @if($prescription->notes)
                                <div class="text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-slate-600 leading-relaxed font-medium">
                                    <span class="font-bold text-slate-700 block text-[10px] uppercase tracking-wider mb-0.5">Catatan Pasien:</span>
                                    {{ $prescription->notes }}
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-5">
                            <div class="relative w-20 h-20 rounded-2xl border border-slate-200 overflow-hidden bg-slate-100 group cursor-zoom-in" 
                                 onclick="openAdminLightbox('{{ \Illuminate\Support\Str::startsWith($prescription->image_path, 'data:image') ? $prescription->image_path : asset($prescription->image_path) }}')">
                                <img src="{{ \Illuminate\Support\Str::startsWith($prescription->image_path, 'data:image') ? $prescription->image_path : asset($prescription->image_path) }}" 
                                        alt="Gambar Obat" 
                                        class="w-full max-w-md rounded-2xl shadow-sm border">
                                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-sm text-slate-600 font-medium">
                            {{ $prescription->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-5">
                            @if($prescription->status === 'pending')
                                <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menunggu Verifikasi
                                </span>
                            @elseif($prescription->status === 'verified')
                                <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 inline-flex items-center gap-1.5">
                                    Terverifikasi
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-100 inline-flex items-center gap-1.5">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            @if($prescription->status === 'pending')
                                <div class="inline-flex gap-2 justify-end">
                                    <form action="{{ route('admin.prescriptions.update', $prescription->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 border border-slate-200 hover:border-red-200 text-xs font-bold rounded-xl shadow-sm transition-all">
                                            Tolak
                                        </button>
                                    </form>

                                    <button type="button" onclick="window.launchApproveModal('{{ $prescription->id }}', '{{ addslashes($prescription->user->name) }}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/10 transition-all">
                                        Setujui Resep
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 font-medium italic">
                                    Selesai diperiksa pada {{ \Carbon\Carbon::parse($prescription->verified_at)->format('d/m/y') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 font-medium">Tidak ada antrean resep dokter masuk saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="orderModal" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-50 items-center justify-center p-4 shadow-xl" style="display: none;">
        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 border border-slate-100 relative shadow-emerald-950/20" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Tentukan Obat Resep</h3>
                    <p class="text-slate-400 text-xs mt-0.5 font-medium" id="modal-patient-title"></p>
                </div>
                <button type="button" onclick="window.closeOrderModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="orderForm" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="verified">

                <div>
                    <label class="text-sm font-semibold text-slate-600 ml-1">Pilih Produk Obat</label>
                    <select name="medicine_id" required class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 py-3 text-sm font-medium">
                        <option value="" disabled selected>-- Pilih Obat Cocok --</option>
                        @foreach($medicines as $med)
                            <option value="{{ $med->id }}">
                                {{ $med->name }} (Stok: {{ $med->stock }} pcs) - Rp {{ number_format($med->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-600 ml-1">Jumlah Obat (Quantity)</label>
                    <input type="number" name="quantity" min="1" value="1" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm py-3">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="window.closeOrderModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3.5 rounded-2xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-emerald-900/10 transition-all">Setujui & Buat Order</button>
                </div>
            </form>
        </div>
    </div>

    <div id="lightbox" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 items-center justify-center p-4" style="display: none;" onclick="window.closeAdminLightbox()">
        <div class="relative max-w-3xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
            <button type="button" class="absolute -top-12 right-0 text-white/80 hover:text-white flex items-center gap-1 text-sm font-semibold" onclick="window.closeAdminLightbox()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg> Tutup (ESC)
            </button>
            <div class="bg-white p-3 rounded-[2rem] shadow-2xl max-h-[80vh] w-fit">
                <img id="lightbox-img" src="" class="max-h-[75vh] w-auto object-contain rounded-2xl">
            </div>
            <p id="lightbox-caption" class="text-white text-sm font-medium mt-4 bg-slate-900/60 backdrop-blur-md px-4 py-2 rounded-full"></p>
        </div>
    </div>

    <script>
        // Ikat fungsi ke objek window global agar bisa dieksekusi secara instan dari file HTML mana saja
        window.launchApproveModal = function(prescriptionId, patientName) {
            const modal = document.getElementById('orderModal');
            document.getElementById('modal-patient-title').innerText = "Pasien: " + patientName;
            document.getElementById('orderForm').action = '/admin/prescriptions/' + prescriptionId + '/status';
            
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        window.closeOrderModal = function() {
            document.getElementById('orderModal').style.display = 'none';
            document.body.style.overflow = '';
        };

        window.openAdminLightbox = function(imageSrc, patientName) {
            document.getElementById('lightbox-img').src = imageSrc;
            document.getElementById('lightbox-caption').innerText = "Resep milik: " + patientName;
            document.getElementById('lightbox').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        window.closeAdminLightbox = function() {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = '';
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { 
                window.closeAdminLightbox(); 
                window.closeOrderModal(); 
            }
        });
    </script>
</x-app-layout>