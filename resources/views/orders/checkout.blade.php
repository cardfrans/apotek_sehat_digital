<x-app-layout>
    <div class="p-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Lengkapi Pengiriman</h1>
            <p class="text-slate-500 mt-1">Isi alamat rumah Anda untuk menghitung tarif ongkir resmi dari Gudang Utama Batam secara real-time.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-5">
                <form id="checkoutForm" action="{{ route('orders.checkout.store', $pendingOrder->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <input type="hidden" name="province_name" id="province_name">
                    <input type="hidden" name="city_name" id="city_name">
                    <input type="hidden" name="courier" id="courier_hidden_input">
                    <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">

                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Alamat Lengkap Rumah</label>
                        <textarea name="address" required rows="3" class="w-full mt-1 border-slate-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm resize-none" placeholder="Nama jalan, nomor rumah, RT/RW, dan kelurahan..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-600 ml-1">Provinsi Tujuan</label>
                            <select id="province-select" required class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="" disabled selected>-- Pilih Provinsi --</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600 ml-1">Kota / Kabupaten</label>
                            <select id="city-select" required disabled class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="" disabled selected>-- Pilih Kota --</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-600 ml-1">Pilihan Opsi Kurir & Tarif Ongkir</label>
                        <select id="unified-shipping-select" required disabled class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="" disabled selected>-- Pilih Kota Terlebih Dahulu --</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-xl h-fit flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold tracking-wide border-b border-slate-800 pb-3 mb-4">Ringkasan Belanja</h3>
                    <div class="space-y-3 text-xs font-medium text-slate-400">
                        <div class="flex justify-between"><span>Harga Obat Racikan</span><span class="text-white">Rp {{ number_format($pendingOrder->total_price, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span>Total Berat Barang</span><span class="text-white font-bold">{{ $pendingOrder->weight ?? 100 }} gram</span></div>
                        <div class="flex justify-between"><span>Ongkos Kirim Paket</span><span id="txt-ongkir" class="text-white">Rp 0</span></div>
                        <div class="flex justify-between"><span>Kemasan Privasi Aman</span><span class="text-emerald-400">FREE</span></div>
                    </div>
                    <div class="border-t border-slate-800 mt-4 pt-4 flex justify-between items-baseline">
                        <span class="text-sm font-bold">Total Bayar</span>
                        <span id="txt-total" data-base="{{ $pendingOrder->total_price }}" class="text-2xl font-extrabold text-emerald-400">Rp {{ number_format($pendingOrder->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="space-y-2 mt-6">
                    <button type="submit" form="checkoutForm" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl text-center shadow-lg shadow-emerald-900/40 transition-all text-sm hover:bg-emerald-700">
                        Konfirmasi Pembayaran
                    </button>

                    <a href="{{ $pendingOrder->id === 'cart' ? route('cart.index') : route('dashboard') }}" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-3.5 rounded-2xl text-center transition-all text-sm block">
                        Batalkan & Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', async function() {
        const provSelect = document.getElementById('province-select');
        const citySelect = document.getElementById('city-select');
        const shippingSelect = document.getElementById('unified-shipping-select');

        // CATATAN: Jika Anda menggunakan Solusi 2 (v1), ubah '/api/' di bawah ini menjadi '/v1/'
        const apiPrefix = '/logistik/'; 

        // 1. Muat Data Provinsi Pertama Kali
        try {
            const res = await fetch(`${apiPrefix}provinces`);
            const provinces = await res.json();
            provinces.forEach(p => {
                const pId = p.province_id || p.id || p.id_province;
                const pName = p.province || p.name || p.province_name;
                if(pId && pName) {
                    provSelect.innerHTML += `<option value="${pId}">${pName}</option>`;
                }
            });
        } catch (e) { 
            console.error("Gagal mengambil data provinsi."); 
        }

        // 2. Event Saat Provinsi Diubah (Memuat data Kota)
        provSelect.addEventListener('change', async function() {
            document.getElementById('province_name').value = this.options[this.selectedIndex].text;
            
            // Reset Dropdown di bawahnya agar bersih
            citySelect.innerHTML = '<option value="" disabled selected>-- Pilih Kota --</option>';
            citySelect.disabled = true;
            shippingSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kota Terlebih Dahulu --</option>';
            shippingSelect.disabled = true;

            try {
                const res = await fetch(`${apiPrefix}cities/${this.value}`);
                const cities = await res.json();
                
                cities.forEach(c => {
                    const cId = c.city_id || c.id || c.id_city || c.city_code;
                    const cName = c.city_name || c.name || c.city || c.city_title;
                    const cType = c.type || c.status || '';
                    if(cId && cName) {
                        citySelect.innerHTML += `<option value="${cId}">${cType} ${cName}</option>`;
                    }
                });
                citySelect.disabled = false;
            } catch (error) {
                citySelect.innerHTML = '<option value="" disabled>❌ Gagal memuat data kota</option>';
            }
        });

        // 3. Event Saat Kota Diubah (Dua fungsi digabung jadi satu agar tidak terjadi Balapan Data / NaN)
        citySelect.addEventListener('change', async function() {
            // Set nama kota ke hidden input
            document.getElementById('city_name').value = this.options[this.selectedIndex].text;
            
            // Reset tampilan total bayar kembali ke harga dasar obat saat kota diganti
            const basePrice = parseInt(document.getElementById('txt-total').getAttribute('data-base'));
            document.getElementById('txt-ongkir').innerText = "Rp 0";
            document.getElementById('txt-total').innerText = "Rp " + basePrice.toLocaleString('id-ID');
            document.getElementById('shipping_cost_input').value = 0;

            // Tampilkan status loading kurir
            shippingSelect.innerHTML = '<option value="" disabled selected>⏳ Memuat pilihan opsi tarif kurir dari Batam...</option>';
            shippingSelect.disabled = true;

            try {
                const res = await fetch(`${apiPrefix}check-ongkir`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        destination: citySelect.value, 
                        order_id: "{{ $pendingOrder->id }}"
                    })
                });

                const services = await res.json();
                shippingSelect.innerHTML = '<option value="" disabled selected>-- Pilih Paket Kurir & Tarif Harga --</option>';

                if(!services || services.length === 0) {
                    shippingSelect.innerHTML = '<option value="" disabled>❌ Tidak ada kurir yang mendukung rute ini</option>';
                    return;
                }

                services.forEach(s => {
                    const optionText = `${s.courier} - ${s.service} (${s.description}) [Estimasi: ${s.etd} Hari] - Rp ${parseInt(s.cost).toLocaleString('id-ID')}`;
                    shippingSelect.innerHTML += `<option value="${s.cost}" data-courier="${s.courier}">${optionText}</option>`;
                });

                shippingSelect.disabled = false;

            } catch (error) {
                shippingSelect.innerHTML = '<option value="" disabled>❌ Gagal memuat data logistik</option>';
            }
        });

        // 4. Event Saat Paket Kurir Dipilih (Hitung Total Akhir Belanja)
        shippingSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const ongkirValue = parseInt(this.value);
            const courierName = selectedOption.getAttribute('data-courier');
            const basePrice = parseInt(document.getElementById('txt-total').getAttribute('data-base'));

            // Masukkan data ke input hidden form untuk dikirim ke database saat checkout
            document.getElementById('shipping_cost_input').value = ongkirValue;
            document.getElementById('courier_hidden_input').value = courierName;

            // Perbarui tampilan teks di layar secara real-time
            document.getElementById('txt-ongkir').innerText = "Rp " + ongkirValue.toLocaleString('id-ID');
            document.getElementById('txt-total').innerText = "Rp " + (basePrice + ongkirValue).toLocaleString('id-ID');
        });
    });
</script>
</x-app-layout>