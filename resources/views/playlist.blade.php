<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Playlist Fokus - SmartDo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-[#000F0F] text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-300">

    {{-- NAVBAR ATAS --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#000F0F]/80 backdrop-blur-md border-b border-gray-200 dark:border-[#002525]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-[#68C7EC] flex items-center">
                SmartDo
            </a>
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-[#68C7EC] transition-colors">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </nav>

    {{-- KONTEN UTAMA PLAYLIST --}}
    <main class="max-w-4xl mx-auto px-4 py-10">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Zona Musik & Fokus 🎧</h1>
            <p class="text-gray-500 dark:text-gray-400">Putar lagu favoritmu dari Spotify atau YouTube agar sesi produktivitasmu makin maksimal.</p>
        </div>

        {{-- Form Input Link Playlist --}}
        <div class="bg-white dark:bg-[#001818] p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-[#002525] mb-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tambahkan Link Playlist-mu Kesini Yuk</h2>
            <form action="{{ route('playlist.save') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="url" name="playlist_url" value="{{ Auth::user()->playlist_url }}" placeholder="Tempel link Spotify atau YouTube di sini..." required class="flex-1 bg-gray-50 dark:bg-[#000F0F] border border-gray-200 dark:border-[#002525] rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#68C7EC] text-gray-900 dark:text-white outline-none">
                <button type="submit" class="px-6 py-3 bg-[#68C7EC] hover:opacity-90 text-[#000F0F] rounded-xl font-bold text-sm transition-colors shadow-md">
                    Simpan Playlist
                </button>
            </form>
            @if(session('success'))
                <p class="text-xs text-green-600 dark:text-green-400 mt-2 font-medium">{{ session('success') }}</p>
            @endif
        </div>

        {{-- Area Pemutar Musik Dinamis (Embed Player) --}}
        <div class="bg-gradient-to-br from-[#002525] to-[#000F0F] border border-[#68C7EC]/20 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-[#68C7EC] opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <span class="bg-[#68C7EC]/20 text-[#68C7EC] border border-[#68C7EC]/30 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">Pemutar Aktif</span>
                <h3 class="text-2xl font-extrabold mt-3 mb-6">Sesi Musik Fokusmu</h3>

                @php
                    $url = Auth::user()->playlist_url;
                    $embedUrl = null;

                    if ($url) {
                        if (str_contains($url, 'spotify.com')) {
                            $cleanUrl = explode('?', $url)[0];
                            $cleanUrl = preg_replace('/\/intl-[a-z]{2}\//', '/', $cleanUrl);
                            $embedUrl = str_replace('open.spotify.com/', 'open.spotify.com/embed/', $cleanUrl);
                        } 
                        elseif (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                            if (str_contains($url, 'watch?v=')) {
                                parse_str(parse_url($url, PHP_URL_QUERY), $ytParams);
                                if (isset($ytParams['v'])) {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $ytParams['v'];
                                }
                            } elseif (str_contains($url, 'youtu.be/')) {
                                $path = parse_url($url, PHP_URL_PATH);
                                $embedUrl = 'https://www.youtube.com/embed' . $path;
                            }
                        }
                    }
                @endphp

                @if($embedUrl)
                    <div class="w-full rounded-2xl overflow-hidden shadow-lg bg-black/40 border border-[#68C7EC]/10">
                        @if(str_contains($embedUrl, 'spotify.com'))
                            <iframe src="{{ $embedUrl }}?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        @elseif(str_contains($embedUrl, 'youtube.com'))
                            <iframe width="100%" height="250" src="{{ $embedUrl }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @endif
                    </div>
                @else
                    <div class="text-center py-10 bg-[#001818]/60 rounded-2xl border border-[#68C7EC]/10">
                        <p class="text-sm text-gray-300">Belum ada link playlist yang disimpan. Masukkan tautan Spotify atau YouTube di atas agar musiknya bisa diputar!</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

</body>
</html>