<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Unggah Resep Dokter</h1>
            <p class="text-slate-500 mt-1">Punya resep dari dokter? Unggah di sini agar apoteker kami dapat menyiapkan obat khusus Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm h-fit">
                <h2 class="text-xl font-bold text-slate-800 mb-6">Kirim Resep Baru</h2>
                
                <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Foto Resep Dokter <span class="text-red-500">*</span></label>
                        <div class="relative mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-slate-200 border-dashed rounded-2xl hover:border-emerald-400 transition-all cursor-pointer bg-slate-50 overflow-hidden group">
                            <input type="file" name="image" id="prescription_file" required accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            
                            <div id="upload-placeholder" class="space-y-2 text-center relative z-0 pointer-events-none">
                                <div class="bg-white p-3 rounded-full shadow-sm w-fit mx-auto text-slate-400 group-hover:text-emerald-500 transition-colors">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-xs text-slate-500 font-semibold">Klik atau seret foto resep Anda ke sini</p>
                                <p class="text-[10px] text-slate-400">Mendukung format PNG, JPG, JPEG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Catatan untuk Apoteker <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
                        <textarea name="notes" rows="4" class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm placeholder-slate-400 resize-none transition-all" placeholder="Contoh: Tolong siapkan obat generiknya saja, atau tambahkan catatan keluhan tambahan..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 active:scale-98 transition-all">Kirim Resep Sekarang</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden h-fit">
                <div class="p-6 border-b border-slate-50">
                    <h2 class="text-xl font-bold text-slate-800">Riwayat Verifikasi Resep</h2>
                    <p class="text-slate-400 text-xs mt-0.5 font-medium">Pantau status persetujuan resep medis Anda secara berkala.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/70">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pratinjau</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan Anda</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Kirim</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($prescriptions as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="w-16 h-16 rounded-xl border border-slate-100 bg-slate-50 overflow-hidden shadow-sm cursor-pointer" onclick="openPatientLightbox('{{ asset('storage/' . $item->image_path) }}')">
                                        <img src="{{ \Illuminate\Support\Str::startsWith($item->image_path, 'data:image') ? $item->image_path : asset($item->image_path) }}" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <p class="text-slate-700 truncate font-medium">{{ $item->notes ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                            Menunggu Review
                                        </span>
                                    @elseif($item->status === 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            Disetujui / Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                            Resep Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-400 font-medium">Anda belum pernah mengunggah resep dokter sebelumnya.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="patientLightbox" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden z-50 flex flex-col items-center justify-center p-4" onclick="closePatientLightbox()">
        <div class="relative max-w-3xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
            <button type="button" class="absolute -top-12 right-0 text-white/80 hover:text-white flex items-center gap-1 text-sm font-semibold" onclick="closePatientLightbox()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg> Tutup (ESC)
            </button>
            <div class="bg-white p-3 rounded-[2rem] shadow-2xl max-h-[80vh] w-fit">
                <img id="lightbox-patient-img" src="" class="max-h-[75vh] w-auto object-contain rounded-2xl">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('prescription_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const placeholder = document.getElementById('upload-placeholder');
            if (file) {
                placeholder.innerHTML = `
                    <div class="bg-emerald-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-1.5 text-emerald-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-800 px-4 truncate max-w-[240px] mx-auto">${file.name}</p>
                    <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">${(file.size / 1024).toFixed(1)} KB • Siap Kirim</p>
                `;
            }
        });

        const patientLightbox = document.getElementById('patientLightbox');
        function openPatientLightbox(imageSrc) {
            document.getElementById('lightbox-patient-img').src = imageSrc;
            patientLightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closePatientLightbox() {
            patientLightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePatientLightbox();
        });
    </script>
</x-app-layout>