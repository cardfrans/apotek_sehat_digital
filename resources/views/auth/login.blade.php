<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali</h2>
        <p class="text-slate-500 text-sm mt-2">Masuk untuk mengelola sistem apotek</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-100 text-sm text-teal-700 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400" placeholder="contoh@email.com">
            @error('email') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-teal-600 hover:text-teal-700">Lupa password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900" placeholder="••••••••">
            @error('password') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
            <label for="remember_me" class="ml-2 block text-sm font-medium text-slate-600">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="w-full h-12 mt-2 flex justify-center items-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold shadow-md shadow-teal-500/20 transition-all hover:-translate-y-0.5">
            Masuk ke Dashboard
        </button>
        
        @if (Route::has('register'))
        <p class="text-center text-sm text-slate-500 mt-6">
            Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:text-teal-700">Daftar sekarang</a>
        </p>
        @endif
    </form>
</x-guest-layout>