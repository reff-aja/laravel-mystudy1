<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartDo - Selesaikan Tugas Lebih Cepat</title>

    <!-- Memanggil Tailwind CSS bawaan project kita -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ✨ KODE BARU: Script untuk mengingat mode gelap agar tidak kedip putih saat refresh ✨ --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        @keyframes fadeDown {
            0% {
                opacity: 0;
                transform: translateY(-40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal-element {
            opacity: 0;
        }

        .is-visible {
            animation: fadeDown 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }
    </style>
</head>

{{-- Tambahkan kelas transisi di body agar perubahan warna terasa lembut --}}

<body
    class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans overflow-x-hidden transition-colors duration-300">

    {{-- NAVIGASI ATAS --}}
    <nav class="bg-white dark:bg-gray-800 shadow-sm relative z-10 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-bold text-2xl text-indigo-600 dark:text-indigo-400">📝 SmartDo</span>
                </div>

                <div class="flex items-center gap-4">
                    {{-- ✨ KODE BARU: Tombol Toggle Mode Gelap/Terang ✨ --}}
                    <button id="theme-toggle" type="button"
                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5 transition">
                        {{-- Icon Matahari (Muncul saat di mode gelap) --}}
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        {{-- Icon Bulan (Muncul saat di mode terang) --}}
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        <div class="space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold transition">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="bg-indigo-600 dark:bg-indigo-500 text-white px-4 py-2 rounded-md font-semibold hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">Daftar
                                        Gratis</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <main class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24 sm:px-6 lg:mt-32 text-center reveal-element">
        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
            <span class="block">Atur Keseharianmu</span>
            <span class="block text-indigo-600 dark:text-indigo-400">Lebih Rapi & Terstruktur</span>
        </h1>
        <p
            class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            SmartDo adalah aplikasi pencatat tugas modern. Catat pekerjaanmu, atur prioritas, dan pantau produktivitasmu
            dengan antarmuka yang bersih dan cepat.
        </p>
        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
            <div class="rounded-md shadow">
                <a href="{{ route('register') }}"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 md:py-4 md:text-lg md:px-10 transition hover:-translate-y-1 transform duration-200">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </main>

   {{-- FITUR UNGGULAN --}}
    <div class="py-16 mt-32 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                
                {{-- Fitur 1 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-100 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-yellow-500 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sangat Cepat</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Dibangun menggunakan teknologi modern, menambah dan menghapus tugas tanpa lelet.</p>
                </div>

                {{-- Fitur 2 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-200 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-indigo-500 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Filter Cerdas</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pisahkan tugas yang masih aktif dan yang sudah selesai hanya dengan satu klik.</p>
                </div>

                {{-- Fitur 3 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-300 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-green-500 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Aman & Privat</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Data tugasmu terenkripsi dengan aman dan hanya kamu yang bisa mengaksesnya.</p>
                </div>

                {{-- Fitur 4 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-100 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-pink-500 dark:text-pink-400 bg-pink-50 dark:bg-pink-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Playlist Fokus</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Ditemani alunan musik lofi atau instrumen favorit untuk meningkatkan konsentrasi belajarmu.</p>
                </div>

                {{-- Fitur 5 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-200 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-cyan-500 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sikat Habis PR</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pecah tugas sekolah yang menumpuk menjadi langkah-langkah kecil. Bebas stres dan lebih efisien!</p>
                </div>

                {{-- Fitur 6 --}}
                <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center reveal-element delay-300 hover:border-indigo-500 dark:hover:border-indigo-400 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:z-20 active:scale-95 transition-all duration-300 cursor-pointer">
                    <div class="mb-4 text-orange-500 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Riwayat Perjalanan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Pantau terus produktivitasmu. Semua tugas yang selesai tersimpan rapi sebagai motivasi harian.</p>
                </div>

            </div>
        </div>
    </div>


    {{-- FOOTER --}}
    <footer
        class="bg-gray-50 dark:bg-gray-900 py-8 text-center border-t border-gray-200 dark:border-gray-800 mt-12 transition-colors duration-300">
        <p class="text-gray-400 dark:text-gray-500 text-sm">
            &copy; 2026 SmartDo App. Dibuat untuk Portofolio.
        </p>
    </footer>

    {{-- Script Animasi Scroll & Logika Tombol Dark Mode --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Logika Scroll Reveal (Animasi Jatuh)
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

            // 2. Logika Tombol Dark Mode
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Atur icon yang muncul pertama kali
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            // Aksi saat tombol diklik
            themeToggleBtn.addEventListener('click', function () {
                // Ubah icon
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // Jika sebelumnya light, jadikan dark
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                    // Jika belum ada di localStorage, setel berdasarkan klik
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