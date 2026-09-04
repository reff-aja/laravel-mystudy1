<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Assistant - SmartDo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-[#000F0F] text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center transition-colors duration-300">
    
    <div class="max-w-md w-full text-center p-8 bg-white dark:bg-[#001818] rounded-3xl shadow-xl border border-gray-100 dark:border-[#002525] relative overflow-hidden">
        {{-- Efek Glow --}}
        <div class="absolute -top-20 -left-20 w-60 h-60 bg-[#68C7EC] opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-60 h-60 bg-[#68C7EC] opacity-10 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="w-20 h-20 mx-auto bg-[#68C7EC]/10 text-[#68C7EC] rounded-2xl flex items-center justify-center mb-6 shadow-inner border border-[#68C7EC]/20">
                <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            
            <h1 class="text-3xl font-extrabold mb-2 text-[#68C7EC]">
                SmartDo AI Assistant
            </h1>
            <h2 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">Coming Soon 🚀</h2>
            
            <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed text-sm">
                Asisten AI cerdasmu sedang berlatih. Nanti kamu bisa berdiskusi, meminta saran pemecahan tugas, dan meningkatkan produktivitasmu di sini!
            </p>

            <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 bg-[#68C7EC] text-[#000F0F] rounded-xl font-bold transition-transform hover:scale-105 shadow-md hover:opacity-90">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>