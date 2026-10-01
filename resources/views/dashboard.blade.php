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
    <style>
        @keyframes modalIn {
            0% {
                opacity: 0;
                transform: translate(-50%, calc(-50% + 24px)) scale(0.94);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes modalOut {
            0% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            100% {
                opacity: 0;
                transform: translate(-50%, calc(-50% + 18px)) scale(0.96);
            }
        }

        @keyframes backdropIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes backdropOut {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }

        .delete-modal-backdrop {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .delete-modal-backdrop.show {
            opacity: 1;
            pointer-events: auto;
            animation: backdropIn 0.2s ease;
        }

        .delete-modal-backdrop.hide {
            animation: backdropOut 0.18s ease forwards;
        }

        .delete-confirm-card {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.96);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .delete-confirm-card.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
            pointer-events: auto;
            animation: modalIn 0.22s ease;
        }

        .delete-confirm-card.hide {
            animation: modalOut 0.2s ease forwards;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-[#000F0F] min-h-screen overflow-x-hidden transition-colors duration-300" x-data="{ userDropdown: false }">

    @php
        $reminderTasks = $tasks
            ->filter(fn ($task) => $task->reminder_at && !$task->is_completed)
            ->sortBy('reminder_at');
    @endphp

    {{-- NAVBAR ATAS --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#000F0F]/80 backdrop-blur-md border-b border-gray-200 dark:border-[#002525] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 justify-between h-16 items-center">
                {{-- Logo --}}
                <div class="min-w-0 flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-lg sm:text-2xl text-[#68C7EC] flex items-center hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            <path d="M9 14l2 2 4-4"></path>
                        </svg>
                        MyStudy
                    </a>
                </div>

                {{-- Bagian Kanan (Theme Toggle & Profil) --}}
                <div class="shrink-0 flex items-center space-x-1.5 sm:space-x-4">
                    <button id="theme-toggle" class="shrink-0 p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#001818] rounded-full transition-colors" aria-label="Ganti tema">
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                    </button>

                    <button id="reminder-toggle" type="button" class="relative shrink-0 p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#001818] rounded-full transition-colors" aria-label="Buka pengingat tugas" aria-expanded="false">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9a6 6 0 0 0-12 0v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.55 1.083 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        <span id="reminder-count" class="absolute -right-0.5 -top-0.5 hidden min-w-4 h-4 rounded-full bg-red-500 px-1 text-[10px] leading-4 text-white">0</span>
                    </button>

                    <div class="relative flex items-center gap-2">
                        <a href="{{ route('profile.edit') }}" title="Pengaturan profil" aria-label="Buka pengaturan profil" class="shrink-0 rounded-full ring-2 ring-[#68C7EC]/40 ring-offset-2 ring-offset-white dark:ring-offset-[#000F0F] hover:ring-[#68C7EC] transition-all">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto profil {{ Auth::user()->name }}" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=68C7EC&color=000F0F&bold=true" alt="Foto profil {{ Auth::user()->name }}" class="h-9 w-9 rounded-full object-cover">
                            @endif
                        </a>
                        <button @click="userDropdown = !userDropdown" @click.away="userDropdown = false" class="flex max-w-[8rem] sm:max-w-[11rem] items-center space-x-2 focus:outline-none bg-gray-100 dark:bg-[#001818] py-1.5 px-2 sm:px-3 rounded-full hover:bg-gray-200 dark:hover:bg-[#002525] transition-colors border border-transparent dark:border-[#002525]">
                            <span class="hidden sm:inline truncate text-sm font-semibold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="userDropdown" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#001818] rounded-xl shadow-lg border border-gray-100 dark:border-[#002525] py-1 z-50">
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

    <div id="reminder-panel" class="fixed right-4 top-20 z-40 hidden w-[min(92vw,360px)] rounded-2xl border border-gray-200 bg-white p-4 shadow-xl dark:border-[#002525] dark:bg-[#001818]">
        <div class="flex items-center justify-between gap-3 border-b border-gray-100 pb-3 dark:border-[#002525]">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Pengingat Tugas</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tugas yang perlu kamu perhatikan</p>
            </div>
            <button id="enable-notifications" type="button" class="rounded-lg bg-[#68C7EC]/15 px-2.5 py-1.5 text-xs font-semibold text-[#1684a8] transition-colors hover:bg-[#68C7EC]/25 dark:text-[#68C7EC]">Aktifkan notifikasi</button>
        </div>
        <div class="mt-3 max-h-72 space-y-2 overflow-y-auto">
            @forelse($reminderTasks as $task)
                <div class="rounded-xl bg-gray-50 p-3 dark:bg-[#000F0F]" data-reminder-item data-reminder-id="{{ $task->id }}" data-reminder-at="{{ $task->reminder_at->toIso8601String() }}">
                    <p class="break-words text-sm font-semibold text-gray-900 dark:text-white">{{ $task->title }}</p>
                    <p class="mt-1 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" /></svg>
                        {{ $task->reminder_at->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
            @empty
                <p class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengingat aktif.</p>
            @endforelse
        </div>
    </div>

    <div id="reminder-toast-container" class="pointer-events-none fixed bottom-4 right-4 z-[60] flex w-[min(92vw,360px)] flex-col gap-2" aria-live="polite" aria-relevant="additions text"></div>

    <div id="delete-modal-backdrop" class="delete-modal-backdrop fixed inset-0 z-40 bg-[#000F0F]/60 backdrop-blur-sm" style="display: none;"></div>

    <div id="delete-confirm-toast" class="delete-confirm-card z-50 w-[min(92vw,420px)] rounded-3xl border border-red-200/80 dark:border-red-500/30 bg-white/95 dark:bg-[#001818]/95 shadow-[0_25px_80px_rgba(239,68,68,0.18)] p-5 ring-1 ring-black/5 dark:ring-white/5" style="display: none;">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-red-500 dark:text-red-400 shadow-inner shadow-red-200/50 dark:shadow-red-500/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M5.43 19h13.14a2 2 0 001.94-2.54L14.4 4.7a2 2 0 00-3.8 0L3.49 16.46A2 2 0 005.43 19z"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-gray-900 dark:text-white">Hapus tugas?</p>
                <p id="delete-confirm-text" class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus tugas ini?</p>
                <div class="mt-5 flex justify-end gap-2.5">
                    <button id="delete-cancel-btn" type="button" class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        Batal
                    </button>
                    <button id="delete-confirm-btn" type="button" class="px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-all duration-200 shadow-lg shadow-red-500/20 hover:shadow-red-500/30">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- AREA UTAMA --}}
    <main class="w-full min-w-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        {{-- Kalkulasi Progres Dinamis --}}
        @php
            $totalTasks = Auth::user()->tasks()->count();
            $completedTasks = Auth::user()->tasks()->where('is_completed', true)->count();
            $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        @endphp

        {{-- Header & Progress --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white flex flex-wrap items-center gap-x-1">
                    Fokus Eksekusi, <span class="text-[#68C7EC]">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                </h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>

            {{-- Widget Progres Tugas Dinamis --}}
            <div class="w-full sm:w-auto bg-white dark:bg-[#001818] p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-[#002525] flex items-center gap-4 sm:min-w-[250px]">
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
        <div class="grid min-w-0 grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
            
            {{-- KOLOM KIRI (Daftar Tugas) - Span 2 Kolom --}}
            <div class="min-w-0 lg:col-span-2 flex flex-col gap-6">
                
                {{-- Form Input Cepat --}}
                <form action="{{ route('tasks.store') }}" method="POST" class="bg-white dark:bg-[#001818] p-1 rounded-2xl shadow-sm border border-gray-200 dark:border-[#002525] flex flex-col sm:flex-row focus-within:ring-2 focus-within:ring-[#68C7EC] focus-within:border-[#68C7EC] transition-all">
                    @csrf
                    <div class="hidden sm:flex pl-5 py-4 items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col sm:flex-row sm:items-center">
                        <input type="text" name="title" placeholder="Tambah tugas baru untuk hari ini..." required class="w-full min-w-0 bg-transparent border-0 text-base sm:text-lg font-medium text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 px-4 py-3 sm:py-4">
                        <label class="flex w-full shrink-0 flex-col gap-1 px-4 pb-3 text-xs text-gray-500 dark:text-gray-400 sm:w-auto sm:px-2 sm:py-0" title="Atur tanggal dan jam pengingat">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-[#68C7EC]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" /></svg>
                                Tanggal dan jam pengingat
                            </span>
                            <input id="task-reminder-at" type="datetime-local" name="reminder_at" class="w-full min-w-0 rounded-lg border-0 bg-transparent p-1 text-sm text-gray-700 focus:ring-0 dark:text-gray-200 sm:w-56" aria-label="Tanggal dan jam pengingat">
                            <input id="task-reminder-timezone" type="hidden" name="reminder_timezone" value="Asia/Jakarta">
                        </label>
                    </div>
                    <button type="submit" class="m-2 mt-0 sm:mt-2 px-6 py-3 sm:py-2 bg-[#68C7EC] hover:opacity-90 text-[#000F0F] rounded-xl font-bold transition-all">
                        Tambah
                    </button>
                </form>

                {{-- Daftar Tugas Card --}}
                <div class="min-w-0 bg-white dark:bg-[#001818] rounded-3xl shadow-sm border border-gray-100 dark:border-[#002525] p-4 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Eksekusi</h2>
                        
                        {{-- Tombol Filter Tugas --}}
                        <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start space-x-1 bg-gray-100 dark:bg-[#000F0F] p-1 rounded-xl text-xs font-semibold border border-transparent dark:border-[#002525]">
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
                            <div class="task-item group min-w-0 flex items-center justify-between gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 dark:bg-[#000F0F] rounded-2xl border border-gray-100 dark:border-[#002525] hover:border-[#68C7EC]/50 dark:hover:border-[#68C7EC]/50 transition-colors">
                                <div class="flex min-w-0 items-center space-x-3 sm:space-x-4 flex-1">
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
                                        <p class="task-title break-words text-sm sm:text-base font-semibold {{ $task->is_completed ? 'text-gray-400 dark:text-gray-600 line-through' : 'text-gray-900 dark:text-white' }}">
                                            {{ $task->title }}
                                        </p>
                                        @if($task->reminder_at && !$task->is_completed)
                                            <p class="mt-1 flex items-center gap-1 text-xs {{ $task->reminder_at->isPast() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" /></svg>
                                                {{ $task->reminder_at->translatedFormat('d M Y, H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Tombol Aksi (AI & Hapus) --}}
                                <div class="flex shrink-0 items-center space-x-1 sm:space-x-2 sm:ml-4">
                                    {{-- Tombol Tanya AI --}}
                                    <a href="{{ route('ai.chat') }}" class="text-[#68C7EC]/70 hover:text-[#68C7EC] transition-colors p-1.5 rounded-lg hover:bg-[#68C7EC]/10" title="Bantu kerjakan tugas ini dengan AI">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form class="delete-task-form" data-task-title="{{ $task->title }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-task-button text-gray-400 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-500/10">
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
                    
                    <div class="relative z-10 flex justify-between items-start gap-3 mb-6">
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
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <h3 class="font-bold text-gray-900 dark:text-white flex items-center text-sm min-w-0">
                            <svg class="w-5 h-5 mr-2 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h2v7H3v-7zm4-6h2v13H7V7zm4-4h2v17h-2V3zm4 9h2v8h-2v-8zm4-5h2v13h-2V7z"></path>
                            </svg>
                            Aktivitas 7 Hari Terakhir
                        </h3>

                        {{-- Badge Streak --}}
                        <div class="shrink-0 flex items-center space-x-1.5 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full text-amber-600 text-xs font-bold" title="Streak harian berturut-turut">
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

            if (themeToggleBtn && themeToggleDarkIcon && themeToggleLightIcon) {
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
            }

            const deleteToast = document.getElementById('delete-confirm-toast');
            const deleteBackdrop = document.getElementById('delete-modal-backdrop');
            const deleteToastText = document.getElementById('delete-confirm-text');
            const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
            const deleteCancelBtn = document.getElementById('delete-cancel-btn');
            let pendingDeleteForm = null;

            if (deleteToast && deleteBackdrop && deleteToastText && deleteConfirmBtn && deleteCancelBtn) {
                function showDeleteToast(taskTitle) {
                    deleteToastText.textContent = `Apakah Anda yakin ingin menghapus tugas "${taskTitle}"?`;
                    deleteBackdrop.style.display = 'block';
                    deleteToast.style.display = 'block';
                    deleteBackdrop.classList.remove('hide');
                    deleteBackdrop.classList.add('show');
                    deleteToast.classList.remove('hide');
                    deleteToast.classList.add('show');
                }

                function hideDeleteToast() {
                    deleteBackdrop.classList.remove('show');
                    deleteBackdrop.classList.add('hide');
                    deleteToast.classList.remove('show');
                    deleteToast.classList.add('hide');

                    setTimeout(() => {
                        deleteBackdrop.style.display = 'none';
                        deleteToast.style.display = 'none';
                        deleteBackdrop.classList.remove('hide');
                        deleteToast.classList.remove('hide');
                    }, 200);
                }

                deleteCancelBtn.addEventListener('click', function() {
                    pendingDeleteForm = null;
                    hideDeleteToast();
                });

                deleteBackdrop.addEventListener('click', function() {
                    pendingDeleteForm = null;
                    hideDeleteToast();
                });

                deleteConfirmBtn.addEventListener('click', function() {
                    if (pendingDeleteForm) {
                        pendingDeleteForm.submit();
                    }
                });
            }

            const deleteForms = document.querySelectorAll('.delete-task-form');
            deleteForms.forEach(function(form) {
                const deleteButton = form.querySelector('.delete-task-button');

                if (deleteButton) {
                    deleteButton.addEventListener('click', function(event) {
                        event.preventDefault();
                        const taskTitle = form.dataset.taskTitle || 'tugas ini';
                        pendingDeleteForm = form;
                        if (deleteToast && deleteBackdrop && deleteToastText && deleteConfirmBtn && deleteCancelBtn) {
                            showDeleteToast(taskTitle);
                        } else {
                            form.submit();
                        }
                    });
                }
            });

            const reminderToggle = document.getElementById('reminder-toggle');
            const reminderPanel = document.getElementById('reminder-panel');
            const reminderCount = document.getElementById('reminder-count');
            const enableNotifications = document.getElementById('enable-notifications');
            const reminderToastContainer = document.getElementById('reminder-toast-container');
            const reminderItems = Array.from(document.querySelectorAll('[data-reminder-item]'));
            const notificationStorageKey = 'smartdo-notified-reminders';

            function showReminderToast(title) {
                if (!reminderToastContainer) return;

                while (reminderToastContainer.children.length >= 3) {
                    reminderToastContainer.firstElementChild.remove();
                }

                const toast = document.createElement('div');
                toast.className = 'pointer-events-auto rounded-xl border border-[#68C7EC]/40 bg-white p-4 shadow-xl dark:border-[#145050] dark:bg-[#001818]';
                toast.setAttribute('role', 'status');

                const heading = document.createElement('p');
                heading.className = 'text-sm font-bold text-gray-900 dark:text-white';
                heading.textContent = 'Pengingat tugas';

                const message = document.createElement('p');
                message.className = 'mt-1 break-words text-sm text-gray-600 dark:text-gray-300';
                message.textContent = `Waktunya mengerjakan: ${title}`;

                toast.append(heading, message);
                reminderToastContainer.append(toast);
                window.setTimeout(() => toast.remove(), 10000);
            }

            function getNotifiedReminders() {
                try {
                    const savedReminders = JSON.parse(localStorage.getItem(notificationStorageKey) || '{}');
                    return savedReminders && typeof savedReminders === 'object' && !Array.isArray(savedReminders)
                        ? savedReminders
                        : {};
                } catch (error) {
                    return {};
                }
            }

            function updateReminderCount() {
                const now = Date.now();
                const dueCount = reminderItems.filter((item) => new Date(item.dataset.reminderAt).getTime() <= now).length;

                reminderCount.textContent = dueCount > 9 ? '9+' : dueCount;
                reminderCount.classList.toggle('hidden', dueCount === 0);
            }

            function notifyDueReminders() {
                const notifiedReminders = getNotifiedReminders();
                const now = Date.now();

                reminderItems.forEach((item) => {
                    const reminderId = item.dataset.reminderId;
                    const reminderAt = new Date(item.dataset.reminderAt).getTime();
                    const title = item.querySelector('p')?.textContent.trim() || 'Tugas';
                    const savedState = notifiedReminders[reminderId];
                    const reminderState = savedState && typeof savedState === 'object'
                        ? savedState
                        : { website: Boolean(savedState), system: Boolean(savedState) };

                    if (reminderAt > now) return;

                    if (!reminderState.website) {
                        showReminderToast(title);
                        reminderState.website = true;
                    }

                    if (!reminderState.system && 'Notification' in window && Notification.permission === 'granted') {
                        try {
                            const notification = new Notification('Pengingat tugas SmartDo', {
                                body: `Waktunya mengerjakan: ${title}`,
                                tag: `task-reminder-${reminderId}`,
                            });
                            notification.onclick = function() {
                                window.focus();
                                notification.close();
                            };
                            reminderState.system = true;
                        } catch (error) {
                            console.error('Notifikasi sistem gagal ditampilkan:', error);
                        }
                    }

                    notifiedReminders[reminderId] = reminderState;
                });

                try {
                    localStorage.setItem(notificationStorageKey, JSON.stringify(notifiedReminders));
                } catch (error) {
                    console.error('Status pengingat gagal disimpan:', error);
                }
            }

            if (reminderToggle && reminderPanel) {
                reminderToggle.addEventListener('click', function() {
                    const isHidden = reminderPanel.classList.toggle('hidden');
                    reminderToggle.setAttribute('aria-expanded', String(!isHidden));
                });

                document.addEventListener('click', function(event) {
                    if (!reminderPanel.contains(event.target) && !reminderToggle.contains(event.target)) {
                        reminderPanel.classList.add('hidden');
                        reminderToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            if (enableNotifications) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    enableNotifications.textContent = 'Notifikasi aktif';
                }

                enableNotifications.addEventListener('click', async function() {
                    if (!('Notification' in window)) {
                        enableNotifications.textContent = 'Browser tidak mendukung';
                        return;
                    }

                    const permission = await Notification.requestPermission();
                    enableNotifications.textContent = permission === 'granted' ? 'Notifikasi aktif' : 'Izin ditolak';
                    notifyDueReminders();
                });
            }

            updateReminderCount();
            notifyDueReminders();
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    updateReminderCount();
                    notifyDueReminders();
                }
            });
            setInterval(function() {
                updateReminderCount();
                notifyDueReminders();
            }, 30000);

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