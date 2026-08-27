<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SmartDo Dashboard') }}
        </h2>
    </x-slot>

    {{-- Pastikan pembungkus utamanya memiliki background untuk terang dan gelap --}}
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/40 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Form Tambah Tugas --}}
                    <form action="{{ route('tasks.store') }}" method="POST" class="mb-8 flex gap-4">
                        @csrf
                        <input type="text" name="title" required 
                            class="border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            placeholder="Ketik tugas barumu di sini...">
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition">
                            + Tambah
                        </button>
                    </form>

                    @error('title')
                        <p class="text-red-500 text-xs mt-1 mb-4">{{ $message }}</p>
                    @enderror

                    {{-- Daftar Tugas & Filter --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tugas Kamu:</h3>
                        
                        {{-- Tombol Tab Filter --}}
                        <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                            <a href="{{ route('dashboard') }}" 
                               class="px-3 py-1 text-sm rounded-md transition {{ $currentFilter === 'all' ? 'bg-white dark:bg-gray-600 shadow text-indigo-700 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-300 hover:text-gray-700' }}">
                               Semua
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'active']) }}" 
                               class="px-3 py-1 text-sm rounded-md transition {{ $currentFilter === 'active' ? 'bg-white dark:bg-gray-600 shadow text-indigo-700 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-300 hover:text-gray-700' }}">
                               Aktif
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'completed']) }}" 
                               class="px-3 py-1 text-sm rounded-md transition {{ $currentFilter === 'completed' ? 'bg-white dark:bg-gray-600 shadow text-indigo-700 dark:text-indigo-300 font-semibold' : 'text-gray-500 dark:text-gray-300 hover:text-gray-700' }}">
                               Selesai
                            </a>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach ($tasks as $task)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg transition-colors duration-300">
                                
                                <form action="{{ route('tasks.update', $task) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" onchange="this.form.submit()" class="rounded border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-600 text-indigo-600 cursor-pointer shadow-sm focus:ring-indigo-500"
                                        {{ $task->is_completed ? 'checked' : '' }}>
                                    
                                    <span class="{{ $task->is_completed ? 'line-through text-gray-400 dark:text-gray-400' : 'text-gray-800 dark:text-gray-200' }}">
                                        {{ $task->title }}
                                    </span>
                                </form>

                                <div class="flex gap-2">
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-sm px-3 py-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded hover:bg-red-200 dark:hover:bg-red-900/60 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach

                        @if ($tasks->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                {{ $currentFilter === 'all' ? 'Belum ada tugas. Yuk, tambah satu!' : 'Tidak ada tugas di kategori ini.' }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            if(!themeToggleBtn) return;

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
        });
    </script>
</x-app-layout>