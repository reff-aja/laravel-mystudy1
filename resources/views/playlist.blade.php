<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Playlist Musik - MyStudy</title>
    
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
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-[#68C7EC] flex items-center gap-2 hover:scale-105 transition-transform">
                        <img src="{{ asset('images/logo.png') }}" alt="MyStudy Logo" class="w-9 h-9 object-contain">
                        <span>MyStudy</span>
                    </a>
                </div>

                {{-- Kembalikan ke Dashboard --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-[#68C7EC] transition-colors flex items-center gap-1">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- HEADER KANAN / KIRI --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white flex items-center gap-3">
                Playlist Fokus Musik 🎵
            </h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                Kelola tautan musik atau hubungkan akun Spotify kamu agar pengalaman bekerja di MyStudy makin fokus!
            </p>
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center gap-3 text-sm font-semibold">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 rounded-2xl flex items-center gap-3 text-sm font-semibold">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">

            {{-- KARTU 1: INTEGRASI SPOTIFY RESMI --}}
            <div class="bg-white dark:bg-[#001818] rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-[#002525] shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            Ayo hubungkan akun spotify kamu disini
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Otentikasi langsung menggunakan akun Spotify milikmu untuk fitur pemutar musik resmi.
                        </p>
                    </div>

                    <div>
                        @if(session('spotify_access_token'))
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-full text-xs font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Terhubung dengan Spotify
                            </div>
                        @else
                            <a href="{{ route('spotify.login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#1DB954] hover:bg-[#1ed760] text-black font-extrabold rounded-2xl transition-all shadow-lg hover:scale-105 text-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.899 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141 C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.18-.1.2-1.2-.42-.18-.6.42-1.2 1.02-1.38 4.26-1.26 11.28-1.02 15.72 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                                Hubungkan Spotify
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KARTU 2: ATUR TAUTAN PLAYLIST KUSTOM --}}
            <div class="bg-white dark:bg-[#001818] rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-[#002525] shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Tautan Playlist Kustom
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Tempelkan URL playlist dari Spotify atau YouTube Musik yang ingin kamu dengarkan di pemutar floating player.
                </p>

                <form action="{{ route('playlist.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="playlist_url" class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                            URL Playlist Spotify / YouTube
                        </label>
                        <input type="url" 
                               name="playlist_url" 
                               id="playlist_url" 
                               value="{{ old('playlist_url', Auth::user()->playlist_url) }}" 
                               placeholder="https://open.spotify.com/playlist/... atau https://youtube.com/..." 
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-[#000F0F] border border-gray-200 dark:border-[#002525] rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#68C7EC] focus:border-[#68C7EC] transition-all text-sm">
                        @error('playlist_url')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-6 py-2.5 bg-[#68C7EC] hover:opacity-90 text-[#000F0F] font-bold rounded-xl text-sm transition-all shadow-md">
                            Simpan Playlist
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    @include('components.floating-player')
</body>
</html>