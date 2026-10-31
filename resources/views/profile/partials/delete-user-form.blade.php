<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Setelah akun Anda dihapus, semua data tugas, riwayat belajar, dan pengaturan di MyStudy akan dihapus secara permanen.
        </p>
    </header>

    <div x-data="{ confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
        {{-- Tombol Pemicu Modal --}}
        <button type="button" @click="confirmingUserDeletion = true"
            class="px-5 py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/30 font-bold rounded-xl text-sm transition-all">
            Hapus Akun Saya
        </button>

        {{-- MODAL KONFIRMASI HAPUS AKUN --}}
        <div x-show="confirmingUserDeletion" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#000F0F]/80 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div @click.away="confirmingUserDeletion = false"
                class="w-full max-w-md bg-white dark:bg-[#001818] border border-red-500/30 rounded-3xl p-6 shadow-2xl relative space-y-4">
                
                {{-- Header Modal --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Hapus Akun</h3>
                        <p class="text-xs text-red-400 font-semibold">Tindakan ini tidak dapat dibatalkan!</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    Apakah Anda yakin ingin menghapus akun? Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa ini benar-benar Anda.
                </p>

                {{-- Form Konfirmasi Kata Sandi --}}
                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password" class="sr-only">Kata Sandi</label>
                        <input id="password" name="password" type="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-[#002525] bg-gray-50 dark:bg-[#000F0F] text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm placeholder-gray-400"
                            placeholder="Masukkan kata sandi Anda...">
                        @if($errors->userDeletion->has('password'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="confirmingUserDeletion = false"
                            class="px-4 py-2.5 bg-gray-100 dark:bg-[#002525] hover:bg-gray-200 dark:hover:bg-[#003030] text-gray-700 dark:text-gray-300 font-bold rounded-xl text-sm transition-all">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-red-500/20">
                            Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>