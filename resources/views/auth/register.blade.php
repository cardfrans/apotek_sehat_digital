<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h2>
        <p class="text-slate-500 text-sm mt-2">Bergabung dengan ekosistem Apotek Sehat</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900" placeholder="Nama Anda">
            @error('name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900" placeholder="contoh@email.com">
            @error('email') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900" placeholder="Minimal 8 karakter">
            @error('password') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 transition-colors bg-slate-50 focus:bg-white text-slate-900" placeholder="Ulangi password">
            @error('password_confirmation') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full h-12 mt-4 flex justify-center items-center rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold shadow-md shadow-teal-500/20 transition-all hover:-translate-y-0.5">
            Daftar Sekarang
        </button>

        <p class="text-center text-sm text-slate-500 mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>