<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="chatApp()" :class="colorTheme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Assistant - MyStudy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #68C7EC;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-100 bg-[#000000] h-screen flex overflow-hidden">

    {{-- SIDEBAR HISTORY --}}
    <aside class="w-72 bg-[#0a0a0a] border-r border-gray-800 flex flex-col hidden md:flex h-full shrink-0">
        <div class="p-4 border-b border-gray-800 flex items-center justify-between">
            <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-400 hover:text-[#68C7EC] transition-colors">&larr; Dashboard</a>
            <button @click="startNewChat()"
                class="text-[#000F0F] bg-[#68C7EC] hover:brightness-110 px-3 py-2 rounded-lg text-sm font-bold flex items-center gap-2 shadow-[0_0_10px_rgba(104,199,236,0.2)] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Chat Baru
            </button>
        </div>
        <div class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Riwayat Chat</div>
        <div class="flex-1 overflow-y-auto px-2 space-y-1">
            <template x-for="chat in chats" :key="chat.id">
                <button @click="selectChat(chat.id)"
                    :class="currentChatId === chat.id ? 'bg-[#202020] text-white border-[#68C7EC]/50' : 'text-gray-400 hover:bg-[#121212] border-transparent'"
                    class="w-full text-left p-3 rounded-xl text-sm truncate transition-all flex items-center gap-3 border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="shrink-0"
                        :class="currentChatId === chat.id ? 'text-[#68C7EC]' : 'text-gray-500'" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span x-text="chat.title"></span>
                </button>
            </template>
            <div x-show="chats.length === 0" class="text-center text-xs text-gray-600 mt-4">Belum ada riwayat</div>
        </div>
    </aside>

    {{-- AREA UTAMA CHAT --}}
    <main class="flex-1 flex flex-col h-full bg-[#000000] relative">
        <header
            class="shrink-0 bg-[#000000]/80 backdrop-blur-md border-b border-gray-800 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-[#202020] flex items-center justify-center border border-[#68C7EC]/30">
                    🤖</div>
                <div>
                    <h1 class="font-bold text-lg text-white leading-tight">MyStudy AI</h1>
                    <p class="text-xs text-[#68C7EC]">Model: Teks Super Cerdas</p>
                </div>
            </div>
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
                        Halo bro! Aku MyStudy AI. Mau bahas apa hari ini? Kamu bisa mulai obrolan baru kapan saja!
                    </div>
                </div>

                <template x-for="(msg, index) in activeMessages" :key="index">
                    <div class="flex gap-4" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                        <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-sm border"
                            :class="msg.role === 'user' ? 'bg-[#68C7EC] text-[#000F0F] border-[#68C7EC]' : 'bg-[#202020] border-[#68C7EC]/30'">
                            <span x-text="msg.role === 'user' ? '👨‍🎓' : '🤖'"></span>
                        </div>

                        <div class="p-4 md:p-5 rounded-2xl max-w-[85%] text-sm md:text-base leading-relaxed"
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

                get activeMessages() {
                    if (!this.currentChatId) return [];
                    const chat = this.chats.find(c => c.id === this.currentChatId);
                    return chat ? chat.messages : [];
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
                            messages: [
                                { role: 'assistant', content: `Mau selesaikan materi **${taskContext}** yang mana nih?` }
                            ]
                        });
                        this.saveHistory();

                        // Bersihkan URL agar saat direfresh tidak membuat chat baru terus
                        window.history.replaceState({}, document.title, window.location.pathname);
                    } else if (this.chats.length > 0) {
                        // Buka chat terakhir jika tidak ada tugas spesifik
                        this.currentChatId = this.chats[0].id;
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
                },

                selectChat(id) {
                    this.currentChatId = id;
                    this.scrollToBottom();
                },

                async sendMessage() {
                    if (this.newMessage.trim() === '' && !this.selectedFile) return;

                    const userText = this.newMessage;
                    const attached = this.selectedFile;

                    if (!this.currentChatId) {
                        this.currentChatId = Date.now();
                        this.chats.unshift({
                            id: this.currentChatId,
                            title: userText ? userText.substring(0, 25) + '...' : 'Pesan dengan Lampiran',
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
                    let formatted = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
                    return formatted.replace(/\n/g, '<br>');
                }
            }
        }
    </script>

</body>

</html>