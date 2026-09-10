<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Profil - MyStudy</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-[#000F0F] min-h-screen transition-colors duration-300" x-data="{ userDropdown: false }">

    {{-- NAVBAR ATAS (Sama persis dengan Dashboard) --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#000F0F]/80 backdrop-blur-md border-b border-gray-200 dark:border-[#002525] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-[#68C7EC] flex items-center hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            <path d="M9 14l2 2 4-4"></path>
                        </svg>
                        MyStudy
                    </a>
                </div>

                {{-- Tombol Kembali --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-[#68C7EC] transition-colors flex items-center gap-1">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- AREA UTAMA PROFIL --}}
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Pengaturan Akun</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Kelola informasi profil, keamanan kata sandi, dan preferensi akunmu.</p>
        </div>

        <div class="space-y-6">
            {{-- Bagian 1: Update Informasi Profil --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-[#001818] shadow-sm sm:rounded-3xl border border-gray-100 dark:border-[#002525]">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Bagian 2: Update Password --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-[#001818] shadow-sm sm:rounded-3xl border border-gray-100 dark:border-[#002525]">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Bagian 3: Hapus Akun --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-[#001818] shadow-sm sm:rounded-3xl border border-gray-100 dark:border-[#002525]">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </main>

</body>
</html>