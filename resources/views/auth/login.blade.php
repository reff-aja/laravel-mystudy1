<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SmartDo</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-[#000F0F] min-h-screen flex transition-colors duration-300">

    {{-- SISI KIRI: Branding Visual (Selaras dengan Register) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#002525] to-[#000F0F] p-12 text-white flex-col justify-between relative overflow-hidden border-r border-[#002525]">
        {{-- Efek Lingkaran Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#68C7EC] opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-[#68C7EC] opacity-10 rounded-full blur-2xl"></div>
        </div>

        <div class="relative z-10">
            <a href="/" class="font-extrabold text-3xl hover:scale-105 transition-transform inline-block text-[#68C7EC]">
                📝 SmartDo
            </a>
        </div>
        
        <div class="relative z-10 mb-10">
            <h1 class="text-4xl font-extrabold mb-6 leading-tight">
                Selamat Datang Kembali.<br>Lanjutkan Produktivitasmu.
            </h1>
            <p class="text-gray-300 text-lg mb-8 max-w-md">
                Daftar tugasmu sudah menanti. Masuk sekarang dan selesaikan apa yang sudah kamu mulai hari ini.
            </p>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#68C7EC]/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-gray-200 font-medium">100% Gratis Selamanya</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#68C7EC]/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-gray-200 font-medium">Privasi Data Terjamin</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#68C7EC]/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#68C7EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-gray-200 font-medium">Bebas Iklan & Gangguan</span>
                </div>
            </div>
        </div>
        
        <div class="relative z-10 text-sm text-gray-400">
            &copy; 2026 SmartDo App. Dibuat untuk Portofolio.
        </div>
    </div>

    {{-- SISI KANAN: Area Form Login --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white dark:bg-[#000F0F]">
        <div id="form-container" class="w-full max-w-md opacity-0 translate-y-4 transition-all duration-700 ease-out">
            
            {{-- Logo khusus versi Mobile --}}
            <div class="lg:hidden text-center mb-8">
                <a href="/" class="font-extrabold text-3xl text-[#68C7EC]">📝 SmartDo</a>
            </div>

            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Masuk ke Akun</h2>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Masukkan email dan kata sandimu untuk melanjutkan.</p>
            </div>

            {{-- Status Session (misal reset password berhasil) --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                           class="block w-full mt-1 px-4 py-3 bg-gray-50 dark:bg-[#001818] border border-gray-200 dark:border-[#002525] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#68C7EC] focus:border-[#68C7EC] transition-colors outline-none">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Kata Sandi --}}
                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" 
                           class="block w-full mt-1 px-4 py-3 bg-gray-50 dark:bg-[#001818] border border-gray-200 dark:border-[#002525] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#68C7EC] focus:border-[#68C7EC] transition-colors outline-none">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ingat Saya & Lupa Kata Sandi --}}
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 dark:border-[#002525] text-[#68C7EC] shadow-sm focus:ring-[#68C7EC] dark:focus:ring-offset-[#000F0F] dark:bg-[#001818]">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#68C7EC] hover:underline transition-all">
                            Lupa Kata Sandi?
                        </a>
                    @endif
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-3.5 bg-[#68C7EC] hover:opacity-90 text-[#000F0F] rounded-xl font-bold text-base transition-all shadow-lg hover:shadow-[#68C7EC]/20 hover:-translate-y-0.5">
                        Masuk Sekarang
                    </button>
                </div>

                {{-- Tautan Daftar --}}
                <div class="text-center mt-8">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="font-bold text-[#68C7EC] hover:underline transition-all">
                            Daftar gratis di sini
                        </a>
                    </p>
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