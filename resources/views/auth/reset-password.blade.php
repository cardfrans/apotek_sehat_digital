<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-900">Atur Ulang Sandi</h2>
        <p class="text-slate-500 text-sm mt-2">Silakan buat password baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
            @error('email') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
            @error('password') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
            @error('password_confirmation') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full h-12 mt-4 flex justify-center items-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold shadow-md transition-all hover:-translate-y-0.5">
            Simpan Password Baru
        </button>
    </form>
</x-guest-layout>