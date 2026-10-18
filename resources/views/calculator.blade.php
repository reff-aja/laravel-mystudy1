<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ colorTheme: localStorage.getItem('color-theme') || 'dark' }" :class="colorTheme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kalkulator Scientific - MyStudy</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Math.js CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.11.0/math.min.js"></script>
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes modernLine {
            0% { left: -30%; width: 30%; }
            50% { left: 30%; width: 70%; }
            100% { left: 100%; width: 30%; }
        }
        .animate-modern-line { animation: modernLine 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite; }
        .preloader-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>
<body class="font-sans antialiased text-gray-100 bg-[#000000] min-h-screen">

    {{-- Preloader Futuristik MyStudy --}}
    <div id="mystudy-preloader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white dark:bg-[#000F0F] transition-all duration-700 ease-in-out">
        <div class="relative flex justify-center items-center mb-8">
            <h1 class="text-4xl font-extrabold text-[#68C7EC] animate-pulse">MyStudy</h1>
        </div>
        <div class="w-64 h-1.5 bg-gray-200 dark:bg-[#002525] rounded-full overflow-hidden relative shadow-inner">
            <div class="absolute top-0 h-full bg-[#68C7EC] rounded-full animate-modern-line shadow-[0_0_10px_#68C7EC]"></div>
        </div>
        <p class="mt-4 text-xs font-bold tracking-widest text-gray-400 dark:text-gray-500 uppercase">
            Memuat Kalkulator...
        </p>
    </div>

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 bg-[#000000]/80 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-md mx-auto px-4">
            <div class="flex justify-between h-14 items-center">
                <a href="{{ route('dashboard') }}" class="font-bold text-lg text-white hover:text-gray-300">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </nav>

    {{-- Konten Utama Kalkulator --}}
    <main class="max-w-md mx-auto h-[calc(100vh-3.5rem)] flex flex-col justify-end" x-data="calcApp()">
        
        <!-- Header Menu -->
        <div class="flex justify-center items-center gap-6 pt-4 pb-8 w-full">
            <span class="text-white font-semibold text-lg border-b-2 border-[#68C7EC] pb-1">Kalkulator</span>
            <div class="absolute right-4 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </div>
        </div>

        <!-- Layar Kalkulator -->
        <div class="px-6 flex flex-col justify-end items-end pb-4 flex-grow w-full">
            <div class="text-gray-400 text-xl tracking-wider min-h-[30px] w-full text-right overflow-hidden" x-text="expr"></div>
            <div class="text-[5rem] leading-none font-medium text-white w-full text-right overflow-hidden break-all h-[90px] flex justify-end items-center" x-text="result !== '' ? result : (currentInput !== '' ? currentInput : '0')"></div>
        </div>

        <!-- Area Tombol -->
        <div class="bg-[#000000] px-4 pb-8 w-full rounded-t-3xl">
            
            {{-- ========================================== --}}
            {{-- 1. MODE SCIENTIFIC (5 KOLOM, 7 BARIS) --}}
            {{-- ========================================== --}}
            <div x-show="isScientific" class="grid grid-cols-5 gap-2 gap-y-3" style="display: none;" x-transition>
                
                {{-- Baris 1: Fitur 2nd dan Trigonometri --}}
                <button @click="toggleMode()" :class="is2nd ? 'text-[#68C7EC]' : 'text-gray-300'" class="bg-[#202020] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors">2nd</button>
                
                <!-- Tombol Deg -->
                <button @click="if(!is2nd) toggleDeg()" :class="is2nd ? 'bg-[#121212] text-gray-600 cursor-not-allowed' : 'bg-[#202020] text-gray-300 hover:bg-[#303030]'" class="h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors flex items-center justify-center" x-text="isDeg ? 'deg' : 'rad'"></button>
                
                <button @click="appendFunction(is2nd ? 'sin⁻¹' : 'sin')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors" x-text="is2nd ? 'sin⁻¹' : 'sin'"></button>
                <button @click="appendFunction(is2nd ? 'cos⁻¹' : 'cos')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors" x-text="is2nd ? 'cos⁻¹' : 'cos'"></button>
                <button @click="appendFunction(is2nd ? 'tan⁻¹' : 'tan')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors" x-text="is2nd ? 'tan⁻¹' : 'tan'"></button>

                {{-- Baris 2 --}}
                <button @click="appendOperator('^')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors">x<sup class="text-xs">y</sup></button>
                <button @click="appendFunction('log')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors">lg</button>
                <button @click="appendFunction('ln')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-base transition-colors">In</button>
                <button @click="appendNumber('(')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors">(</button>
                <button @click="appendNumber(')')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors">)</button>

                {{-- Baris 3 --}}
                <button @click="appendFunction('sqrt')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-xl transition-colors">√x</button>
                <button @click="clearAll()" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors">AC</button>
                <button @click="del()" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                </button>
                <button @click="appendOperator('%')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-xl transition-colors">%</button>
                <button @click="appendOperator('÷')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-2xl transition-colors">÷</button>

                {{-- Baris 4 --}}
                <button @click="appendOperator('!')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors">x!</button>
                <button @click="appendNumber('7')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">7</button>
                <button @click="appendNumber('8')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">8</button>
                <button @click="appendNumber('9')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">9</button>
                <button @click="appendOperator('×')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-2xl pb-1 transition-colors">×</button>

                {{-- Baris 5 --}}
                <button @click="appendFunction('inv')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-lg transition-colors"><sup>1</sup>/<sub class="text-xs">x</sub></button>
                <button @click="appendNumber('4')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">4</button>
                <button @click="appendNumber('5')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">5</button>
                <button @click="appendNumber('6')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">6</button>
                <button @click="appendOperator('-')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-3xl pb-1 transition-colors">-</button>

                {{-- Baris 6 --}}
                <button @click="appendNumber('π')" class="bg-[#202020] text-gray-300 hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-xl transition-colors">π</button>
                <button @click="appendNumber('1')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">1</button>
                <button @click="appendNumber('2')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">2</button>
                <button @click="appendNumber('3')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">3</button>
                <button @click="appendOperator('+')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-medium text-2xl transition-colors">+</button>

                {{-- Baris 7 --}}
                <button @click="toggleUI()" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] transition-colors flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                </button>
                <button @click="appendNumber('e')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-xl transition-colors">e</button>
                <button @click="appendNumber('0')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">0</button>
                <button @click="appendNumber('.')" class="bg-[#202020] text-white hover:bg-[#303030] h-[3.8rem] rounded-[1.2rem] font-normal text-2xl transition-colors">,</button>
                <!-- Tombol Sama Dengan diubah jadi Pale Azure -->
                <button @click="calculate()" class="bg-[#68C7EC] text-[#000F0F] hover:brightness-110 h-[3.8rem] rounded-[1.2rem] font-bold text-2xl transition-all shadow-[0_0_15px_rgba(104,199,236,0.3)] flex items-center justify-center">=</button>
            </div>

            {{-- ========================================== --}}
            {{-- 2. MODE BASIC (4 KOLOM, 5 BARIS) --}}
            {{-- ========================================== --}}
            <div x-show="!isScientific" class="grid grid-cols-4 gap-3 gap-y-4 px-2" x-transition>
                
                {{-- Baris 1 --}}
                <button @click="clearAll()" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-xl transition-colors">AC</button>
                <button @click="del()" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-xl transition-colors flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                </button>
                <button @click="appendOperator('%')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-2xl transition-colors">%</button>
                <button @click="appendOperator('÷')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-3xl transition-colors">÷</button>

                {{-- Baris 2 --}}
                <button @click="appendNumber('7')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">7</button>
                <button @click="appendNumber('8')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">8</button>
                <button @click="appendNumber('9')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">9</button>
                <button @click="appendOperator('×')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-3xl pb-1 transition-colors">×</button>

                {{-- Baris 3 --}}
                <button @click="appendNumber('4')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">4</button>
                <button @click="appendNumber('5')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">5</button>
                <button @click="appendNumber('6')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">6</button>
                <button @click="appendOperator('-')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-4xl pb-1 transition-colors">-</button>

                {{-- Baris 4 --}}
                <button @click="appendNumber('1')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">1</button>
                <button @click="appendNumber('2')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">2</button>
                <button @click="appendNumber('3')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">3</button>
                <button @click="appendOperator('+')" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-medium text-3xl transition-colors">+</button>

                {{-- Baris 5 --}}
                <button @click="toggleUI()" class="bg-[#202020] text-[#68C7EC] hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] transition-colors flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                </button>
                <button @click="appendNumber('0')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">0</button>
                <button @click="appendNumber('.')" class="bg-[#202020] text-white hover:bg-[#303030] h-[4.5rem] rounded-[1.5rem] font-normal text-3xl transition-colors">,</button>
                <button @click="calculate()" class="bg-[#68C7EC] text-[#000F0F] hover:brightness-110 h-[4.5rem] rounded-[1.5rem] font-bold text-3xl transition-all shadow-[0_0_15px_rgba(104,199,236,0.3)] flex items-center justify-center">=</button>
            </div>

        </div>
    </main>

    {{-- Script Logika --}}
    <script>
        window.addEventListener('load', function () {
            setTimeout(() => { document.getElementById('mystudy-preloader').classList.add('preloader-hidden'); }, 5000);
        });

        function calcApp() {
            return {
                expr: '',
                currentInput: '',
                result: '',
                isDeg: true,
                isCalculated: false,
                isScientific: true,
                is2nd: false, 

                toggleUI() {
                    this.isScientific = !this.isScientific;
                },

                toggleMode() {
                    this.is2nd = !this.is2nd; 
                },

                toggleDeg() {
                    this.isDeg = !this.isDeg;
                },

                appendNumber(val) {
                    if (this.isCalculated) {
                        this.currentInput = '';
                        this.expr = '';
                        this.result = '';
                        this.isCalculated = false;
                    }
                    if (val === ',') val = '.'; 
                    this.currentInput += val;
                },

                appendOperator(val) {
                    if (this.isCalculated) {
                        this.currentInput = this.result;
                        this.expr = '';
                        this.result = '';
                        this.isCalculated = false;
                    }
                    if (this.currentInput !== '') {
                        this.currentInput += val;
                    }
                },

                appendFunction(funcName) {
                    if (this.isCalculated) {
                        this.currentInput = '';
                        this.expr = '';
                        this.result = '';
                        this.isCalculated = false;
                    }
                    if (funcName === 'inv') {
                        this.currentInput += '1/(';
                    } else {
                        this.currentInput += funcName + '(';
                    }
                },

                clearAll() {
                    this.expr = '';
                    this.currentInput = '';
                    this.result = '';
                    this.isCalculated = false;
                },

                del() {
                    if (this.isCalculated) {
                        this.clearAll();
                        return;
                    }
                    this.currentInput = this.currentInput.toString().slice(0, -1);
                },

                calculate() {
                    if (!this.currentInput) return;
                    try {
                        let formattedExpr = this.currentInput
                            .replace(/×/g, '*')
                            .replace(/÷/g, '/')
                            .replace(/π/g, 'pi')
                            .replace(/%/g, '/100')
                            .replace(/sin⁻¹/g, 'asin')
                            .replace(/cos⁻¹/g, 'acos')
                            .replace(/tan⁻¹/g, 'atan');

                        const scope = {
                            sin: (x) => this.isDeg ? Math.sin(x * Math.PI / 180) : Math.sin(x),
                            cos: (x) => this.isDeg ? Math.cos(x * Math.PI / 180) : Math.cos(x),
                            tan: (x) => this.isDeg ? Math.tan(x * Math.PI / 180) : Math.tan(x),
                            asin: (x) => this.isDeg ? Math.asin(x) * 180 / Math.PI : Math.asin(x),
                            acos: (x) => this.isDeg ? Math.acos(x) * 180 / Math.PI : Math.acos(x),
                            atan: (x) => this.isDeg ? Math.atan(x) * 180 / Math.PI : Math.atan(x),
                            log: (x) => Math.log10(x),
                            ln: (x) => Math.log(x),
                            sqrt: (x) => Math.sqrt(x),
                            pi: Math.PI,
                            e: Math.E
                        };

                        let res = math.evaluate(formattedExpr, scope);
                        
                        this.result = math.format(res, { precision: 10 });
                        this.expr = this.currentInput + '= ' + this.result;
                        this.isCalculated = true;

                    } catch (e) {
                        this.result = 'Error';
                    }
                }
            }
        }
    </script>
</body>
</html>