<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>AI Assistant - MyStudy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 font-sans antialiased text-gray-900 transition-colors duration-300 dark:bg-[#000F0F] dark:text-gray-100">
    <nav class="sticky top-0 z-20 border-b border-gray-200 bg-white/80 backdrop-blur-md dark:border-[#002525] dark:bg-[#000F0F]/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-extrabold text-[#68C7EC] transition-opacity hover:opacity-80 sm:text-2xl">
                MyStudy
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg px-2 py-2 text-sm font-semibold text-gray-600 transition-colors hover:text-[#1688AE] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#68C7EC] dark:text-gray-300 dark:hover:text-[#68C7EC]">
                <span aria-hidden="true">&larr;</span>
                <span class="hidden sm:inline">Kembali ke Dashboard</span>
                <span class="sm:hidden">Dashboard</span>
            </a>
        </div>
    </nav>

    <main class="relative isolate flex min-h-[calc(100vh-4rem)] items-center justify-center px-4 py-10 sm:px-6 sm:py-16">
        <div aria-hidden="true" class="pointer-events-none absolute left-1/2 top-1/2 -z-10 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#68C7EC]/10 blur-3xl sm:h-96 sm:w-96"></div>

        <section aria-labelledby="ai-chat-title" class="relative w-full max-w-2xl overflow-hidden rounded-3xl border border-gray-100 bg-white px-5 py-8 text-center shadow-xl dark:border-[#002525] dark:bg-[#001818] sm:px-10 sm:py-12 lg:px-14">
            <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-24 h-56 w-56 rounded-full bg-[#68C7EC]/10 blur-3xl"></div>
            <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 -left-20 h-56 w-56 rounded-full bg-[#68C7EC]/10 blur-3xl"></div>

            <div class="relative">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl border border-[#68C7EC]/20 bg-[#68C7EC]/10 text-[#68C7EC] shadow-inner sm:mb-8 sm:h-20 sm:w-20">
                    <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>

                <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-[#1688AE] dark:text-[#68C7EC]">MyStudy AI</p>
                <h1 id="ai-chat-title" class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white sm:text-3xl">
                    MyStudy AI Assistant
                </h1>

                <div class="my-5 inline-flex items-center gap-2 rounded-full border border-[#68C7EC]/20 bg-[#68C7EC]/10 px-4 py-2 text-sm font-bold text-[#1688AE] dark:text-[#68C7EC] sm:my-6">
                    <span aria-hidden="true" class="h-2 w-2 rounded-full bg-[#68C7EC]"></span>
                    <h2>Coming Soon <span aria-hidden="true">🚀</span></h2>
                </div>

                <p class="mx-auto max-w-lg text-sm leading-6 text-gray-600 dark:text-gray-400 sm:text-base sm:leading-7">
                    Asisten AI cerdasmu sedang berlatih. Nanti kamu bisa berdiskusi, meminta saran pemecahan tugas, dan meningkatkan produktivitasmu di sini!
                </p>

                <a href="{{ route('dashboard') }}" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#68C7EC] px-6 py-3.5 font-bold text-[#000F0F] shadow-md transition hover:scale-[1.02] hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1688AE] focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-[#001818] sm:mt-10 sm:w-auto">
                    <span aria-hidden="true">&larr;</span>
                    Kembali ke Dashboard
                </a>
            </div>
        </section>
    </main>
</body>

</html>
