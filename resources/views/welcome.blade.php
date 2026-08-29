<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartDo - Selesaikan Tugas Lebih Cepat</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        @keyframes fadeDown {
            0% { opacity: 0; transform: translateY(-40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .reveal-element { opacity: 0; }
        .is-visible { animation: fadeDown 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-sans overflow-x-hidden transition-colors duration-300">

    {{-- STICKY NAVBAR --}}
    <nav id="navbar" class="fixed top-0 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 shadow-sm z-50 transition-all duration-500">
        <div id="navbar-container" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-700 ease-in-out">
            <div class="relative flex justify-between h-16 items-center">
                
                {{-- ✨ REVISI: Bagian Kiri (Logo) didorong sedikit ke kanan dengan md:ml-8 lg:ml-12 ✨ --}}
                <div class="flex-shrink-0 flex items-center md:ml-8 lg:ml-12 transition-all duration-500">
                    <a href="#beranda" class="font-bold text-2xl text-indigo-600 dark:text-indigo-400 hover:scale-105 transition-transform cursor-pointer whitespace-nowrap">📝 SmartDo</a>
                </div>
                
                {{-- Bagian Tengah (Menu Navigasi) --}}
                <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 space-x-8 whitespace-nowrap">
                    <a href="#beranda" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">Beranda</a>
                    <a href="#fitur" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">Fitur Utama</a>
                    <a href="#cara-kerja" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">Cara Kerja</a>
                    <a href="#faq" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">FAQ</a>
                </div>

                {{-- Bagian Kanan (Tombol) --}}
                <div class="flex-shrink-0 flex items-center gap-2 sm:gap-4 transition-all duration-500">
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none rounded-lg text-sm p-2 transition">
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold transition hidden sm:inline-block whitespace-nowrap">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-md font-semibold transition shadow-sm hover:shadow-md whitespace-nowrap">Daftar Gratis</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <main id="beranda" class="pt-32 pb-16 mx-auto max-w-7xl px-4 sm:pt-40 sm:px-6 lg:pt-48 text-center reveal-element">
        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
            <span class="block">Atur Keseharianmu</span>
            <span class="block text-indigo-600 dark:text-indigo-400">Lebih Rapi & Terstruktur</span>
        </h1>
        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            SmartDo adalah aplikasi pencatat tugas modern. Catat pekerjaanmu, atur prioritas, dan pantau produktivitasmu dengan antarmuka yang bersih dan cepat.
        </p>
        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
            <div class="rounded-md shadow">
                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 md:py-4 md:text-lg md:px-10 transition hover:-translate-y-1 transform duration-200">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </main>

    {{-- FITUR UNGGULAN --}}
    <div id="fitur" class="py-16 mt-16 bg-white dark:bg-gray-900 transition-colors duration-300 scroll-mt-24 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal-element">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Fitur Unggulan</h2>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Semua yang kamu butuhkan untuk tetap produktif.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center pt-4">
                {{-- Fitur 1 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-100 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-yellow-500 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sangat Cepat</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Dibangun menggunakan teknologi modern, menambah dan menghapus tugas tanpa lelet.</p>
                </div>
                {{-- Fitur 2 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-200 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-indigo-500 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Filter Cerdas</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pisahkan tugas yang masih aktif dan yang sudah selesai hanya dengan satu klik.</p>
                </div>
                {{-- Fitur 3 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-300 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-green-500 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Aman & Privat</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Data tugasmu terenkripsi dengan aman dan hanya kamu yang bisa mengaksesnya.</p>
                </div>
                {{-- Fitur 4 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-100 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-pink-500 dark:text-pink-400 bg-pink-50 dark:bg-pink-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Playlist Fokus</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Ditemani alunan musik lofi atau instrumen favorit untuk meningkatkan konsentrasi belajarmu.</p>
                </div>
                {{-- Fitur 5 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-200 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-cyan-500 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sikat Habis PR</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pecah tugas sekolah yang menumpuk menjadi langkah-langkah kecil. Bebas stres dan lebih efisien!</p>
                </div>
                {{-- Fitur 6 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-300 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-orange-500 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Riwayat Perjalanan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pantau terus produktivitasmu. Semua tugas yang selesai tersimpan rapi sebagai motivasi harian.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CARA KERJA --}}
    <div id="cara-kerja" class="py-20 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 scroll-mt-24 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal-element">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Cara Kerja</h2>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Tiga langkah mudah menuju produktivitas maksimal.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative text-center">
                <div class="hidden md:block absolute top-12 left-1/6 right-1/6 h-1 bg-indigo-100 dark:bg-indigo-900/30 -translate-y-1/2 z-0 rounded-full"></div>
                <div class="relative z-10 flex flex-col items-center reveal-element delay-100">
                    <div class="w-24 h-24 mb-6 bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-3xl font-extrabold border-8 border-gray-50 dark:border-gray-950 shadow-md">1</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Buat Akun</h3>
                    <p class="text-gray-500 dark:text-gray-400">Daftar gratis dalam hitungan detik. Data kamu aman dan tersimpan rapi selamanya.</p>
                </div>
                <div class="relative z-10 flex flex-col items-center reveal-element delay-200">
                    <div class="w-24 h-24 mb-6 bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-3xl font-extrabold border-8 border-gray-50 dark:border-gray-950 shadow-md">2</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Catat Tugas</h3>
                    <p class="text-gray-500 dark:text-gray-400">Tulis apa yang perlu dikerjakan hari ini. Jangan biarkan tugas menumpuk di pikiran.</p>
                </div>
                <div class="relative z-10 flex flex-col items-center reveal-element delay-300">
                    <div class="w-24 h-24 mb-6 bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-3xl font-extrabold border-8 border-gray-50 dark:border-gray-950 shadow-md">3</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Selesaikan!</h3>
                    <p class="text-gray-500 dark:text-gray-400">Centang tugas yang sudah beres dan rasakan kepuasan menyelesaikan pekerjaanmu.</p>
                </div>
            </div>
            
            <div class="mt-16 text-center reveal-element delay-300">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Mulai Bikin Tugas Sekarang &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- FAQ --}}
    <div id="faq" class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300 scroll-mt-24 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal-element">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Pertanyaan Sering Diajukan</h2>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Masih ragu? Temukan jawabanmu di bawah ini.</p>
            </div>

            <div class="space-y-4 reveal-element delay-100">
                
                {{-- FAQ 1 --}}
                <div x-data="{ open: false }" class="relative bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300" :class="open ? 'shadow-md dark:shadow-indigo-500/10' : ''">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 transition-transform duration-300 origin-top" :class="open ? 'scale-y-100' : 'scale-y-0'"></div>
                    <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white text-lg">Apa itu SmartDo?</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-5 pt-2 text-gray-600 dark:text-gray-300" style="display: none;">
                        <p>SmartDo adalah aplikasi pencatat tugas (To-Do List) modern yang dirancang khusus untuk membantumu mengatur keseharian, memecah tugas besar menjadi langkah kecil, dan memantau produktivitas dengan antarmuka yang sangat cepat dan bebas gangguan.</p>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div x-data="{ open: false }" class="relative bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300" :class="open ? 'shadow-md dark:shadow-indigo-500/10' : ''">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 transition-transform duration-300 origin-top" :class="open ? 'scale-y-100' : 'scale-y-0'"></div>
                    <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white text-lg">Apakah aplikasi SmartDo ini gratis?</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-5 pt-2 text-gray-600 dark:text-gray-300" style="display: none;">
                        <p>Tentu saja! SmartDo 100% gratis untuk digunakan. Karena aplikasi ini dibangun sebagai proyek portofolio, kamu tidak akan menemukan biaya langganan bulanan yang tersembunyi atau iklan yang mengganggu layar belajarmu.</p>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div x-data="{ open: false }" class="relative bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300" :class="open ? 'shadow-md dark:shadow-indigo-500/10' : ''">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 transition-transform duration-300 origin-top" :class="open ? 'scale-y-100' : 'scale-y-0'"></div>
                    <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white text-lg">Apakah ini akan menurunkan kemampuan belajar saya?</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-5 pt-2 text-gray-600 dark:text-gray-300" style="display: none;">
                        <p>Justru sebaliknya! Dengan memindahkan beban mengingat tugas dari otak ke dalam SmartDo, kamu mengurangi stres dan kelelahan mental <strong>(cognitive load)</strong>. Ini membuat otakmu memiliki ruang lebih untuk fokus pada proses memahami materi, bukan sekadar mengingat *deadline* PR.</p>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div x-data="{ open: false }" class="relative bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300" :class="open ? 'shadow-md dark:shadow-indigo-500/10' : ''">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 transition-transform duration-300 origin-top" :class="open ? 'scale-y-100' : 'scale-y-0'"></div>
                    <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white text-lg">Mengapa saya harus memilih SmartDo?</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-5 pt-2 text-gray-600 dark:text-gray-300" style="display: none;">
                        <p>SmartDo dirancang agar sangat ringan, cepat, dan bebas distraksi. Kami menghindari fitur rumit yang justru membuat pengguna pusing. Ditambah dengan antarmuka modern dan mode gelap (Dark Mode), aplikasi ini secara khusus dibangun agar kamu bisa langsung fokus mengeksekusi tugasmu tanpa buang-buang waktu.</p>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div x-data="{ open: false }" class="relative bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300" :class="open ? 'shadow-md dark:shadow-indigo-500/10' : ''">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 transition-transform duration-300 origin-top" :class="open ? 'scale-y-100' : 'scale-y-0'"></div>
                    <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white text-lg">Apakah data saya aman?</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-5 pt-2 text-gray-600 dark:text-gray-300" style="display: none;">
                        <p>Sangat aman. Sistem kami dibangun menggunakan kerangka keamanan dari Laravel. Semua <i>password</i> kamu dienkripsi menggunakan protokol modern. Data tugasmu juga bersifat 100% privat, yang artinya hanya kamu yang bisa mengakses dan mengelolanya melalui akunmu.</p>
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div x-data="{ open: false }" class="relative bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300" :class="open ? 'shadow-md dark:shadow-indigo-500/10' : ''">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500 transition-transform duration-300 origin-top" :class="open ? 'scale-y-100' : 'scale-y-0'"></div>
                    <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-bold text-gray-900 dark:text-white text-lg">SmartDo kan menghindari distraksi, mengapa ada fitur musik?</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-5 pt-2 text-gray-600 dark:text-gray-300" style="display: none;">
                        <p>Distraksi yang kita basmi adalah hal-hal yang bikin hilang fokus seperti notifikasi medsos atau tab <i>browser</i> yang berlebihan. Nah, untuk urusan musik, SmartDo justru membebaskanmu untuk <strong>mengkustomisasi <i>playlist</i> sendiri</strong>! Mau lagu yang nge-beat, <i>hype</i>, atau genre apapun yang bikin kamu tetap melek dan semangat ngerjain tugas, semuanya bisa diatur sesuai <i>vibe</i> kamu.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 dark:bg-black py-12 text-center transition-colors duration-300">
        <p class="text-gray-400 dark:text-gray-500 text-sm">
            &copy; 2026 SmartDo App. Dibuat untuk Portofolio.
        </p>
    </footer>

    {{-- Script Animasi Scroll & Logika Tombol Dark Mode --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navContainer = document.getElementById('navbar-container');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 20) {
                    navContainer.classList.remove('max-w-7xl');
                    navContainer.classList.add('max-w-5xl'); 
                } else {
                    navContainer.classList.add('max-w-7xl');
                    navContainer.classList.remove('max-w-5xl'); 
                }
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            const hiddenElements = document.querySelectorAll('.reveal-element');
            hiddenElements.forEach((el) => observer.observe(el));

            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        });
    </script>
</body>
</html>