<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Kelola Inventaris Obat</h1>
            <p class="text-slate-500 mt-1">Tambahkan produk obat baru atau perbarui jumlah stok serta berat barang secara real-time.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm h-fit">
                <h2 class="text-xl font-bold text-slate-800 mb-6">Tambah Obat Baru</h2>
                
                <form action="{{ route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Nama Produk Obat</label>
                        <input type="text" name="name" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm py-3" placeholder="Contoh: Paracetamol 500mg">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-600 ml-1">Harga (Rp)</label>
                            <input type="number" name="price" min="0" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xs py-3" placeholder="0">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-600 ml-1">Stok (Pcs)</label>
                            <input type="number" name="stock" min="0" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xs py-3" placeholder="0">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-600 ml-1">Berat (Gram)</label>
                            <input type="number" name="weight" min="1" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xs py-3" placeholder="100">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Foto Kemasan Obat</label>
                        <div class="relative mt-1 flex justify-center px-6 pt-6 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-emerald-400 cursor-pointer bg-slate-50 group transition-colors">
                            <input type="file" name="image" id="create_med_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div id="create-placeholder" class="space-y-1 text-center pointer-events-none relative z-0">
                                <svg class="mx-auto h-8 w-8 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <p class="text-xs text-slate-500 font-semibold">Klik atau seret gambar ke sini</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-emerald-900/10 hover:bg-emerald-700 active:scale-98 transition-all mt-2">Simpan Produk Obat</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden h-fit">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Info Produk Obat</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Gudang</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($medicines as $med)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl border border-slate-100 overflow-hidden bg-slate-50 shrink-0">
                                    @if($med->image_path)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($med->image_path, 'data:image') ? $med->image_path : asset($med->image_path) }}" 
                                        alt="Gambar Obat" 
                                        class="w-full max-w-md rounded-2xl shadow-sm border">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg></div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 block leading-tight">{{ $med->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium mt-0.5 block">Massa: <span class="text-slate-600 font-bold">{{ $med->weight ?? 100 }} gram</span></span>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-800">
                                Rp {{ number_format($med->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-md {{ $med->stock <= 10 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                                    {{ $med->stock }} pcs
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button type="button" 
                                        onclick="window.openEditMedicineModal('{{ $med->id }}', '{{ addslashes($med->name) }}', '{{ $med->price }}', '{{ $med->stock }}', '{{ $med->weight ?? 100 }}')" 
                                        class="p-2 text-slate-400 hover:text-emerald-600 bg-slate-50 hover:bg-emerald-50 rounded-xl transition-all inline-flex shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-slate-400 font-medium">Gudang inventaris kosong. Silakan input produk obat pertama Anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editMedicineModal" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-50 items-center justify-center p-4 shadow-2xl animate-fade-in" style="display: none;" onclick="window.closeEditMedicineModal()">
        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 border border-slate-100 relative shadow-emerald-950/20" onclick="event.stopPropagation()">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Edit Data Obat</h3>
                    <p class="text-slate-400 text-xs mt-0.5 font-medium">Ubah spesifikasi data medis atau berat paket.</p>
                </div>
                <button type="button" onclick="window.closeEditMedicineModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="editMedicineForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="text-sm font-semibold text-slate-600 ml-1">Nama Produk Obat</label>
                    <input type="text" name="name" id="edit_name" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm py-3">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Harga (Rp)</label>
                        <input type="number" name="price" id="edit_price" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xs py-3">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Stok (Pcs)</label>
                        <input type="number" name="stock" id="edit_stock" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xs py-3">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Berat (Gram)</label>
                        <input type="number" name="weight" id="edit_weight" required class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xs py-3">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-600 ml-1">Perbarui Gambar Baru <span class="text-[10px] text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="file" name="image" accept="image/*" class="w-full mt-1 border border-slate-200 bg-slate-50 text-slate-500 rounded-xl text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="window.closeEditMedicineModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3.5 rounded-2xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-emerald-900/10 transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('create_med_image');
            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const placeholder = document.getElementById('create-placeholder');
                    if (file && placeholder) {
                        placeholder.innerHTML = `
                            <p class="text-xs font-bold text-emerald-600 truncate max-w-[200px] mx-auto">✓ ${file.name}</p>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">${(file.size / 1024).toFixed(1)} KB • Siap</p>
                        `;
                    }
                });
            }
        });

        window.openEditMedicineModal = function(id, name, price, stock, weight) {
            const modal = document.getElementById('editMedicineModal');
            if (modal) {
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_price').value = price;
                document.getElementById('edit_stock').value = stock;
                document.getElementById('edit_weight').value = weight;
                
                document.getElementById('editMedicineForm').action = '/admin/medicines/' + id;
                
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeEditMedicineModal = function() {
            const modal = document.getElementById('editMedicineModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') window.closeEditMedicineModal();
        });
    </script>
</x-app-layout>