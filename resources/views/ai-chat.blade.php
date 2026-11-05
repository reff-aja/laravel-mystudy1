<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="chatApp()" :class="colorTheme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Assistant - MyStudy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #2a2a2a;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #68C7EC;
        }

        .chat-bubble a {
            color: #68C7EC;
            text-decoration: underline;
        }

        .chat-bubble pre {
            background: #181818;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 0.5rem 0;
            border: 1px solid #333;
        }

        .chat-bubble code {
            background: #222;
            padding: 0.15rem 0.35rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-size: 0.875em;
        }

        .chat-bubble pre code {
            background: transparent;
            padding: 0;
        }

        .chat-bubble p {
            margin-bottom: 0.5rem;
        }

        .chat-bubble p:last-child {
            margin-bottom: 0;
        }

        .chat-bubble ul,
        .chat-bubble ol {
            margin-left: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .chat-bubble ul {
            list-style-type: disc;
        }

        .chat-bubble ol {
            list-style-type: decimal;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-100 bg-[#000000] h-screen flex overflow-hidden">

    {{-- MOBILE OVERLAY BACKDROP --}}
    <div x-show="mobileSidebarOpen" x-cloak
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-30 md:hidden"
        @click="mobileSidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    {{-- SIDEBAR HISTORY --}}
    <aside class="w-80 md:w-72 bg-[#0a0a0a] border-r border-gray-800 flex flex-col h-full shrink-0 fixed inset-y-0 left-0 z-40 md:static md:translate-x-0 transition-transform duration-300 shadow-2xl md:shadow-none"
        :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        {{-- Top Navigation & Action --}}
        <div class="p-4 border-b border-gray-800 flex items-center justify-between gap-2">
            <a href="{{ route('dashboard') }}"
                class="text-xs text-gray-400 hover:text-[#68C7EC] transition-colors flex items-center gap-1">
                <span>&larr;</span>
                <span>Dashboard</span>
            </a>
            
            <div class="flex items-center gap-2">
                <button @click="startNewChat()"
                    class="text-[#000F0F] bg-[#68C7EC] hover:brightness-110 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-[0_0_10px_rgba(104,199,236,0.2)] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Chat Baru</span>
                </button>

                {{-- Close button for mobile drawer --}}
                <button @click="mobileSidebarOpen = false"
                    class="md:hidden p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-[#202020]"
                    title="Tutup Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Search Input (shown when chats exist) --}}
        <div x-show="chats.length > 2" class="p-3 pb-0">
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Cari obrolan..."
                    class="w-full bg-[#141414] border border-gray-800 rounded-xl pl-8 pr-3 py-1.5 text-xs text-gray-200 placeholder-gray-500 focus:border-[#68C7EC] focus:outline-none focus:ring-1 focus:ring-[#68C7EC] transition-all">
                <svg class="absolute left-2.5 top-2.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2 text-gray-500 hover:text-gray-300">
                    &times;
                </button>
            </div>
        </div>

        {{-- Scrollable Chat List --}}
        <div class="flex-1 overflow-y-auto px-2 py-3 space-y-4">

            {{-- 1. BAGIAN DISEMATKAN (PINNED CHATS) --}}
            <div x-show="pinnedChats.length > 0">
                <div class="px-2 pb-1.5 text-[10px] font-bold text-[#68C7EC] uppercase tracking-wider flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                            <line x1="12" y1="17" x2="12" y2="22"></line>
                            <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                        </svg>
                        <span>Disematkan (<span x-text="pinnedChats.length"></span>)</span>
                    </span>
                </div>

                <div class="space-y-1">
                    <template x-for="chat in pinnedChats" :key="chat.id">
                        <div class="group relative rounded-xl transition-all border text-xs"
                            :class="currentChatId === chat.id ? 'bg-[#202020] border-[#68C7EC]/50 text-white' : 'hover:bg-[#141414] border-transparent text-gray-300'">

                            <div class="flex items-center justify-between p-2.5 gap-1">
                                <button @click="selectChat(chat.id)" class="flex-1 text-left truncate flex items-center gap-2 min-w-0 pr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-[#68C7EC] shrink-0">
                                        <line x1="12" y1="17" x2="12" y2="22"></line>
                                        <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                                    </svg>
                                    <span class="truncate font-medium" x-text="chat.title"></span>
                                </button>

                                {{-- TOMBOL 3 TITIK --}}
                                <div class="relative shrink-0">
                                    <button @click.stop="openDropdownId = (openDropdownId === chat.id ? null : chat.id)"
                                        title="Opsi Obrolan"
                                        class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-[#2c2c2c] transition-colors"
                                        :class="(currentChatId === chat.id || openDropdownId === chat.id) ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1.5"></circle>
                                            <circle cx="19" cy="12" r="1.5"></circle>
                                            <circle cx="5" cy="12" r="1.5"></circle>
                                        </svg>
                                    </button>

                                    {{-- DROPDOWN POPUP MENU --}}
                                    <div x-show="openDropdownId === chat.id"
                                        x-cloak
                                        @click.outside="if (openDropdownId === chat.id) openDropdownId = null"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 top-8 z-30 w-44 bg-[#181818] border border-gray-700/80 rounded-xl shadow-2xl py-1 text-xs backdrop-blur-md">
                                        
                                        {{-- 1. Lepas Sematan --}}
                                        <button @click.stop="togglePin(chat.id); openDropdownId = null"
                                            class="w-full text-left px-3 py-2 flex items-center gap-2.5 text-gray-300 hover:text-white hover:bg-[#252525] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" class="text-[#68C7EC]">
                                                <line x1="12" y1="17" x2="12" y2="22"></line>
                                                <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                                            </svg>
                                            <span>Lepas Sematan</span>
                                        </button>

                                        {{-- 2. Ubah Nama --}}
                                        <button @click.stop="openRenameModal(chat); openDropdownId = null"
                                            class="w-full text-left px-3 py-2 flex items-center gap-2.5 text-gray-300 hover:text-white hover:bg-[#252525] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                            </svg>
                                            <span>Ubah Nama</span>
                                        </button>

                                        <div class="h-px bg-gray-800 my-1"></div>

                                        {{-- 3. Hapus Obrolan --}}
                                        <button @click.stop="confirmDelete(chat); openDropdownId = null"
                                            class="w-full text-left px-3 py-2 flex items-center gap-2.5 text-red-400 hover:text-red-300 hover:bg-red-950/30 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            <span>Hapus Obrolan</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 2. BAGIAN RIWAYAT SEMUA OBROLAN (UNPINNED) --}}
            <div>
                <div class="px-2 pb-1.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider flex items-center justify-between">
                    <span x-text="pinnedChats.length > 0 ? 'Obrolan Lainnya' : 'Riwayat Chat'"></span>
                    <button x-show="chats.length > 0"
                        @click="showClearAllModal = true"
                        title="Hapus Semua Riwayat Chat"
                        class="text-[10px] text-gray-500 hover:text-red-400 transition-colors lowercase flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span>hapus semua</span>
                    </button>
                </div>

                <div class="space-y-1">
                    <template x-for="chat in unpinnedChats" :key="chat.id">
                        <div class="group relative rounded-xl transition-all border text-xs"
                            :class="currentChatId === chat.id ? 'bg-[#202020] border-[#68C7EC]/50 text-white' : 'hover:bg-[#141414] border-transparent text-gray-400'">

                            <div class="flex items-center justify-between p-2.5 gap-1">
                                <button @click="selectChat(chat.id)" class="flex-1 text-left truncate flex items-center gap-2 min-w-0 pr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="shrink-0"
                                        :class="currentChatId === chat.id ? 'text-[#68C7EC]' : 'text-gray-500'" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span class="truncate font-medium" x-text="chat.title"></span>
                                </button>

                                {{-- TOMBOL 3 TITIK --}}
                                <div class="relative shrink-0">
                                    <button @click.stop="openDropdownId = (openDropdownId === chat.id ? null : chat.id)"
                                        title="Opsi Obrolan"
                                        class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-[#2c2c2c] transition-colors"
                                        :class="(currentChatId === chat.id || openDropdownId === chat.id) ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1.5"></circle>
                                            <circle cx="19" cy="12" r="1.5"></circle>
                                            <circle cx="5" cy="12" r="1.5"></circle>
                                        </svg>
                                    </button>

                                    {{-- DROPDOWN POPUP MENU --}}
                                    <div x-show="openDropdownId === chat.id"
                                        x-cloak
                                        @click.outside="if (openDropdownId === chat.id) openDropdownId = null"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 top-8 z-30 w-44 bg-[#181818] border border-gray-700/80 rounded-xl shadow-2xl py-1 text-xs backdrop-blur-md">
                                        
                                        {{-- 1. Sematkan --}}
                                        <button @click.stop="togglePin(chat.id); openDropdownId = null"
                                            class="w-full text-left px-3 py-2 flex items-center gap-2.5 text-gray-300 hover:text-white hover:bg-[#252525] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                                <line x1="12" y1="17" x2="12" y2="22"></line>
                                                <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                                            </svg>
                                            <span>Sematkan Chat</span>
                                        </button>

                                        {{-- 2. Ubah Nama --}}
                                        <button @click.stop="openRenameModal(chat); openDropdownId = null"
                                            class="w-full text-left px-3 py-2 flex items-center gap-2.5 text-gray-300 hover:text-white hover:bg-[#252525] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                            </svg>
                                            <span>Ubah Nama</span>
                                        </button>

                                        <div class="h-px bg-gray-800 my-1"></div>

                                        {{-- 3. Hapus Obrolan --}}
                                        <button @click.stop="confirmDelete(chat); openDropdownId = null"
                                            class="w-full text-left px-3 py-2 flex items-center gap-2.5 text-red-400 hover:text-red-300 hover:bg-red-950/30 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            <span>Hapus Obrolan</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Empty State --}}
            <div x-show="chats.length === 0" class="text-center py-10 px-4 text-xs text-gray-600">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-[#161616] flex items-center justify-center text-gray-500">💬</div>
                Belum ada riwayat chat
            </div>

            <div x-show="chats.length > 0 && filteredChats.length === 0" class="text-center py-6 px-4 text-xs text-gray-600">
                Tidak ditemukan hasil untuk "<span class="text-gray-400" x-text="searchQuery"></span>"
            </div>
        </div>
    </aside>

    {{-- AREA UTAMA CHAT --}}
    <main class="flex-1 flex flex-col h-full bg-[#000000] relative">
        <header class="shrink-0 bg-[#000000]/80 backdrop-blur-md border-b border-gray-800 p-3 md:p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Mobile Hamburger Button --}}
                <button @click="mobileSidebarOpen = true"
                    class="md:hidden p-2 text-gray-400 hover:text-white rounded-xl hover:bg-[#181818] transition-colors shrink-0"
                    title="Buka Riwayat">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <div class="w-10 h-10 rounded-xl bg-[#202020] flex items-center justify-center border border-[#68C7EC]/30 shrink-0">
                    🤖
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="font-bold text-sm md:text-base text-white truncate max-w-[180px] sm:max-w-xs md:max-w-md"
                            x-text="currentChat ? currentChat.title : 'MyStudy AI'"></h1>
                        <template x-if="currentChat && currentChat.pinned">
                            <span class="text-[10px] bg-[#68C7EC]/10 text-[#68C7EC] border border-[#68C7EC]/30 px-2 py-0.5 rounded-full flex items-center gap-1 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="currentColor">
                                    <line x1="12" y1="17" x2="12" y2="22"></line>
                                    <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                                </svg>
                                <span>Disematkan</span>
                            </span>
                        </template>
                    </div>
                    <p class="text-xs text-[#68C7EC]">Model: Teks Super Cerdas</p>
                </div>
            </div>

            {{-- TOMBOL 3 TITIK PADA HEADER CHAT AKTIF --}}
            <template x-if="currentChat">
                <div class="relative shrink-0" x-data="{ headerMenuOpen: false }">
                    <button @click="headerMenuOpen = !headerMenuOpen"
                        title="Opsi Obrolan"
                        class="p-2 rounded-xl border border-gray-800 hover:border-[#68C7EC]/50 hover:bg-[#181818] text-gray-400 hover:text-white transition-colors flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="1.5"></circle>
                            <circle cx="19" cy="12" r="1.5"></circle>
                            <circle cx="5" cy="12" r="1.5"></circle>
                        </svg>
                    </button>

                    {{-- HEADER DROPDOWN POPUP MENU --}}
                    <div x-show="headerMenuOpen"
                        x-cloak
                        @click.outside="headerMenuOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-12 z-30 w-48 bg-[#181818] border border-gray-700/80 rounded-xl shadow-2xl py-1 text-xs backdrop-blur-md">
                        
                        {{-- 1. Sematkan / Lepas Sematan --}}
                        <button @click="togglePin(currentChat.id); headerMenuOpen = false"
                            class="w-full text-left px-3.5 py-2.5 flex items-center gap-2.5 text-gray-300 hover:text-white hover:bg-[#252525] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" :fill="currentChat.pinned ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"
                                :class="currentChat.pinned ? 'text-[#68C7EC]' : 'text-gray-400'">
                                <line x1="12" y1="17" x2="12" y2="22"></line>
                                <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                            </svg>
                            <span x-text="currentChat.pinned ? 'Lepas Sematan' : 'Sematkan Chat'"></span>
                        </button>

                        {{-- 2. Ubah Nama --}}
                        <button @click="openRenameModal(currentChat); headerMenuOpen = false"
                            class="w-full text-left px-3.5 py-2.5 flex items-center gap-2.5 text-gray-300 hover:text-white hover:bg-[#252525] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-400">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                            <span>Ubah Nama</span>
                        </button>

                        <div class="h-px bg-gray-800 my-1"></div>

                        {{-- 3. Hapus Obrolan --}}
                        <button @click="confirmDelete(currentChat); headerMenuOpen = false"
                            class="w-full text-left px-3.5 py-2.5 flex items-center gap-2.5 text-red-400 hover:text-red-300 hover:bg-red-950/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Hapus Obrolan</span>
                        </button>
                    </div>
                </div>
            </template>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth" id="chat-container">
            <div class="max-w-4xl mx-auto space-y-6 pb-20">

                {{-- Pesan Default saat Chat Kosong --}}
                <div x-show="activeMessages.length === 0" class="flex gap-4">
                    <div
                        class="w-8 h-8 shrink-0 rounded-full bg-[#202020] flex items-center justify-center text-sm border border-[#68C7EC]/30">
                        🤖</div>
                    <div
                        class="bg-[#121212] border border-gray-800 text-gray-300 p-4 md:p-5 rounded-2xl rounded-tl-none text-sm md:text-base leading-relaxed">
                        Halo bro! Aku MyStudy AI. Mau bahas materi atau tugas apa hari ini? Kamu bisa mulai obrolan baru kapan saja!
                    </div>
                </div>

                <template x-for="(msg, index) in activeMessages" :key="index">
                    <div class="flex gap-4" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                        <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-sm border"
                            :class="msg.role === 'user' ? 'bg-[#68C7EC] text-[#000F0F] border-[#68C7EC]' : 'bg-[#202020] border-[#68C7EC]/30'">
                            <span x-text="msg.role === 'user' ? '👨‍🎓' : '🤖'"></span>
                        </div>

                        <div class="chat-bubble p-4 md:p-5 rounded-2xl max-w-[85%] text-sm md:text-base leading-relaxed"
                            :class="msg.role === 'user' ? 'bg-[#202020] text-white rounded-tr-none' : 'bg-[#121212] border border-gray-800 text-gray-300 rounded-tl-none'">
                            <template x-if="msg.attachment">
                                <div
                                    class="mb-3 p-2 bg-[#000000] rounded-lg border border-gray-700 flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        class="text-[#68C7EC]">
                                        <path
                                            d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                                        </path>
                                    </svg>
                                    <span class="text-xs text-gray-400" x-text="msg.attachment"></span>
                                </div>
                            </template>
                            <span x-html="formatText(msg.content)"></span>
                        </div>
                    </div>
                </template>

                <div x-show="isLoading" class="flex gap-4">
                    <div
                        class="w-8 h-8 shrink-0 rounded-full bg-[#202020] flex items-center justify-center text-sm border border-[#68C7EC]/30">
                        🤖</div>
                    <div
                        class="bg-[#121212] border border-gray-800 p-5 rounded-2xl rounded-tl-none flex gap-1 items-center h-12">
                        <span class="w-2 h-2 bg-[#68C7EC] rounded-full animate-bounce"></span>
                        <span class="w-2 h-2 bg-[#68C7EC] rounded-full animate-bounce"
                            style="animation-delay: 0.2s"></span>
                        <span class="w-2 h-2 bg-[#68C7EC] rounded-full animate-bounce"
                            style="animation-delay: 0.4s"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="shrink-0 p-4 md:p-6 bg-gradient-to-t from-[#000000] to-transparent w-full">
            <div class="max-w-4xl mx-auto relative">
                <div x-show="selectedFile"
                    class="absolute -top-12 left-0 bg-[#202020] text-xs text-gray-300 px-4 py-2 rounded-lg border border-gray-700 flex items-center gap-2 shadow-lg">
                    <span class="truncate max-w-[200px]" x-text="selectedFile"></span>
                    <button @click="selectedFile = null"
                        class="text-red-400 hover:text-red-300 font-bold">&times;</button>
                </div>
                <form @submit.prevent="sendMessage"
                    class="flex items-center gap-2 bg-[#121212] border border-gray-800 rounded-2xl p-2 shadow-2xl focus-within:border-[#68C7EC] transition-colors">
                    <input type="file" id="file-upload" class="hidden" @change="handleFileUpload"
                        accept="image/*,video/*,audio/*,.pdf">
                    <button type="button" @click="document.getElementById('file-upload').click()"
                        class="p-3 text-gray-400 hover:text-[#68C7EC] transition-colors rounded-xl hover:bg-[#202020]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </button>
                    <input type="text" x-model="newMessage" placeholder="Ketik pesan di sini..."
                        class="flex-grow bg-transparent border-none text-white text-base focus:ring-0 px-2"
                        :disabled="isLoading">
                    <button type="submit"
                        class="bg-[#68C7EC] text-[#000F0F] p-3 rounded-xl hover:brightness-110 transition-all disabled:opacity-50"
                        :disabled="isLoading || (newMessage.trim() === '' && !selectedFile)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </main>

    {{-- MODAL 1: HAPUS CHAT TUNGGAL --}}
    <div x-show="showDeleteModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-[#121212] border border-gray-800 rounded-2xl max-w-sm w-full p-6 shadow-2xl relative"
            @click.away="showDeleteModal = false">
            <div class="w-12 h-12 rounded-xl bg-red-950/40 border border-red-500/30 flex items-center justify-center text-red-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Hapus Obrolan Ini?</h3>
            <p class="text-xs text-gray-400 mb-6 leading-relaxed">
                Obrolan "<span class="text-gray-200 font-semibold" x-text="chatToDelete ? chatToDelete.title : ''"></span>" akan dihapus secara permanen dari riwayat Anda.
            </p>
            <div class="flex items-center justify-end gap-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 text-xs font-semibold text-gray-400 hover:text-white rounded-xl hover:bg-[#202020] transition-colors">
                    Batal
                </button>
                <button @click="executeDelete()" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-500 rounded-xl transition-all shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL 2: HAPUS SEMUA RIWAYAT --}}
    <div x-show="showClearAllModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-[#121212] border border-gray-800 rounded-2xl max-w-sm w-full p-6 shadow-2xl relative"
            @click.away="showClearAllModal = false">
            <div class="w-12 h-12 rounded-xl bg-red-950/40 border border-red-500/30 flex items-center justify-center text-red-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Hapus Semua Riwayat?</h3>
            <p class="text-xs text-gray-400 mb-6 leading-relaxed">
                Semua riwayat percakapan dengan AI akan dibersihkan dan tidak dapat dikembalikan.
            </p>
            <div class="flex items-center justify-end gap-3">
                <button @click="showClearAllModal = false" class="px-4 py-2 text-xs font-semibold text-gray-400 hover:text-white rounded-xl hover:bg-[#202020] transition-colors">
                    Batal
                </button>
                <button @click="executeClearAll()" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-500 rounded-xl transition-all shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                    Hapus Semua
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL 3: UBAH NAMA RIWAYAT CHAT --}}
    <div x-show="showRenameModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-[#121212] border border-gray-800 rounded-2xl max-w-sm w-full p-6 shadow-2xl relative"
            @click.away="showRenameModal = false">
            <h3 class="text-base font-bold text-white mb-1">Ubah Nama Obrolan</h3>
            <p class="text-xs text-gray-400 mb-4">Ganti nama obrolan agar lebih mudah diingat:</p>
            
            <input type="text"
                x-model="renameModalTitle"
                id="rename-modal-input"
                @keydown.enter.prevent="saveRenameModal()"
                class="w-full bg-[#181818] border border-gray-700 focus:border-[#68C7EC] text-white text-sm px-3.5 py-2.5 rounded-xl focus:outline-none mb-6">
            
            <div class="flex items-center justify-end gap-3">
                <button @click="showRenameModal = false" class="px-4 py-2 text-xs font-semibold text-gray-400 hover:text-white rounded-xl hover:bg-[#202020] transition-colors">
                    Batal
                </button>
                <button @click="saveRenameModal()" class="px-4 py-2 text-xs font-bold text-[#000F0F] bg-[#68C7EC] hover:brightness-110 rounded-xl transition-all">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
        function chatApp() {
            return {
                colorTheme: localStorage.getItem('color-theme') || 'dark',
                // Load history dari localStorage saat halaman dibuka
                chats: JSON.parse(localStorage.getItem('mystudy_ai_history')) || [],
                currentChatId: null,
                newMessage: '',
                isLoading: false,
                selectedFile: null,
                mobileSidebarOpen: false,
                searchQuery: '',

                // Dropdown menu state
                openDropdownId: null,

                // Rename modal state
                showRenameModal: false,
                renameTargetId: null,
                renameModalTitle: '',

                // Delete modal state
                showDeleteModal: false,
                chatToDelete: null,

                // Clear all modal state
                showClearAllModal: false,

                get currentChat() {
                    if (!this.currentChatId) return null;
                    return this.chats.find(c => c.id === this.currentChatId) || null;
                },

                get activeMessages() {
                    return this.currentChat ? this.currentChat.messages : [];
                },

                get filteredChats() {
                    if (!this.searchQuery.trim()) return this.chats;
                    const q = this.searchQuery.toLowerCase();
                    return this.chats.filter(c => (c.title || '').toLowerCase().includes(q));
                },

                get pinnedChats() {
                    return this.filteredChats.filter(c => Boolean(c.pinned));
                },

                get unpinnedChats() {
                    return this.filteredChats.filter(c => !Boolean(c.pinned));
                },

                // Fungsi ini otomatis berjalan saat halaman AI dimuat
                init() {
                    const params = new URLSearchParams(window.location.search);
                    const taskContext = params.get('task');

                    if (taskContext) {
                        // Jika datang dari tombol bintang di dashboard
                        this.currentChatId = Date.now();
                        this.chats.unshift({
                            id: this.currentChatId,
                            title: 'Bahas: ' + taskContext,
                            pinned: false,
                            messages: [
                                { role: 'assistant', content: `Mau selesaikan materi **${taskContext}** yang mana nih?` }
                            ]
                        });
                        this.saveHistory();

                        // Bersihkan URL agar saat direfresh tidak membuat chat baru terus
                        window.history.replaceState({}, document.title, window.location.pathname);
                    } else if (this.chats.length > 0) {
                        // Buka chat disematkan pertama atau chat terbaru
                        const pinned = this.chats.find(c => c.pinned);
                        this.currentChatId = pinned ? pinned.id : this.chats[0].id;
                    }

                    this.scrollToBottom();
                },

                saveHistory() {
                    localStorage.setItem('mystudy_ai_history', JSON.stringify(this.chats));
                },

                handleFileUpload(e) {
                    if (e.target.files.length > 0) {
                        this.selectedFile = e.target.files[0].name;
                    }
                },

                startNewChat() {
                    this.currentChatId = null;
                    this.newMessage = '';
                    this.selectedFile = null;
                    this.mobileSidebarOpen = false;
                    this.openDropdownId = null;
                },

                selectChat(id) {
                    this.currentChatId = id;
                    this.mobileSidebarOpen = false;
                    this.openDropdownId = null;
                    this.scrollToBottom();
                },

                // --- 1. PIN / SEMATKAN FITUR ---
                togglePin(id) {
                    const chat = this.chats.find(c => c.id === id);
                    if (chat) {
                        chat.pinned = !Boolean(chat.pinned);
                        this.saveHistory();
                    }
                },

                // --- 2. EDIT NAMA FITUR (MODAL) ---
                openRenameModal(chat) {
                    if (!chat) return;
                    this.openDropdownId = null;
                    this.renameTargetId = chat.id;
                    this.renameModalTitle = chat.title;
                    this.showRenameModal = true;
                    this.$nextTick(() => {
                        const input = document.getElementById('rename-modal-input');
                        if (input) {
                            input.focus();
                            input.select();
                        }
                    });
                },

                saveRenameModal() {
                    if (!this.renameModalTitle.trim() || !this.renameTargetId) return;
                    const chat = this.chats.find(c => c.id === this.renameTargetId);
                    if (chat) {
                        chat.title = this.renameModalTitle.trim();
                        this.saveHistory();
                    }
                    this.showRenameModal = false;
                    this.renameTargetId = null;
                    this.renameModalTitle = '';
                },

                // --- 3. HAPUS FITUR (SINGLE CHAT) ---
                confirmDelete(chat) {
                    this.openDropdownId = null;
                    this.chatToDelete = chat;
                    this.showDeleteModal = true;
                },

                executeDelete() {
                    if (!this.chatToDelete) return;
                    const id = this.chatToDelete.id;
                    this.chats = this.chats.filter(c => c.id !== id);
                    this.saveHistory();
                    if (this.currentChatId === id) {
                        this.currentChatId = this.chats.length > 0 ? this.chats[0].id : null;
                    }
                    this.showDeleteModal = false;
                    this.chatToDelete = null;
                },

                // --- 4. HAPUS SEMUA FITUR ---
                executeClearAll() {
                    this.chats = [];
                    this.saveHistory();
                    this.currentChatId = null;
                    this.showClearAllModal = false;
                },

                async sendMessage() {
                    if (this.newMessage.trim() === '' && !this.selectedFile) return;

                    const userText = this.newMessage;
                    const attached = this.selectedFile;

                    if (!this.currentChatId) {
                        this.currentChatId = Date.now();
                        this.chats.unshift({
                            id: this.currentChatId,
                            title: userText ? userText.substring(0, 30) + '...' : 'Pesan dengan Lampiran',
                            pinned: false,
                            messages: []
                        });
                    }

                    const currentChat = this.chats.find(c => c.id === this.currentChatId);
                    currentChat.messages.push({ role: 'user', content: userText, attachment: attached });
                    this.newMessage = '';
                    this.selectedFile = null;
                    this.isLoading = true;

                    // Simpan setelah user ngirim pesan
                    this.saveHistory();
                    this.scrollToBottom();

                    try {
                        const response = await fetch("{{ route('ai-chat.send') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ message: attached ? `[Lampiran: ${attached}] ` + userText : userText })
                        });
                        const data = await response.json();
                        currentChat.messages.push({ role: 'assistant', content: data.reply || 'Ada masalah server bro.' });
                    } catch (error) {
                        currentChat.messages.push({ role: 'assistant', content: 'Gagal terhubung ke server.' });
                    }

                    this.isLoading = false;

                    // Simpan lagi setelah AI membalas
                    this.saveHistory();
                    this.scrollToBottom();
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = document.getElementById('chat-container');
                        if (container) container.scrollTop = container.scrollHeight;
                    }, 50);
                },

                formatText(text) {
                    if (!text) return "";
                    if (window.marked) {
                        return marked.parse(text);
                    }
                    let formatted = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
                    return formatted.replace(/\n/g, '<br>');
                }
            }
        }
    </script>

</body>

</html>