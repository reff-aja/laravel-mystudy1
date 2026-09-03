@php
    $url = Auth::user() ? Auth::user()->playlist_url : null;
    $embedUrl = null;

    if ($url) {
        if (str_contains($url, 'spotify.com')) {
            $cleanUrl = explode('?', $url)[0];
            $cleanUrl = preg_replace('/\/intl-[a-z]{2}\//', '/', $cleanUrl);
            $embedUrl = str_replace('open.spotify.com/', 'open.spotify.com/embed/', $cleanUrl);
        } elseif (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
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
    <div x-data="{ minimized: false }" class="fixed bottom-4 right-4 z-50 bg-gray-900/90 dark:bg-gray-900/90 backdrop-blur-md border border-gray-700/50 rounded-2xl shadow-2xl text-white overflow-hidden transition-all duration-300" :class="minimized ? 'w-16 h-16 rounded-full flex items-center justify-center cursor-pointer' : 'w-80 sm:w-96'">
        
        {{-- Tombol Minimize / Expand --}}
        <div class="absolute top-2 right-2 z-20 flex items-center space-x-1">
            <button @click="minimized = !minimized" class="p-1.5 bg-black/40 hover:bg-black/60 rounded-full text-gray-300 hover:text-white transition-colors" title="Sembunyikan/Tampilkan Player">
                <svg x-show="!minimized" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                <svg x-show="minimized" style="display: none;" class="w-5 h-5 text-indigo-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
            </button>
        </div>

        {{-- Tampilan Normal (Expanded) --}}
        <div x-show="!minimized" class="p-4">
            <div class="flex items-center space-x-2 mb-2">
                <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-ping"></div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Floating Focus Player 🎧</span>
            </div>

            <div class="rounded-xl overflow-hidden bg-black/40 shadow-inner">
                @if(str_contains($embedUrl, 'spotify.com'))
                    <iframe src="{{ $embedUrl }}?utm_source=generator&theme=0" width="100%" height="80" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                @elseif(str_contains($embedUrl, 'youtube.com'))
                    <iframe width="100%" height="80" src="{{ $embedUrl }}?enablejsapi=1" title="YouTube player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @endif
            </div>
        </div>

        {{-- Tampilan Minim (Lingkaran Musik di Pojok) --}}
        <div x-show="minimized" @click="minimized = false" style="display: none;" class="w-full h-full flex items-center justify-center cursor-pointer" title="Buka Pemutar Musik">
            <svg class="w-7 h-7 text-indigo-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
        </div>

    </div>
@endif