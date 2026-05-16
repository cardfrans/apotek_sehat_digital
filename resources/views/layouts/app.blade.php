<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Apotek Sehat Digital') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-[#F8FAFC]">
        <div class="flex h-screen overflow-hidden">
            
            @include('layouts.navigation')

            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                
                <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-30">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <div class="text-sm font-medium text-slate-500">
                            {{ now()->format('l, d F Y') }}
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </header>

                <main class="flex-1">
                    {{ $slot }}
                </main>

            </div>
        </div>

        @if(auth()->user()->role === 'customer')
            
            <button onclick="toggleGlobalChatbot()" id="global-chat-launcher" class="fixed bottom-6 right-6 w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-2xl shadow-emerald-700/30 hover:bg-emerald-700 hover:scale-105 active:scale-95 transition-all duration-300 z-50 group">
                <svg class="w-7 h-7 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </button>

            <div id="global-chat-window" class="fixed bottom-6 right-6 w-[23rem] h-[32rem] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 flex flex-col justify-between overflow-hidden z-50 hidden opacity-0 translate-y-4 transition-all duration-300">
                
                <div class="bg-slate-900 p-5 px-6 flex justify-between items-center text-white shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                        <div>
                            <h4 class="text-sm font-bold tracking-wide">Asisten Konsultasi AI</h4>
                            <p class="text-[10px] text-slate-400 font-medium tracking-wider">Apotek Sehat Digital</p>
                        </div>
                    </div>
                    <button onclick="toggleGlobalChatbot()" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"></path></svg>
                    </button>
                </div>

                <div id="global-chat-body" class="flex-1 p-5 overflow-y-auto space-y-4 bg-slate-50/60 text-xs scrollbar-thin">
                    <div class="flex gap-2.5 items-start max-w-[85%] animate-fade-in">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0">AI</div>
                        <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-sm text-slate-700 leading-relaxed font-medium">
                            Halo! Saya Asisten AI Apotek Sehat. Ada keluhan gejala ringan atau rekomendasi vitamin yang ingin Anda tanyakan hari ini?
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white border-t border-slate-100 rounded-b-[2.5rem]">
                    <form id="global-chat-form" class="flex gap-2 items-center">
                        <input type="text" id="global-chat-input" placeholder="Tulis gejala penyakit ringan Anda..." autocomplete="off" class="flex-1 bg-slate-50 border-slate-200 rounded-xl py-3 px-4 text-xs focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all">
                        <button type="submit" class="p-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-md shadow-emerald-600/10 active:scale-95 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            <script>
        // 1. Fungsi Sakelar Buka-Tutup Minimize Jendela Chat AI
        function toggleGlobalChatbot() {
            const container = document.getElementById('global-chat-container');
            const windowChat = document.getElementById('global-chat-window');
            const chatBody = document.getElementById('global-chat-body');
            
            if(windowChat.classList.contains('hidden')) {
                windowChat.classList.remove('hidden');
                setTimeout(() => {
                    windowChat.classList.remove('opacity-0', 'translate-y-4');
                    windowChat.classList.add('opacity-100', 'translate-y-0');
                }, 50);
                container.classList.add('hidden'); 
                chatBody.scrollTop = chatBody.scrollHeight;
            } else {
                windowChat.classList.add('opacity-0', 'translate-y-4');
                windowChat.classList.remove('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    windowChat.classList.add('hidden');
                }, 300);
                container.classList.remove('hidden'); 
            }
        }

        // 2. Fungsi Utama Penangkap Submit AJAX (Menahan agar tidak lari ke /dashboard?)
        document.getElementById('global-chat-form').addEventListener('submit', async function(e) {
            e.preventDefault(); // <-- KUNCI UTAMA: Menahan form agar tidak me-refresh halaman!
            
            const inputElement = document.getElementById('global-chat-input');
            const chatBody = document.getElementById('global-chat-body');
            const userMessage = inputElement.value.trim();
            
            if(!userMessage) return;

            inputElement.value = '';

            // Tempel Bubble Chat Pengguna ke Widget
            chatBody.innerHTML += `
                <div class="flex gap-2.5 items-start justify-end max-w-[85%] ml-auto">
                    <div class="bg-emerald-600 p-3.5 rounded-2xl text-white shadow-md leading-relaxed font-semibold">
                        ${userMessage}
                    </div>
                </div>
            `;
            chatBody.scrollTop = chatBody.scrollHeight;

            // Buat Indikator Loading Bulat-Bulat Mengambang (Bouncing Dots)
            const typingId = 'typing-' + Date.now();
            chatBody.innerHTML += `
                <div id="${typingId}" class="flex gap-2.5 items-start max-w-[85%]">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0">AI</div>
                    <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm flex items-center gap-1.5 h-10">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
            `;
            chatBody.scrollTop = chatBody.scrollHeight;

            // Tembak Data via AJAX Fetch ke Backend Laravel
            try {
                const response = await fetch('/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: userMessage })
                });

                const data = await response.json();
                
                // Hapus bouncings dots setelah respon sukses/gagal didapatkan
                const typingBubble = document.getElementById(typingId);
                if (typingBubble) typingBubble.remove();

                if(data.status === 'success') {
                    // Tampilkan Jawaban Pintar dari Gemini API
                    chatBody.innerHTML += `
                        <div class="flex gap-2.5 items-start max-w-[85%]">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0">AI</div>
                            <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-sm text-slate-700 leading-relaxed font-medium">
                                ${data.response.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                } else {
                    chatBody.innerHTML += `
                        <div class="flex gap-2.5 items-start max-w-[85%]">
                            <div class="w-7 h-7 rounded-full bg-red-100 text-red-700 font-bold flex items-center justify-center shrink-0">!</div>
                            <div class="bg-red-50 p-3 rounded-2xl border border-red-100 text-red-600 font-medium">
                                Google API Terkendala: ${data.message || 'Gagal memproses pesan.'}
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                const typingBubble = document.getElementById(typingId);
                if (typingBubble) typingBubble.remove();

                chatBody.innerHTML += `
                    <div class="flex gap-2.5 items-start max-w-[85%]">
                        <div class="w-7 h-7 rounded-full bg-red-100 text-red-700 font-bold flex items-center justify-center shrink-0">!</div>
                        <div class="bg-red-50 p-3 rounded-2xl border border-red-100 text-red-600 font-medium">
                            Gagal terhubung ke server apotek.
                        </div>
                    </div>
                `;
            }
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    </script>
        @endif
    </body>
</html>