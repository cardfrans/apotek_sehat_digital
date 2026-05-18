<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Konfirmasi Keamanan</h2>
        <p class="text-slate-500 text-sm mt-3 leading-relaxed">Ini adalah area sistem yang dilindungi. Harap konfirmasi password Anda untuk memastikan identitas sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Anda</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
            @error('password') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full h-12 flex justify-center items-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold shadow-md transition-all hover:-translate-y-0.5">
            Lanjutkan
        </button>
    </form>
</x-guest-layout>