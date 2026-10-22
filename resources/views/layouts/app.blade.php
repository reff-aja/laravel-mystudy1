<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Script Anti-Kedip Dark Mode --}}
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Math.js Engine[cite: 1] -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Tambahkan transisi warna global pada pembungkus utama --}}
        <div class="min-h-screen bg-gray-100 dark:bg-gray-950 transition-colors duration-300">
            
            {{-- Navigasi Bawaan Breeze dengan tambahan class dark: --}}
            @include('layouts.navigation')

            <!-- Page Heading (Header dengan dukungan Dark Mode) -->
            @isset($header)
                <header class="bg-white dark:bg-gray-900 shadow dark:border-b dark:border-gray-800 transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}

                <!-- Komponen Kalkulator Alpine.js[cite: 1] -->
                <div x-data="calculatorData()" class="max-w-3xl mx-auto mt-8 p-6 bg-white dark:bg-[#000F0F] rounded-xl shadow-lg border border-gray-200 dark:border-gray-800">
                    
                    <!-- Header & Toggles[cite: 1] -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-[#68C7EC]">Calculator</h2>
                        <div class="flex space-x-2">
                            <button @click="toggleMode()" class="px-3 py-1 text-sm rounded bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-700">
                                <span x-text="mode === 'scientific' ? 'Basic Mode' : 'Scientific Mode'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Display -->
                    <div class="w-full bg-gray-100 dark:bg-[#000000] rounded mb-4 p-4 text-right">
                        <div class="text-gray-500 dark:text-gray-400 text-sm min-h-[1.25rem]" x-text="expression"></div>
                        <div class="text-3xl font-semibold text-gray-800 dark:text-white overflow-hidden" x-text="currentInput || '0'"></div>
                    </div>

                    <!-- Mode Scientific Toggles (2nd & DEG/RAD)[cite: 1] -->
                    <div x-show="mode === 'scientific'" class="flex justify-between mb-4">
                        <button @click="is2nd = !is2nd" 
                                :class="is2nd ? 'bg-[#68C7EC] text-[#000F0F]' : 'bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-white'"
                                class="px-4 py-2 rounded font-medium transition-colors">
                            2nd
                        </button>
                        <button @click="if(!is2nd) angleMode = (angleMode === 'deg' ? 'rad' : 'deg')"
                                :class="is2nd ? 'opacity-50 cursor-not-allowed bg-gray-800' : 'bg-gray-200 dark:bg-gray-800'"
                                class="px-4 py-2 rounded font-medium text-gray-800 dark:text-white transition-colors">
                            <span x-text="angleMode.toUpperCase()"></span>
                        </button>
                    </div>

                    <!-- Keypad Grid[cite: 1] -->
                    <div :class="mode === 'scientific' ? 'grid-cols-5' : 'grid-cols-4'" class="grid gap-2">
                        
                        <!-- Fungsi Scientific (Hanya tampil jika mode == scientific) -->
                        <template x-if="mode === 'scientific'">
                            <div class="contents">
                                <button @click="append('sin(')" class="calc-btn dark:bg-gray-900" x-text="is2nd ? 'sin⁻¹' : 'sin'"></button>
                                <button @click="append('cos(')" class="calc-btn dark:bg-gray-900" x-text="is2nd ? 'cos⁻¹' : 'cos'"></button>
                                <button @click="append('tan(')" class="calc-btn dark:bg-gray-900" x-text="is2nd ? 'tan⁻¹' : 'tan'"></button>
                                <button @click="append('log(')" class="calc-btn dark:bg-gray-900">log</button>
                                <button @click="append('!')" class="calc-btn dark:bg-gray-900">x!</button>
                                
                                <button @click="append('sqrt(')" class="calc-btn dark:bg-gray-900">√</button>
                                <button @click="append('^')" class="calc-btn dark:bg-gray-900">xⁿ</button>
                                <button @click="append('pi')" class="calc-btn dark:bg-gray-900">π</button>
                                <button @click="append('(')" class="calc-btn dark:bg-gray-900">(</button>
                                <button @click="append(')')" class="calc-btn dark:bg-gray-900">)</button>
                            </div>
                        </template>

                        <!-- Basic Keypad[cite: 1] -->
                        <button @click="clearAll()" class="col-span-2 calc-btn bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">AC</button>
                        <button @click="deleteLast()" class="calc-btn dark:bg-gray-800">DEL</button>
                        <button @click="append('/')" class="calc-btn text-[#68C7EC] dark:bg-gray-900">÷</button>

                        <button @click="append('7')" class="calc-btn dark:bg-gray-800">7</button>
                        <button @click="append('8')" class="calc-btn dark:bg-gray-800">8</button>
                        <button @click="append('9')" class="calc-btn dark:bg-gray-800">9</button>
                        <button @click="append('*')" class="calc-btn text-[#68C7EC] dark:bg-gray-900">×</button>

                        <button @click="append('4')" class="calc-btn dark:bg-gray-800">4</button>
                        <button @click="append('5')" class="calc-btn dark:bg-gray-800">5</button>
                        <button @click="append('6')" class="calc-btn dark:bg-gray-800">6</button>
                        <button @click="append('-')" class="calc-btn text-[#68C7EC] dark:bg-gray-900">-</button>

                        <button @click="append('1')" class="calc-btn dark:bg-gray-800">1</button>
                        <button @click="append('2')" class="calc-btn dark:bg-gray-800">2</button>
                        <button @click="append('3')" class="calc-btn dark:bg-gray-800">3</button>
                        <button @click="append('+')" class="calc-btn text-[#68C7EC] dark:bg-gray-900">+</button>

                        <button @click="append('0')" class="col-span-2 calc-btn dark:bg-gray-800">0</button>
                        <button @click="append('.')" class="calc-btn dark:bg-gray-800">.</button>
                        <button @click="calculate()" class="calc-btn bg-[#68C7EC] text-[#000F0F] hover:bg-opacity-90">=</button>
                    </div>
                </div>

                <!-- Alpine.js Logic Script[cite: 1] -->
                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('calculatorData', () => ({
                            mode: 'basic',
                            is2nd: false,
                            angleMode: 'deg',
                            expression: '',
                            currentInput: '',

                            toggleMode() {
                                this.mode = this.mode === 'scientific' ? 'basic' : 'scientific';
                            },
                            append(val) {
                                this.currentInput += val;
                            },
                            clearAll() {
                                this.expression = '';
                                this.currentInput = '';
                            },
                            deleteLast() {
                                this.currentInput = this.currentInput.toString().slice(0, -1);
                            },
                            calculate() {
                                try {
                                    this.expression = this.currentInput + ' =';
                                    let evalStr = this.currentInput;
                                    
                                    // Mengonversi derajat ke radian jika mode DEG aktif sebelum dieksekusi oleh math.js[cite: 1]
                                    if (this.angleMode === 'deg') {
                                        evalStr = evalStr.replace(/sin\(/g, 'sin(pi/180 * ');
                                        evalStr = evalStr.replace(/cos\(/g, 'cos(pi/180 * ');
                                        evalStr = evalStr.replace(/tan\(/g, 'tan(pi/