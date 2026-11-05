<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
        /* Animasi Ambient Glow Background */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Animasi Shimmer Kartu Tugas */
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        
        /* Animasi Modal Umum */
        @keyframes modalIn {
            0% { opacity: 0; transform: translate(-50%, calc(-50% + 24px)) scale(0.94); }
            100% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }
        @keyframes modalOut {
            0% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            100% { opacity: 0; transform: translate(-50%, calc(-50% + 18px)) scale(0.96); }
        }
        @keyframes backdropIn { 0% { opacity: 0; } 100% { opacity: 1; } }
        @keyframes backdropOut { 0% { opacity: 1; } 100% { opacity: 0; } }

        .delete-modal-backdrop { opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
        .delete-modal-backdrop.show { opacity: 1; pointer-events: auto; animation: backdropIn 0.2s ease; }
        .delete-modal-backdrop.hide { animation: backdropOut 0.18s ease forwards; }

        .delete-confirm-card { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.96); opacity: 0; pointer-events: none; transition: opacity 0.2s ease, transform 0.2s ease; }
        .delete-confirm-card.show { opacity: 1; transform: translate(-50%, -50%) scale(1); pointer-events: auto; animation: modalIn 0.22s ease; }
        .delete-confirm-card.hide { animation: modalOut 0.2s ease forwards; }

        input[type="datetime-local"]::-webkit-calendar-picker-indicator { cursor: pointer; filter: invert(63%) sepia(64%) saturate(411%) hue-rotate(160deg) brightness(92%) contrast(92%); }
    </style>
</head>

<body
    class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-[#000F0F] min-h-screen overflow-x-hidden transition-colors duration-300 relative"
    x-data="{ 
        userDropdown: false, 
        showCalculator: false,
        layoutMode: localStorage.getItem('mystudy-layout') || 'bento'
    }"
    x-init="$watch('layoutMode', val => localStorage.setItem('mystudy-layout', val))">

    {{-- AMBIENT BACKGROUND ANIMATION --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none" x-show="layoutMode === 'bento'">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#68C7EC]/20 dark:bg-[#68C7EC]/5 rounded-full mix-blend-multiply dark:mix-blend-screen blur-3xl opacity-70 animate-[blob_7s_infinite]"></div>
        <div class="absolute top-[20%] right-[-10%] w-[400px] h-[400px] bg-blue-300/20 dark:bg-blue-900/10 rounded-full mix-blend-multiply dark:mix-blend-screen blur-3xl opacity-70 animate-[blob_7s_infinite] animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-[600px] h-[600px] bg-cyan-200/20 dark:bg-cyan-900/10 rounded-full mix-blend-multiply dark:mix-blend-screen blur-3xl opacity-70 animate-[blob_7s_infinite] animation-delay-4000"></div>
    </div>

    @php
        $reminderTasks = $tasks->filter(fn($task) => $task->reminder_at && !$task->is_completed)->sortBy('reminder_at');
        $totalTasks = Auth::user()->tasks()->count();
        $completedTasks = Auth::user()->tasks()->where('is_completed', true)->count();
        $activeTasks = $totalTasks - $completedTasks;
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    {{-- ========================================== --}}
    {{-- LAYOUT 1: MODE ESTETIK (BENTO GRID)        --}}
    {{-- ========================================== --}}
    <div x-show="layoutMode === 'bento'" x-cloak>
        
        {{-- NAVBAR BENTO --}}
        <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#000F0F]/80 backdrop-blur-md border-b border-gray-200 dark:border-[#002525] transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex min-w-0 justify-between h-16 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-[#68C7EC] flex items-center gap-2 hover:scale-105 transition-transform">
                            <img src="{{ asset('images/logo.png') }}" alt="MyStudy Logo" class="w-12 h-12 object-contain">
                            <span>My<span class="text-gray-900 dark:text-white">Study</span></span>
                        </a>
                    </div>

                    <div class="shrink-0 flex items-center space-x-1.5 sm:space-x-4 relative z-50">
                        {{-- Tombol Switch UI Mode (Pro) --}}
                        <button @click="layoutMode = 'pro'" 
                            class="shrink-0 p-2 text-gray-400 hover:text-[#68C7EC] dark:text-gray-500 dark:hover:text-[#68C7EC] hover:bg-gray-100 dark:hover:bg-[#001818] rounded-full transition-colors flex items-center gap-1.5"
                            title="Ganti ke Tampilan Profesional">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                        </button>

                        {{-- Tombol Theme Bento --}}
                        <button id="theme-toggle-bento" class="shrink-0 p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#001818] rounded-full transition-colors">
                            <svg class="theme-icon-light hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                            <svg class="theme-icon-dark hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                        </button>

                        <button id="reminder-toggle-bento" type="button"
                            class="relative shrink-0 p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#001818] rounded-full transition-colors"
                            aria-label="Buka pengingat tugas" aria-expanded="false">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9a6 6 0 0 0-12 0v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.55 1.083 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <span id="reminder-count-bento" class="absolute -right-0.5 -top-0.5 hidden min-w-4 h-4 rounded-full bg-red-500 px-1 text-[10px] leading-4 text-white">0</span>
                        </button>

                        <div class="relative flex items-center gap-2">
                            <button @click="userDropdown = !userDropdown" @click.away="userDropdown = false" class="flex items-center space-x-2 bg-gray-100 dark:bg-[#001818] py-1.5 px-3 rounded-full hover:bg-gray-200 dark:hover:bg-[#002525] transition-colors border border-transparent dark:border-[#002525]">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="userDropdown" style="display: none;" class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-[#001818] rounded-xl shadow-lg border border-gray-100 dark:border-[#002525] py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium"><span>Pengaturan Profil</span></a>
                                <button @click="showCalculator = !showCalculator; userDropdown = false" class="w-full flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium text-left"><span>Kalkulator Cepat</span></button>
                                <a href="{{ route('ai-chat') }}" class="flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium"><span>Chat AI</span></a>
                                <a href="{{ route('playlist') }}" class="flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium"><span>Playlist Musik</span></a>
                                <a href="{{ route('guide') }}" class="flex items-center justify-between px-4 py-2 text-sm text-[#68C7EC] hover:bg-[#68C7EC]/10 transition-colors font-medium"><span>Buku Panduan</span></a>
                                
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

        {{-- AREA UTAMA BENTO --}}
        <main class="relative z-10 w-full min-w-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white flex flex-wrap items-center gap-x-1">
                        Fokus Eksekusi, <span class="text-[#68C7EC]">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                    </h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="w-full sm:w-auto bg-white/90 dark:bg-[#001818]/90 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-[#002525] flex items-center gap-4 sm:min-w-[250px]">
                    <div class="w-12 h-12 rounded-full border-4 border-[#68C7EC]/20 dark:border-[#68C7EC]/10 flex items-center justify-center relative">
                        <svg class="absolute inset-0 w-full h-full text-[#68C7EC] transform -rotate-90" viewBox="0 0 36 36"><path stroke-dasharray="{{ $percentage }}, 100" class="stroke-current" fill="none" stroke-width="4" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" /></svg>
                        <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $percentage }}%</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Progres Keseluruhan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai</p>
                    </div>
                </div>
            </div>

            <div class="grid min-w-0 grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start relative z-10">
                <div class="min-w-0 lg:col-span-2 flex flex-col gap-6">
                    <form action="{{ route('tasks.store') }}" method="POST" class="bg-white/90 dark:bg-[#001818]/90 backdrop-blur-md p-1 rounded-2xl shadow-sm border border-gray-200 dark:border-[#002525] flex flex-col sm:flex-row focus-within:ring-2 focus-within:ring-[#68C7EC] focus-within:border-[#68C7EC] transition-all">
                        @csrf
                        <div class="flex min-w-0 flex-1 flex-col sm:flex-row sm:items-center">
                            <input type="text" name="title" placeholder="Tambah tugas baru untuk hari ini..." required class="w-full min-w-0 bg-transparent border-0 text-base sm:text-lg font-medium text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 px-4 py-3 sm:py-4">
                            <label class="flex w-full shrink-0 flex-col gap-1 px-4 pb-3 text-xs text-gray-500 dark:text-gray-400 sm:w-auto sm:px-2 sm:py-0">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-[#68C7EC]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" /></svg>
                                    Tanggal dan jam pengingat
                                </span>
                                <input type="datetime-local" name="reminder_at" class="w-full min-w-0 rounded-lg border-0 bg-transparent p-1 text-sm text-gray-700 focus:ring-0 dark:text-gray-200 sm:w-56">
                            </label>
                        </div>
                        <button type="submit" class="m-2 mt-0 sm:mt-2 px-6 py-3 sm:py-2 bg-[#68C7EC] text-[#000F0F] rounded-xl font-bold transition-all hover:opacity-90">Tambah</button>
                    </form>

                    <div class="min-w-0 bg-white/90 dark:bg-[#001818]/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-[#002525] p-4 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Eksekusi</h2>

                            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start space-x-1 bg-gray-100 dark:bg-[#000F0F] p-1 rounded-xl text-xs font-semibold border border-transparent dark:border-[#002525]">
                                <a href="{{ route('dashboard', ['filter' => 'all']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'all' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">Semua</a>
                                <a href="{{ route('dashboard', ['filter' => 'active']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'active' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">Aktif</a>
                                <a href="{{ route('dashboard', ['filter' => 'completed']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'completed' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">Selesai</a>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @forelse($tasks as $task)
                                @php
                                    $targetDate = $task->reminder_at ?? $task->deadline ?? null;
                                    $daysLeft = $targetDate ? (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($targetDate)->startOfDay(), false) : null;
                                @endphp

                                <div class="task-item group relative min-w-0 flex items-center justify-between gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 dark:bg-[#000F0F] rounded-2xl border border-gray-100 dark:border-[#002525] transition-all duration-300 ease-out hover:-translate-y-1 hover:border-[#68C7EC]/50 hover:shadow-lg hover:shadow-[#68C7EC]/10 overflow-hidden">
                                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-[#68C7EC]/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] pointer-events-none"></div>

                                    <div class="flex min-w-0 items-center space-x-3 sm:space-x-4 flex-1 relative z-10">
                                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-5 h-5 rounded-md border flex items-center justify-center transition-colors {{ $task->is_completed ? 'bg-[#68C7EC] border-[#68C7EC] text-[#000F0F]' : 'bg-white dark:bg-[#001818] border-gray-300 dark:border-gray-700' }}">
                                                @if($task->is_completed)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                                @endif
                                            </button>
                                        </form>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="task-title text-sm sm:text-base font-semibold {{ $task->is_completed ? 'text-gray-400 dark:text-gray-600 line-through' : 'text-gray-900 dark:text-white' }}">{{ $task->title }}</p>
                                                @if(!$task->is_completed && !is_null($daysLeft) && $daysLeft <= 2 && $daysLeft >= 0)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/30 animate-pulse">H-{{ $daysLeft }}</span>
                                                @endif
                                            </div>
                                            @if($task->reminder_at && !$task->is_completed)
                                                <p class="mt-1 flex gap-1 text-xs {{ $task->reminder_at->isPast() ? 'text-red-500' : 'text-gray-500' }}"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" /></svg>{{ $task->reminder_at->translatedFormat('d M Y, H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex shrink-0 items-center space-x-1 sm:space-x-2 sm:ml-4 relative z-10">
                                        <form class="delete-task-form" data-task-title="{{ $task->title }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="delete-task-button text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-500/10 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400 dark:text-gray-600"><p class="text-sm font-medium">Tidak ada tugas pada kategori ini.</p></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Widgets Kanan Bento --}}
                <div class="flex flex-col gap-6">
                    <div class="relative overflow-hidden rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-500 hover:-translate-y-1 group bg-white/90 dark:bg-[#001818]/90 backdrop-blur-md border border-gray-100 dark:border-[#002525]">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-gradient-to-br from-[#68C7EC]/30 to-transparent opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-2 flex items-center gap-2">
                                <div class="p-1.5 bg-[#68C7EC]/10 rounded-lg text-[#68C7EC] group-hover:rotate-12 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                Fokus Hari Ini
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm font-medium italic">"Jangan menunggu waktu yang tepat untuk mulai eksekusi. Mulailah sekarang, dan waktu itu akan menjadi tepat dengan sendirinya."</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#002525]/90 to-[#000F0F]/90 backdrop-blur-md border border-[#68C7EC]/20 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#68C7EC] opacity-10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                        <div class="relative z-10 flex justify-between items-start gap-3 mb-6">
                            <div>
                                <p class="text-[#68C7EC] text-xs font-bold uppercase tracking-widest mb-1">Playlist Fokus</p>
                                <p class="font-extrabold text-xl text-white">
                                    @if(Auth::user()->playlist_url) Playlist Kustom @else Atur Playlist-mu @endif
                                </p>
                            </div>
                            <a href="{{ route('playlist') }}" class="w-10 h-10 bg-[#68C7EC]/20 backdrop-blur-md rounded-full flex items-center justify-center hover:scale-110 transition-transform shadow-md border border-[#68C7EC]/30">
                                <svg class="w-5 h-5 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                            </a>
                        </div>
                        <div class="relative z-10">
                            <p class="text-xs text-gray-300 mb-4">
                                @if(Auth::user()->playlist_url) Tautan tersimpan. Gunakan Floating Player di pojok kanan bawah! @else Belum ada tautan musik. Klik ikon musik untuk menambahkan. @endif
                            </p>
                            <a href="{{ route('playlist') }}" class="block w-full py-2.5 bg-[#68C7EC] text-[#000F0F] rounded-xl font-bold text-center text-sm shadow-md hover:opacity-90 transition-all">Kelola Pemutar</a>
                        </div>
                    </div>
                    
                    <div class="bg-white/90 dark:bg-[#001818]/90 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-[#002525]">
                        <div class="flex justify-between mb-4 items-center">
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h2v7H3v-7zm4-6h2v13H7V7zm4-4h2v17h-2V3zm4 9h2v8h-2v-8zm4-5h2v13h-2V7z"></path></svg>
                                7 Hari Terakhir
                            </h3>
                            <span class="text-[#68C7EC] text-xs font-bold bg-[#68C7EC]/10 px-2 py-1 rounded-full">{{ $streak ?? 0 }} Hari Streak</span>
                        </div>
                        <div class="grid grid-cols-7 gap-2">
                            @foreach($activityDays as $day)
                                <div class="flex flex-col items-center">
                                    <div class="w-full h-8 rounded-lg flex items-center justify-center text-xs font-bold {{ $day['active'] ? 'bg-[#68C7EC] text-[#000F0F]' : 'bg-gray-100 dark:bg-[#000F0F] text-gray-400' }}">{{ $day['completed_count'] > 0 ? $day['completed_count'] : '-' }}</div>
                                    <span class="text-[10px] text-gray-400 mt-1">{{ $day['day'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    {{-- ========================================== --}}
    {{-- LAYOUT 2: MODE PROFESIONAL (SIDEBAR)       --}}
    {{-- ========================================== --}}
    <div x-show="layoutMode === 'pro'" class="flex h-screen w-full bg-gray-50 dark:bg-[#000808]" x-cloak>
        
        {{-- SIDEBAR KIRI --}}
        <aside class="w-64 bg-white dark:bg-[#001212] border-r border-gray-200 dark:border-[#002525] flex flex-col justify-between shrink-0 h-screen sticky top-0 overflow-hidden shadow-sm z-50">
            <div class="p-6 overflow-y-auto h-full scrollbar-hide">
                <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-[#68C7EC] flex items-center gap-2 mb-10 hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo.png') }}" class="w-8 h-8 object-contain">
                    <span>My<span class="text-gray-900 dark:text-white">Study</span><span class="text-[#68C7EC] ml-0.5">Pro</span></span>
                </a>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[#68C7EC]/10 text-[#68C7EC] rounded-xl font-bold border border-[#68C7EC]/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    <button @click="showCalculator = true" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#002020] rounded-xl font-medium transition-colors text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Kalkulator
                    </button>
                    <a href="{{ route('ai-chat') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#002020] rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Tanya AI
                    </a>
                    <a href="{{ route('playlist') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#002020] rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        Musik Fokus
                    </a>
                    <a href="{{ route('guide') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#002020] rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Panduan
                    </a>
                </nav>
            </div>
            
            <div class="p-6 border-t border-gray-200 dark:border-[#002525] space-y-3 shrink-0 bg-white dark:bg-[#001212]">
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('profile.edit') }}">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=68C7EC&color=000F0F&bold=true" class="w-10 h-10 rounded-full">
                        @endif
                    </a>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-500 hover:text-red-500 transition-colors">Log out</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 overflow-y-auto p-8 relative">
            <div class="flex justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Selamat Malam, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                    <p class="text-gray-500 mt-1">Siap untuk fokus belajar hari ini?</p>
                </div>
                
                {{-- KUMPULAN TOMBOL HEADER PRO MODE --}}
                <div class="flex items-center gap-1 sm:gap-2">
                    {{-- Tombol Switch UI (ke Bento) --}}
                    <button @click="layoutMode = 'bento'" class="shrink-0 p-2 text-gray-400 hover:text-[#68C7EC] dark:text-gray-500 dark:hover:text-[#68C7EC] hover:bg-gray-100 dark:hover:bg-[#002525] rounded-full transition-colors flex items-center" title="Kembali ke UI Estetik">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </button>

                    {{-- Tombol Tema --}}
                    <button id="theme-toggle-pro-header" class="shrink-0 p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#002525] rounded-full transition-colors">
                        <svg class="theme-icon-light hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                        <svg class="theme-icon-dark hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                    </button>

                    {{-- Tombol Pengingat (Reminder) --}}
                    <button id="reminder-toggle-pro" type="button"
                        class="relative shrink-0 p-2 text-gray-500 dark:text-gray-400 hover:text-[#68C7EC] dark:hover:text-[#68C7EC] hover:bg-gray-100 dark:hover:bg-[#002525] rounded-full transition-colors"
                        aria-label="Buka pengingat tugas" aria-expanded="false">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9a6 6 0 0 0-12 0v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.55 1.083 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <span id="reminder-count-pro" class="absolute -right-0 -top-0 hidden min-w-4 h-4 rounded-full bg-red-500 px-1 text-[10px] leading-4 text-white">0</span>
                    </button>
                </div>
            </div>

            {{-- 4 STATS ROW --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('dashboard', ['filter' => 'all']) }}" class="block bg-white dark:bg-[#001818] border border-gray-100 dark:border-[#002525] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:border-[#68C7EC]/50 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
                    <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Total Tugas</p><p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalTasks }}</p></div>
                </a>
                
                <div class="bg-white dark:bg-[#001818] border border-gray-100 dark:border-[#002525] rounded-2xl p-5 flex items-center gap-4 shadow-sm transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-[#68C7EC]/10 text-[#68C7EC] flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                    <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Streak Aktif</p><p class="text-2xl font-black text-gray-900 dark:text-white">{{ $streak ?? 0 }} <span class="text-sm font-medium text-gray-400">hari</span></p></div>
                </div>
                
                <a href="{{ route('dashboard', ['filter' => 'active']) }}" class="block bg-white dark:bg-[#001818] border border-gray-100 dark:border-[#002525] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:border-green-500/50 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tugas Aktif</p><p class="text-2xl font-black text-gray-900 dark:text-white">{{ $activeTasks }}</p></div>
                </a>
                
                <a href="{{ route('dashboard', ['filter' => 'completed']) }}" class="block bg-white dark:bg-[#001818] border border-gray-100 dark:border-[#002525] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:border-[#68C7EC]/50 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-[#68C7EC]/10 text-[#68C7EC] flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                    <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Selesai</p><p class="text-2xl font-black text-gray-900 dark:text-white">{{ $completedTasks }} <span class="text-sm font-medium text-gray-400">/ {{ $percentage }}%</span></p></div>
                </a>
            </div>

            {{-- PROGRESS LEVEL BAR --}}
            <div class="bg-white dark:bg-[#001818] border border-gray-100 dark:border-[#002525] rounded-2xl p-6 mb-8 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#68C7EC] text-[#000F0F] flex items-center justify-center font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">Level Fokus</p>
                            <p class="text-xs text-gray-500">Konsisten Belajar</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-400">{{ $completedTasks }} / {{ $totalTasks }} Selesai</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-[#000F0F] rounded-full h-3 overflow-hidden">
                    <div class="bg-[#68C7EC] h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                </div>
            </div>

            {{-- DAFTAR TUGAS FULL WIDTH --}}
            <div class="bg-white dark:bg-[#001818] border border-gray-100 dark:border-[#002525] rounded-2xl p-6 shadow-sm">
                
                {{-- HEADER LIST & FILTER TABS --}}
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-6 gap-4 border-b border-gray-100 dark:border-[#002525] pb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Tugasmu</h2>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                        {{-- Tombol Filter di Pro Mode --}}
                        <div class="flex items-center space-x-1 bg-gray-100 dark:bg-[#000F0F] p-1 rounded-xl text-xs font-semibold border border-transparent dark:border-[#002525]">
                            <a href="{{ route('dashboard', ['filter' => 'all']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'all' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">Semua</a>
                            <a href="{{ route('dashboard', ['filter' => 'active']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'active' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">Aktif</a>
                            <a href="{{ route('dashboard', ['filter' => 'completed']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ ($currentFilter ?? 'all') === 'completed' ? 'bg-white dark:bg-[#002525] text-[#68C7EC] shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">Selesai</a>
                        </div>

                        {{-- Form Cepat Pro Mode --}}
                        <form action="{{ route('tasks.store') }}" method="POST" class="flex items-center gap-2 w-full sm:w-auto">
                            @csrf
                            <input type="text" name="title" placeholder="Tugas baru..." required class="flex-1 bg-gray-50 dark:bg-[#000F0F] border border-gray-200 dark:border-[#002525] rounded-lg px-4 py-2 text-sm text-gray-900 dark:text-white focus:border-[#68C7EC] focus:ring-0 outline-none">
                            <input type="datetime-local" name="reminder_at" class="bg-gray-50 dark:bg-[#000F0F] border border-gray-200 dark:border-[#002525] rounded-lg px-2 py-2 text-sm text-gray-700 dark:text-gray-400 focus:border-[#68C7EC] focus:ring-0 w-10 md:w-auto outline-none">
                            <button type="submit" class="bg-[#68C7EC] text-[#000F0F] px-4 py-2 rounded-lg font-bold text-sm hover:brightness-110 transition-colors shadow-sm whitespace-nowrap">+ Tambah</button>
                        </form>
                    </div>
                </div>

                {{-- INLINE TASK HTML untuk Pro Layout --}}
                <div class="space-y-3">
                    @forelse($tasks as $task)
                        @php
                            $targetDate = $task->reminder_at ?? $task->deadline ?? null;
                            $daysLeft = $targetDate ? (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($targetDate)->startOfDay(), false) : null;
                        @endphp

                        <div class="task-item group relative min-w-0 flex items-center justify-between gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 dark:bg-[#000F0F] rounded-2xl border border-gray-100 dark:border-[#002525] transition-all duration-300 ease-out hover:-translate-y-1 hover:border-[#68C7EC]/50 hover:shadow-lg hover:shadow-[#68C7EC]/10 overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-[#68C7EC]/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] pointer-events-none"></div>

                            <div class="flex min-w-0 items-center space-x-3 sm:space-x-4 flex-1 relative z-10">
                                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-5 h-5 rounded-md border flex items-center justify-center transition-colors {{ $task->is_completed ? 'bg-[#68C7EC] border-[#68C7EC] text-[#000F0F]' : 'bg-white dark:bg-[#001818] border-gray-300 dark:border-gray-700' }}">
                                        @if($task->is_completed)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </button>
                                </form>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="task-title text-sm sm:text-base font-semibold {{ $task->is_completed ? 'text-gray-400 dark:text-gray-600 line-through' : 'text-gray-900 dark:text-white' }}">{{ $task->title }}</p>
                                        @if(!$task->is_completed && !is_null($daysLeft) && $daysLeft <= 2 && $daysLeft >= 0)
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/30 animate-pulse">H-{{ $daysLeft }}</span>
                                        @endif
                                    </div>
                                    @if($task->reminder_at && !$task->is_completed)
                                        <p class="mt-1 flex gap-1 text-xs {{ $task->reminder_at->isPast() ? 'text-red-500' : 'text-gray-500' }}"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" /></svg>{{ $task->reminder_at->translatedFormat('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center space-x-1 sm:space-x-2 sm:ml-4 relative z-10">
                                <form class="delete-task-form" data-task-title="{{ $task->title }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="delete-task-button text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-500/10 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-6 text-gray-500 text-sm font-medium">Belum ada tugas aktif untuk dieksekusi.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>


    {{-- ========================================== --}}
    {{-- MODAL & SCRIPT BERSAMA                     --}}
    {{-- ========================================== --}}

    <div id="reminder-panel"
        class="fixed right-4 top-20 z-40 hidden w-[min(92vw,360px)] rounded-2xl border border-gray-200 bg-white p-4 shadow-xl dark:border-[#002525] dark:bg-[#001818]">
        <div class="flex items-center justify-between gap-3 border-b border-gray-100 pb-3 dark:border-[#002525]">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Pengingat Tugas</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tugas yang perlu kamu perhatikan</p>
            </div>
            <button id="enable-notifications" type="button"
                class="rounded-lg bg-[#68C7EC]/15 px-2.5 py-1.5 text-xs font-semibold text-[#1684a8] transition-colors hover:bg-[#68C7EC]/25 dark:text-[#68C7EC]">Aktifkan notifikasi</button>
        </div>
        <div class="mt-3 max-h-72 space-y-2 overflow-y-auto">
            @forelse($reminderTasks as $task)
                <div class="rounded-xl bg-gray-50 p-3 dark:bg-[#000F0F]" data-reminder-item
                    data-reminder-id="{{ $task->id }}" data-reminder-at="{{ $task->reminder_at->toIso8601String() }}">
                    <p class="break-words text-sm font-semibold text-gray-900 dark:text-white">{{ $task->title }}</p>
                    <p class="mt-1 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                        </svg>
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
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M5.43 19h13.14a2 2 0 001.94-2.54L14.4 4.7a2 2 0 00-3.8 0L3.49 16.46A2 2 0 005.43 19z"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-gray-900 dark:text-white">Hapus tugas?</p>
                <p id="delete-confirm-text" class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus tugas ini?</p>
                <div class="mt-5 flex justify-end gap-2.5">
                    <button id="delete-cancel-btn" type="button" class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">Batal</button>
                    <button id="delete-confirm-btn" type="button" class="px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-all duration-200 shadow-lg shadow-red-500/20 hover:shadow-red-500/30">Hapus</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Widget Kalkulator Melayang --}}
    <div x-data="{
        display: '0',
        history: '',
        rawHistory: '',
        waitingForNext: false,
        formatNum(val) {
            if (!val || val === 'Error') return val;
            let parts = val.toString().split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return parts.join(',');
        },
        appendNum(n) {
            if (this.waitingForNext) {
                this.display = n;
                this.waitingForNext = false;
            } else {
                let digitCount = this.display.replace(/[^0-9]/g, '').length;
                if (digitCount >= 12) return;
                this.display = this.display === '0' ? n : this.display + n;
            }
        },
        appendDot() {
            if (this.waitingForNext) {
                this.display = '0.';
                this.waitingForNext = false;
                return;
            }
            if (!this.display.includes('.')) {
                this.display += '.';
            }
        },
        setOp(op) {
            this.rawHistory = this.display + ' ' + op + ' ';
            this.history = this.formatNum(this.display) + ' ' + op + ' ';
            this.waitingForNext = true;
        },
        clearAll() {
            this.display = '0';
            this.history = '';
            this.rawHistory = '';
            this.waitingForNext = false;
        },
        backspace() {
            if (this.waitingForNext) return;
            this.display = this.display.length > 1 ? this.display.slice(0, -1) : '0';
        },
        percent() {
            this.display = (parseFloat(this.display) / 100).toString();
        },
        calculate() {
            if (!this.rawHistory || this.waitingForNext) return;
            let fullRawExpr = this.rawHistory + this.display;
            let cleanExpr = fullRawExpr.replace(/×/g, '*').replace(/÷/g, '/');
            try {
                let res = eval(cleanExpr);
                this.history = this.history + this.formatNum(this.display) + ' =';
                this.rawHistory = '';
                this.display = Number.isInteger(res) ? res.toString() : parseFloat(res.toFixed(4)).toString();
                this.waitingForNext = true;
            } catch (e) {
                this.display = 'Error';
            }
        }
    }" x-show="showCalculator" x-cloak 
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-6 left-6 z-50 w-80 bg-white/90 dark:bg-[#001818]/90 border border-gray-200 dark:border-[#002525] rounded-3xl shadow-2xl p-4 backdrop-blur-xl">
        
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-[#002525]">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-[#68C7EC] animate-pulse"></div>
                <span class="text-sm font-bold text-gray-900 dark:text-white">Kalkulator</span>
            </div>
            <button @click="showCalculator = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-[#002525] transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <div class="bg-gray-50 dark:bg-[#000F0F] border border-gray-100 dark:border-[#002525] rounded-2xl p-3 mb-3 text-right">
            <div class="text-xs text-gray-400 h-4 overflow-x-auto font-mono" x-text="history"></div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white truncate tracking-wider font-mono" x-text="formatNum(display)"></div>
        </div>

        <div class="grid grid-cols-4 gap-2 text-sm font-bold">
            <button @click="clearAll()" class="p-2.5 text-red-500 bg-red-500/10 hover:bg-red-500/20 rounded-xl transition-all">AC</button>
            <button @click="backspace()" class="p-2.5 text-amber-500 bg-amber-500/10 hover:bg-amber-500/20 rounded-xl transition-all">⌫</button>
            <button @click="percent()" class="p-2.5 text-[#68C7EC] bg-[#68C7EC]/10 hover:bg-[#68C7EC]/20 rounded-xl transition-all">%</button>
            <button @click="setOp('÷')" class="p-2.5 text-[#68C7EC] bg-[#68C7EC]/10 hover:bg-[#68C7EC]/20 rounded-xl transition-all">÷</button>
            <button @click="appendNum('7')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">7</button>
            <button @click="appendNum('8')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">8</button>
            <button @click="appendNum('9')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">9</button>
            <button @click="setOp('×')" class="p-2.5 text-[#68C7EC] bg-[#68C7EC]/10 hover:bg-[#68C7EC]/20 rounded-xl transition-all">×</button>
            <button @click="appendNum('4')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">4</button>
            <button @click="appendNum('5')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">5</button>
            <button @click="appendNum('6')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">6</button>
            <button @click="setOp('-')" class="p-2.5 text-[#68C7EC] bg-[#68C7EC]/10 hover:bg-[#68C7EC]/20 rounded-xl transition-all">-</button>
            <button @click="appendNum('1')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">1</button>
            <button @click="appendNum('2')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">2</button>
            <button @click="appendNum('3')" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">3</button>
            <button @click="setOp('+')" class="p-2.5 text-[#68C7EC] bg-[#68C7EC]/10 hover:bg-[#68C7EC]/20 rounded-xl transition-all">+</button>
            <button @click="appendNum('0')" class="col-span-2 p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">0</button>
            <button @click="appendDot()" class="p-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-900 dark:text-white rounded-xl transition-all">.</button>
            <button @click="calculate()" class="p-2.5 bg-[#68C7EC] text-[#000F0F] hover:brightness-110 rounded-xl transition-all">=</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Update Ikon Tema Secara Sinkron
            function updateThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark');
                document.querySelectorAll('.theme-icon-dark').forEach(el => {
                    if (isDark) el.classList.add('hidden'); // Sembunyikan bulan saat dark mode
                    else el.classList.remove('hidden'); // Tampilkan bulan saat light mode
                });
                document.querySelectorAll('.theme-icon-light').forEach(el => {
                    if (isDark) el.classList.remove('hidden'); // Tampilkan matahari saat dark mode
                    else el.classList.add('hidden'); // Sembunyikan matahari saat light mode
                });
            }

            // Fungsi Global Klik Toggle Tema
            function setupThemeToggle(btnId) {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.addEventListener('click', function () {
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
                        updateThemeIcons(); // Update semua ikon secara instan
                    });
                }
            }

            // Set up semua tombol ganti tema di berbagai tempat
            setupThemeToggle('theme-toggle-bento');
            setupThemeToggle('theme-toggle-pro-sidebar');
            setupThemeToggle('theme-toggle-pro-header');
            updateThemeIcons(); // Jalankan sekali saat load agar ikon sesuai tema saat ini

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

                deleteCancelBtn.addEventListener('click', () => { pendingDeleteForm = null; hideDeleteToast(); });
                deleteBackdrop.addEventListener('click', () => { pendingDeleteForm = null; hideDeleteToast(); });

                deleteConfirmBtn.addEventListener('click', function () {
                    if (pendingDeleteForm) pendingDeleteForm.submit();
                });
            }

            const deleteForms = document.querySelectorAll('.delete-task-form');
            deleteForms.forEach(function (form) {
                const deleteButton = form.querySelector('.delete-task-button');
                if (deleteButton) {
                    deleteButton.addEventListener('click', function (event) {
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

            // Handle Reminder Toggle for both Bento and Pro buttons
            const reminderToggleBento = document.getElementById('reminder-toggle-bento');
            const reminderTogglePro = document.getElementById('reminder-toggle-pro');
            const reminderPanel = document.getElementById('reminder-panel');
            
            function toggleReminderPanel() {
                if (reminderPanel) {
                    const isHidden = reminderPanel.classList.toggle('hidden');
                    if(reminderToggleBento) reminderToggleBento.setAttribute('aria-expanded', String(!isHidden));
                    if(reminderTogglePro) reminderTogglePro.setAttribute('aria-expanded', String(!isHidden));
                }
            }

            if (reminderToggleBento) reminderToggleBento.addEventListener('click', toggleReminderPanel);
            if (reminderTogglePro) reminderTogglePro.addEventListener('click', toggleReminderPanel);
            
            document.addEventListener('click', function (event) {
                if (reminderPanel && !reminderPanel.contains(event.target) && 
                    (reminderToggleBento && !reminderToggleBento.contains(event.target)) &&
                    (reminderTogglePro && !reminderTogglePro.contains(event.target))) {
                    reminderPanel.classList.add('hidden');
                    if(reminderToggleBento) reminderToggleBento.setAttribute('aria-expanded', 'false');
                    if(reminderTogglePro) reminderTogglePro.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
    
    @include('components.floating-player')
</body>
</html>