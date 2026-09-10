<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - MyStudy</title>
    
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

    {{-- NAVBAR ATAS --}}
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

                {{-- Bagian Kanan (Theme Toggle & Profil) --}}
                <div class="flex items-center space-x-4">
                    <button id="theme-toggle" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#001818] rounded-full transition-colors">
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                    </button>

                    <div class="relative">
                        <button @click="userDropdown = !userDropdown" @click.away="userDropdown = false" class="flex items-center space-x-2 focus:outline-none bg-gray-100 dark:bg-[#001818] py-1.5 px-3 rounded-full hover:bg-gray-200 dark:hover:bg-[#002525] transition-colors border border-transparent dark:border-[#002525]">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="userDropdown" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#001818] rounded-xl shadow-lg border border-gray-100 dark:border-[#002525] py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#002525] transition-colors">Pengaturan Profil</a>
                            
                            {{-- Menu Tambahan: Chat AI --}}
                            <a href="{{ route('ai.chat') }}" class="flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium">
                                <span>Chat AI</span>
                            </a>

                            {{-- Menu Tambahan: Playlist Musik --}}
                            <a href="{{ route('playlist') }}" class="flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium">
                                <span>Playlist Musik</span>
                            </a>

                            <hr class="my-1 border-gray-100 dark:border-[#002525]">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- AREA UTAMA --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        {{-- Kalkulasi Progres Dinamis --}}
        @php
            $totalTasks = Auth::user()->tasks()->count();
            $completedTasks = Auth::user()->tasks()->where('is_completed', true)->count();
            $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        @endphp

        {{-- Header & Progress --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white flex items-center">
                    Fokus Eksekusi, <span class="text-[#68C7EC] mx-2">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                    <svg class="w-8 h-8 text-amber-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                </h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>

            {{-- Widget Progres Tugas Dinamis --}}
            <div class="bg-white dark:bg-[#001818] p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-[#002525] flex items-center gap-4 min-w-[250px]">
                <div class="w-12 h-12 rounded-full border-4 border-[#68C7EC]/20 dark:border-[#68C7EC]/10 flex items-center justify-center relative">
                    <svg class="absolute inset-0 w-full h-full text-[#68C7EC] transform -rotate-90" viewBox="0 0 36 36">
                        <path stroke-dasharray="{{ $percentage }}, 100" class="stroke-current" fill="none" stroke-width="4" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $percentage }}%</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">Progres Keseluruhan</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai</p>
                </div>
            </div>
        </div>

        {{-- BENTO GRID LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
            
            {{-- KOLOM KIRI (Daftar Tugas) - Span 2 Kolom --}}
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                {{-- Form Input Cepat --}}
                <form action="{{ route('tasks.store') }}" method="POST" class="bg-white dark:bg-[#001818] p-1 rounded-2xl shadow-sm border border-gray-200 dark:border-[#002525] flex focus-within:ring-2 focus-within:ring-[#68C7EC] focus-within:border-[#68C7EC] transition-all">
                    @csrf
                    <div class="pl-5 py-4 flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <input type="text" name="title" placeholder="Tambah tugas baru untuk hari ini..." required class="w-full bg-transparent border-0 text-lg font-medium text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 px-4 py-4">
                    <button type="submit" class="m-2 px-6 py-2 bg-[#68C7EC] hover:opacity-90 text-[#000F0F] rounded-xl font-bold transition-all">
                        Tambah
                    </button>
                </form>

                {{-- Daftar Tugas Card --}}
                <div class="bg-white dark:bg-[#001818] rounded-3xl shadow-sm border border-gray-100 dark:border-[#002525] p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Eksekusi</h2>
                        
                        {{-- Tombol Filter Tugas --}}
                        <div class="flex items-center space-x-1 bg-gray-100 dark:bg-[#000F0F] p-1 rounded-xl text-xs font-semibold border border-transparent dark:border-[#002525]">
                            <a href="{{ route('dashboard', ['filter' => 'all']) }}" 
                               class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'all' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                                Semua
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'active']) }}" 
                               class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'active' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                                Aktif
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'completed']) }}" 
                               class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'completed' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                                Selesai
                            </a>
                        </div>
                    </div>

                    {{-- Wrapper List --}}
                    <div class="space-y-4">
                        @forelse($tasks as $task)
                            <div class="group flex items-center justify-between p-4 bg-gray-50 dark:bg-[#000F0F] rounded-2xl border border-gray-100 dark:border-[#002525] hover:border-[#68C7EC]/50 dark:hover:border-[#68C7EC]/50 transition-colors">
                                <div class="flex items-center space-x-4 flex-1">
                                    {{-- Form Update Status Selesai/Belum --}}
                                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-5 h-5 rounded-md border flex items-center justify-center transition-colors {{ $task->is_completed ? 'bg-[#68C7EC] border-[#68C7EC] text-[#000F0F]' : 'bg-white dark:bg-[#001818] border-gray-300 dark:border-gray-700' }}">
                                            @if($task->is_completed)
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <div class="flex-1">
                                        <p class="text-base font-semibold {{ $task->is_completed ? 'text-gray-400 dark:text-gray-600 line-through' : 'text-gray-900 dark:text-white' }}">
                                            {{ $task->title }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Tombol Aksi (AI & Hapus) --}}
                                <div class="flex items-center space-x-2 ml-4">
                                    {{-- Tombol Tanya AI --}}
                                    <a href="{{ route('ai.chat') }}" class="text-[#68C7EC]/70 hover:text-[#68C7EC] transition-colors p-1.5 rounded-lg hover:bg-[#68C7EC]/10" title="Bantu kerjakan tugas ini dengan AI">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-500/10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 dark:text-gray-600">
                                <p class="text-sm font-medium">Tidak ada tugas pada kategori ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Widgets) - Span 1 Kolom --}}
            <div class="flex flex-col gap-6">
                
                {{-- Widget Musik Lo-Fi --}}
                <div class="bg-gradient-to-br from-[#002525] to-[#000F0F] border border-[#68C7EC]/20 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#68C7EC] opacity-10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                    
                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div>
                            <p class="text-[#68C7EC] text-xs font-bold uppercase tracking-widest mb-1">Playlist Fokus</p>
                            <p class="font-extrabold text-xl">
                                @if(Auth::user()->playlist_url)
                                    Playlist Kustom 
                                @else
                                    Atur Playlist-mu
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('playlist') }}" class="w-10 h-10 bg-[#68C7EC]/20 backdrop-blur-md rounded-full flex items-center justify-center hover:scale-110 transition-transform shadow-md border border-[#68C7EC]/30" title="Kelola Playlist">
                            <svg class="w-5 h-5 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        </a>
                    </div>

                    <div class="relative z-10">
                        <p class="text-xs text-gray-400 mb-4">
                            @if(Auth::user()->playlist_url)
                                Tautan tersimpan. Gunakan Floating Player di pojok kanan bawah!
                            @else
                                Belum ada tautan musik. Klik ikon musik untuk menambahkan.
                            @endif
                        </p>

                        <a href="{{ route('playlist') }}" class="block w-full py-2.5 bg-[#68C7EC] text-[#000F0F] rounded-xl font-bold text-center text-sm shadow-md hover:opacity-90 transition-all">
                            Kelola Pemutar 
                        </a>
                    </div>
                </div>

                {{-- Widget Aktivitas 7 Hari Terakhir & Streak --}}
                <div class="bg-white dark:bg-[#001818] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-[#002525]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 dark:text-white flex items-center text-sm">
                            <svg class="w-5 h-5 mr-2 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h2v7H3v-7zm4-6h2v13H7V7zm4-4h2v17h-2V3zm4 9h2v8h-2v-8zm4-5h2v13h-2V7z"></path>
                            </svg>
                            Aktivitas 7 Hari Terakhir
                        </h3>

                        {{-- Badge Streak --}}
                        <div class="flex items-center space-x-1.5 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full text-amber-600 text-xs font-bold" title="Streak harian berturut-turut">
                            <svg class="w-4 h-4 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $streak ?? 0 }} Hari Streak</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-2">
                        @foreach($activityDays as $day)
                            <div class="flex flex-col items-center">
                                <div class="w-full h-10 rounded-lg flex items-center justify-center text-xs font-bold transition-all {{ $day['active'] ? 'bg-[#68C7EC] text-[#000F0F] shadow-md shadow-[#68C7EC]/30 scale-105' : 'bg-gray-100 dark:bg-[#000F0F] text-gray-400 dark:text-gray-600 border border-transparent dark:border-[#002525]' }}" title="{{ $day['completed_count'] }} tugas selesai pada {{ $day['date'] }}">
                                    {{ $day['completed_count'] > 0 ? $day['completed_count'] : $day['day'][0] }}
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1.5 font-medium">{{ $day['day'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            // AJAX Script
            const taskForm = document.querySelector('form[action="{{ route("tasks.store") }}"]');
            if (taskForm) {
                taskForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let formData = new FormData(taskForm);

                    fetch(taskForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (response.ok) window.location.reload();
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
        });
    </script>

    @include('components.floating-player')
</body>
</html>