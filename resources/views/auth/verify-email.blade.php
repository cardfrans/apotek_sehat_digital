<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Verifikasi Email</h2>
        <p class="text-slate-500 text-sm mt-3 leading-relaxed">Terima kasih telah bergabung! Silakan periksa kotak masuk Anda dan klik tautan yang telah kami kirimkan. Jika Anda tidak menerimanya, kami dapat mengirimkan ulang.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-100 text-sm text-teal-700 text-center font-medium">
            Tautan verifikasi baru berhasil dikirimkan ke alamat email Anda.
        </div>
    @endif

    <div class="flex flex-col space-y-3 mt-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full h-12 flex justify-center items-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold shadow-md transition-all hover:-translate-y-0.5">
                Kirim Ulang Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full h-12 flex justify-center items-center rounded-xl bg-white hover:bg-slate-50 text-slate-600 font-bold border border-slate-200 transition-colors">
                Keluar Akun
            </button>
        </form>
    </div>
</x-guest-layout>