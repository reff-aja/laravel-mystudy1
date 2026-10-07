<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Pusat Bantuan - MyStudy</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-[#000F0F] min-h-screen transition-colors duration-300">

    {{-- NAVBAR ATAS --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#000F0F]/80 backdrop-blur-md border-b border-gray-200 dark:border-[#002525] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-[#68C7EC] flex items-center hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 022 2h2a2 2 0 022-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            <path d="M9 14l2 2 4-4"></path>
                        </svg>
                        MyStudy
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-[#68C7EC] transition-colors flex items-center gap-1">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- HEADER SAPAAN --}}
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="bg-[#68C7EC]/10 text-[#68C7EC] border border-[#68C7EC]/20 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                Pusat Bantuan
            </span>
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mt-4 mb-3">
                Apa yang bisa kami bantu? 👋
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-base">
                Temukan panduan cepat dan solusi lengkap untuk memaksimalkan penggunaan fitur-fitur di SmartDo.
            </p>
        </div>

        {{-- GRID KARTU PANDUAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Kartu 1: Kelola Tugas --}}
            <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl border border-gray-100 dark:border-[#002525] shadow-sm hover:border-[#68C7EC] dark:hover:border-[#68C7EC] hover:shadow-md dark:hover:shadow-[#68C7EC]/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#68C7EC]/10 text-[#68C7EC] rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform border border-[#68C7EC]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Manajemen Tugas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Ketik tugas baru di form input utama pada Dashboard. Klik tombol centang untuk menandai tugas selesai, atau ikon tempat sampah untuk menghapusnya.
                </p>
            </div>

            {{-- Kartu 2: Music Floating Player --}}
            <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl border border-gray-100 dark:border-[#002525] shadow-sm hover:border-[#68C7EC] dark:hover:border-[#68C7EC] hover:shadow-md dark:hover:shadow-[#68C7EC]/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#68C7EC]/10 text-[#68C7EC] rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform border border-[#68C7EC]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Playlist Musik Fokus</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Buka menu "Playlist Musik", tempel link playlist Spotify/YouTube milikmu. Pemutar musik mengambang (*floating player*) siap menemanimu bekerja tanpa reload.
                </p>
            </div>

            {{-- Kartu 3: Streak & Progres --}}
            <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl border border-gray-100 dark:border-[#002525] shadow-sm hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-md dark:hover:shadow-amber-500/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform border border-amber-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sistem Streak Harian</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Selesaikan minimal satu tugas setiap hari untuk menjaga angka *streak* kamu terus bertambah dan melihat grafik aktivitas 7 hari terakhir.
                </p>
            </div>

            {{-- Kartu 4: Asisten AI --}}
            <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl border border-gray-100 dark:border-[#002525] shadow-sm hover:border-[#68C7EC] dark:hover:border-[#68C7EC] hover:shadow-md dark:hover:shadow-[#68C7EC]/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#68C7EC]/10 text-[#68C7EC] rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform border border-[#68C7EC]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Integrasi AI Assistant</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Klik ikon AI di samping baris tugas untuk meminta ide pemecahan langkah kerja (*sub-tasks*) atau konsultasikan produktivitasmu langsung dengan AI.
                </p>
            </div>

            {{-- Kartu 5: Pengingat & Notifikasi --}}
            <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl border border-gray-100 dark:border-[#002525] shadow-sm hover:border-[#68C7EC] dark:hover:border-[#68C7EC] hover:shadow-md dark:hover:shadow-[#68C7EC]/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#68C7EC]/10 text-[#68C7EC] rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform border border-[#68C7EC]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Pengingat Waktu</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Atur tanggal dan jam pengingat saat membuat tugas agar sistem dapat memberikan lonceng notifikasi tepat sebelum tenggat waktu tiba.
                </p>
            </div>

            {{-- Kartu 6: Tema & Profil --}}
            <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl border border-gray-100 dark:border-[#002525] shadow-sm hover:border-[#68C7EC] dark:hover:border-[#68C7EC] hover:shadow-md dark:hover:shadow-[#68C7EC]/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#68C7EC]/10 text-[#68C7EC] rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform border border-[#68C7EC]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Pengaturan Akun</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Gunakan ikon bulan/matahari di navigasi atas untuk berganti mode gelap/terang, dan akses Pengaturan Profil untuk mengganti nama atau kata sandi.
                </p>
            </div>

        </div>
    </main>

</body>
</html>