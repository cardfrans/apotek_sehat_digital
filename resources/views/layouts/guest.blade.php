<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Apotek Sehat Digital') }} - Akses Masuk</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 selection:bg-teal-600 selection:text-white relative overflow-hidden">
        
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-teal-200/30 rounded-full filter blur-[100px] -z-10"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50%] h-[50%] bg-emerald-200/30 rounded-full filter blur-[100px] -z-10"></div>

        <div class="min-h-screen flex flex-col justify-center items-center p-6">
            <div class="mb-8">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-xl bg-teal-600 flex items-center justify-center text-white font-bold shadow-lg shadow-teal-500/30 transition-transform group-hover:scale-105">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight text-slate-900">
                        Apotek<span class="text-teal-600">Sehat</span>
                    </span>
                </a>
            </div>

            <div class="w-full max-w-md px-8 py-10 bg-white/80 backdrop-blur-xl shadow-2xl shadow-slate-200/50 border border-white overflow-hidden rounded-3xl relative">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-teal-400 to-teal-600"></div>
                
                {{ $slot }}
            </div>
            
            <div class="mt-10 text-center text-sm font-medium text-slate-400">
                &copy; {{ date('Y') }} Apotek Sehat Digital.
            </div>
        </div>
    </body>
</html>