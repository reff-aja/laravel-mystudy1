<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Panduan - MyStudy</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Cek dan terapkan tema sebelum render untuk mencegah flash
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        /* Custom Scrollbar untuk Sidebar Panduan */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-[#000F0F] min-h-screen relative overflow-x-hidden selection:bg-[#68C7EC]/30 selection:text-white transition-colors duration-300">
    @include('components.page-skeleton', ['page' => 'guide'])

    {{-- AMBIENT BACKGROUND --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#68C7EC]/10 dark:bg-[#68C7EC]/5 rounded-full mix-blend-multiply dark:mix-blend-screen blur-3xl animate-[blob_7s_infinite]"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[400px] h-[400px] bg-blue-300/10 dark:bg-blue-900/5 rounded-full mix-blend-multiply dark:mix-blend-screen blur-3xl animate-[blob_7s_infinite] animation-delay-2000"></div>
    </div>

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#000F0F]/80 backdrop-blur-md border-b border-gray-200 dark:border-[#002525]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="p-2 text-gray-400 hover:text-[#68C7EC] bg-gray-100 dark:bg-[#001818] hover:bg-gray-200 dark:hover:bg-[#002525] rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-xl text-[#68C7EC] flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                        <span>My<span class="text-gray-900 dark:text-white">Study</span></span>
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <button id="theme-toggle" class="p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-[#001818] rounded-xl transition-colors">
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN PANDUAN --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row gap-10 items-start">
            
            {{-- SIDEBAR DAFTAR ISI (Sticky) --}}
            <aside class="w-full md:w-64 shrink-0 order-2 md:order-1 sticky top-24">
                <div class="bg-white/90 dark:bg-[#001818]/90 backdrop-blur-md border border-gray-100 dark:border-[#002525] rounded-3xl p-5 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Daftar Isi</h3>
                    <nav class="space-y-1.5" x-data="{ activeSection: 'intro' }">
                        <a href="#intro" @click="activeSection = 'intro'" :class="activeSection === 'intro' ? 'bg-[#68C7EC]/10 text-[#68C7EC] font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#002525]'" class="block px-4 py-2.5 rounded-xl text-sm transition-all">Selamat Datang</a>
                        <a href="#tugas" @click="activeSection = 'tugas'" :class="activeSection === 'tugas' ? 'bg-[#68C7EC]/10 text-[#68C7EC] font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#002525]'" class="block px-4 py-2.5 rounded-xl text-sm transition-all">Manajemen Tugas</a>
                        <a href="#pomodoro" @click="activeSection = 'pomodoro'" :class="activeSection === 'pomodoro' ? 'bg-[#68C7EC]/10 text-[#68C7EC] font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#002525]'" class="block px-4 py-2.5 rounded-xl text-sm transition-all">Pomodoro Timer</a>
                        <a href="#ai" @click="activeSection = 'ai'" :class="activeSection === 'ai' ? 'bg-[#68C7EC]/10 text-[#68C7EC] font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#002525]'" class="block px-4 py-2.5 rounded-xl text-sm transition-all">Asisten Tanya AI</a>
                        <a href="#musik" @click="activeSection = 'musik'" :class="activeSection === 'musik' ? 'bg-[#68C7EC]/10 text-[#68C7EC] font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#002525]'" class="block px-4 py-2.5 rounded-xl text-sm transition-all">Pemutar Musik (Floating)</a>
                    </nav>
                </div>
            </aside>

            {{-- MAIN READING AREA --}}
            <main class="flex-1 order-1 md:order-2 space-y-12">
                
                {{-- Bagian 1: Intro --}}
                <section id="intro" class="scroll-mt-28">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#68C7EC]/10 text-[#68C7EC] text-xs font-bold mb-4 border border-[#68C7EC]/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pusat Bantuan
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">Panduan Penggunaan MyStudy</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-6">
                        MyStudy dirancang sebagai *all-in-one workspace* untuk memaksimalkan fokus belajar dan eksekusimu. Platform ini memadukan pencatatan tugas, manajemen waktu, hingga asisten AI dalam satu ekosistem yang terintegrasi.
                    </p>
                    <div class="bg-white/60 dark:bg-[#001818]/60 backdrop-blur-md rounded-2xl p-6 border border-gray-100 dark:border-[#002525]">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">💡 Tips Profesional:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Gunakan tombol ganti layout di Dashboard (pojok kanan atas) untuk beralih antara "UI Estetik" (Bento Grid) yang santai atau "UI Profesional" (Sidebar Mode) untuk visibilitas data yang lebih padat.</p>
                    </div>
                </section>

                <hr class="border-gray-200 dark:border-[#002525]">

                {{-- Bagian 2: Manajemen Tugas --}}
                <section id="tugas" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#68C7EC]/10 text-[#68C7EC] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Tugas</h2>
                    </div>
                    <div class="space-y-4 text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>Fitur utama MyStudy adalah daftar eksekusi tugas harian yang intuitif.</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong>Tambah Tugas:</strong> Ketik judul tugas di kolom yang tersedia, atur tanggal & jam pengingat (opsional), lalu tekan tombol "+ Tambah".</li>
                            <li><strong>Status Eksekusi:</strong> Klik kotak centang di sebelah kiri tugas untuk menandainya sebagai selesai. Progres bar di Dashboard akan otomatis menyesuaikan.</li>
                            <li><strong>Filter Cepat:</strong> Klik kartu statistik (Total Tugas, Tugas Aktif, Selesai) di Dashboard mode Pro untuk memfilter daftar tugas secara otomatis.</li>
                            <li><strong>Hapus Tugas:</strong> Klik ikon tempat sampah di sebelah kanan. Jangan khawatir, akan ada peringatan konfirmasi sebelum tugas benar-benar terhapus.</li>
                        </ul>
                    </div>
                </section>

                <hr class="border-gray-200 dark:border-[#002525]">

                {{-- Bagian 3: Pomodoro --}}
                <section id="pomodoro" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pomodoro Timer</h2>
                    </div>
                    <div class="space-y-4 text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>Tersedia secara melayang (floating) di halaman <strong>Chat AI</strong>, widget ini dirancang agar kamu bisa fokus berdiskusi atau mencatat tanpa lupa waktu.</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Secara default, teknik ini menggunakan format <strong>25 Menit Fokus</strong> dan <strong>5 Menit Istirahat</strong>.</li>
                            <li>Klik ikon jam di pojok kiri bawah untuk membuka panel Pomodoro.</li>
                            <li>Pilih tab <strong>Fokus</strong> atau <strong>Istirahat</strong> lalu klik "Mulai".</li>
                            <li>Sistem akan memberikan <strong>Notifikasi Browser</strong> (pastikan kamu mengizinkan akses notifikasi) saat waktu habis dan otomatis berpindah mode.</li>
                        </ul>
                    </div>
                </section>

                <hr class="border-gray-200 dark:border-[#002525]">

                {{-- Bagian 4: AI --}}
                <section id="ai" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Asisten Tanya AI</h2>
                    </div>
                    <div class="space-y-4 text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>Menu <strong>Tanya AI</strong> adalah teman belajarmu. Kamu bisa menanyakan materi pelajaran, meminta ringkasan, atau meminta dibuatkan jadwal belajar.</p>
                        <p>Untuk mengaksesnya, klik menu "Tanya AI" di Sidebar (Mode Pro) atau dari dropdown profil (Mode Bento).</p>
                    </div>
                </section>

                <hr class="border-gray-200 dark:border-[#002525]">

                {{-- Bagian 5: Musik --}}
                <section id="musik" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pemutar Musik (Floating)</h2>
                    </div>
                    <div class="space-y-4 text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>Untuk menemanimu belajar, MyStudy dilengkapi dengan pemutar musik melayang di pojok kanan bawah.</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Masuk ke menu <strong>Musik Fokus</strong> untuk mengatur atau menautkan URL playlist favoritmu (misal: link embed Spotify).</li>
                            <li>Setelah diset, pemutar musik ini akan mengikuti ke halaman mana saja kamu pergi di dalam web MyStudy, sehingga musikmu tidak akan terputus saat berganti halaman.</li>
                        </ul>
                    </div>
                </section>

            </main>
        </div>
    </div>

    {{-- Script Mode Gelap/Terang --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (themeToggleBtn && themeToggleDarkIcon && themeToggleLightIcon) {
                if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    themeToggleLightIcon.classList.remove('hidden');
                } else {
                    themeToggleDarkIcon.classList.remove('hidden');
                }

                themeToggleBtn.addEventListener('click', function () {
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
            }
        });
    </script>
</body>
</html>