<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pulihkan Kata Sandi - SmartDo</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-950 min-h-screen flex transition-colors duration-300">

    {{-- SISI KIRI: Branding Visual (Selaras dengan Login & Register) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 to-purple-700 p-12 text-white flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-indigo-400 opacity-20 rounded-full blur-2xl"></div>
        </div>

        <div class="relative z-10">
            <a href="/" class="font-extrabold text-3xl hover:scale-105 transition-transform inline-block">
                📝 SmartDo
            </a>
        </div>
        
        <div class="relative z-10 mb-10">
            <h1 class="text-4xl font-extrabold mb-6 leading-tight">
                Jangan Khawatir.<br>Kami Bantu Pulihkan Aksesmu.
            </h1>
            <p class="text-indigo-100 text-lg mb-8 max-w-md">
                Masukkan email yang terdaftar, dan kami akan mengirimkan instruksi aman untuk membuat kata sandi barumu.
            </p>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-indigo-50 font-medium">Proses Cepat & Aman</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-indigo-50 font-medium">Tautan Berbatas Waktu</span>
                </div>
            </div>
        </div>
        
        <div class="relative z-10 text-sm text-indigo-200">
            &copy; 2026 SmartDo App. Dibuat untuk Portofolio.
        </div>
    </div>

    {{-- SISI KANAN: Area Form Lupa Kata Sandi --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
        <div id="form-container" class="w-full max-w-md opacity-0 translate-y-4 transition-all duration-700 ease-out">
            
            {{-- Logo khusus versi Mobile --}}
            <div class="lg:hidden text-center mb-8">
                <a href="/" class="font-extrabold text-3xl text-indigo-600 dark:text-indigo-400">📝 SmartDo</a>
            </div>

            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Lupa Kata Sandi?</h2>
                <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Tidak masalah. Cukup masukkan alamat emailmu dan kami akan mengirimkan tautan pemulihan agar kamu bisa membuat kata sandi baru.
                </p>
            </div>

            {{-- Status Session --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Alamat Email Terdaftar</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="block w-full mt-1 px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-3.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-xl font-bold text-base transition-all shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5">
                        Kirim Tautan Pemulihan
                    </button>
                </div>

                {{-- Kembali ke Login --}}
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline transition-all">
                        &larr; Kembali ke halaman Masuk
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Animasi --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                const formContainer = document.getElementById('form-container');
                formContainer.classList.remove('opacity-0', 'translate-y-4');
                formContainer.classList.add('opacity-100', 'translate-y-0');
            }, 100);
        });
    </script>
</body>
</html>