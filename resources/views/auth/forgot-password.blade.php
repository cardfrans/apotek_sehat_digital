<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4v-3.286l7.465-7.465A6 6 0 1115 7z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Lupa Password?</h2>
        <p class="text-slate-500 text-sm mt-3 leading-relaxed">Jangan khawatir. Masukkan email Anda dan sistem kami akan mengirimkan tautan untuk mengatur ulang sandi Anda.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-100 text-sm text-teal-700 font-medium text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
            @error('email') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full h-12 flex justify-center items-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold shadow-md shadow-teal-500/20 transition-all hover:-translate-y-0.5">
            Kirim Tautan Reset
        </button>
        
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-400 hover:text-teal-600 transition-colors">Kembali ke halaman Login</a>
        </div>
    </form>
</x-guest-layout>