<div data-page-skeleton role="status" aria-label="Memuat halaman"
    class="fixed inset-0 z-[9999] overflow-auto bg-gray-50 dark:bg-[#000F0F] text-gray-900 dark:text-gray-100 transition-opacity duration-300">
    @if ($page === 'ai-chat')
        <div class="flex h-screen">
            <aside class="hidden w-80 shrink-0 border-r border-gray-800 bg-[#0a0a0a] p-5 md:block">
                <div class="mb-8 h-8 w-40 rounded-lg bg-gray-800 animate-pulse"></div>
                <div class="mb-6 h-4 w-28 rounded bg-gray-800 animate-pulse"></div>
                @foreach (range(1, 5) as $row)
                    <div class="mb-3 h-12 rounded-xl bg-[#121212] animate-pulse"></div>
                @endforeach
            </aside>
            <div class="flex min-w-0 flex-1 flex-col bg-black p-5 md:p-8">
                <div class="mx-auto flex w-full max-w-4xl flex-1 flex-col justify-center gap-6">
                    <div class="h-10 w-2/3 rounded-xl bg-gray-800 animate-pulse"></div>
                    <div class="h-24 w-4/5 rounded-2xl bg-[#121212] animate-pulse"></div>
                    <div class="ml-auto h-20 w-2/3 rounded-2xl bg-[#121212] animate-pulse"></div>
                    <div class="h-20 w-3/4 rounded-2xl bg-[#121212] animate-pulse"></div>
                </div>
                <div class="mx-auto mt-6 h-16 w-full max-w-4xl rounded-2xl bg-[#121212] animate-pulse"></div>
            </div>
        </div>
    @elseif ($page === 'welcome')
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-20 flex h-12 items-center justify-between">
                <div class="h-10 w-36 rounded-xl bg-[#68C7EC]/20 animate-pulse"></div>
                <div class="hidden gap-4 md:flex">
                    <div class="h-8 w-20 rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                    <div class="h-8 w-20 rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                    <div class="h-8 w-24 rounded-lg bg-[#68C7EC]/20 animate-pulse"></div>
                </div>
            </div>
            <div class="mx-auto max-w-3xl text-center">
                <div class="mx-auto mb-5 h-12 w-4/5 rounded-xl bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                <div class="mx-auto mb-3 h-12 w-3/5 rounded-xl bg-[#68C7EC]/20 animate-pulse"></div>
                <div class="mx-auto mt-6 h-5 max-w-2xl rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                <div class="mx-auto mt-3 h-5 max-w-xl rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                <div class="mx-auto mt-8 h-12 w-44 rounded-xl bg-[#68C7EC]/30 animate-pulse"></div>
            </div>
            <div class="mt-24 grid gap-6 md:grid-cols-3">
                @foreach (range(1, 3) as $card)
                    <div class="h-48 rounded-3xl border border-gray-200 bg-white p-6 dark:border-[#002525] dark:bg-[#001818] animate-pulse">
                        <div class="mb-6 h-12 w-12 rounded-2xl bg-[#68C7EC]/20"></div>
                        <div class="mb-4 h-6 w-2/3 rounded bg-gray-200 dark:bg-[#002525]"></div>
                        <div class="h-4 w-full rounded bg-gray-200 dark:bg-[#002525]"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <nav class="h-16 border-b border-gray-200 bg-white/80 dark:border-[#002525] dark:bg-[#000F0F]/80">
            <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="h-9 w-36 rounded-lg bg-[#68C7EC]/20 animate-pulse"></div>
                <div class="h-8 w-40 rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
            </div>
        </nav>
        <main class="mx-auto max-w-{{ $page === 'playlist' ? '4xl' : '7xl' }} px-4 py-10 sm:px-6 lg:px-8">
            @if ($page === 'playlist')
                <div class="mb-8">
                    <div class="mb-4 h-9 w-72 rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                    <div class="h-4 max-w-2xl rounded bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                </div>
                @foreach (range(1, 2) as $card)
                    <div class="mb-6 rounded-3xl border border-gray-100 bg-white p-8 dark:border-[#002525] dark:bg-[#001818]">
                        <div class="mb-4 h-6 w-2/5 rounded bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                        <div class="mb-6 h-4 w-4/5 rounded bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                        <div class="h-12 w-full rounded-xl bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                    </div>
                @endforeach
            @elseif ($page === 'guide')
                <div class="flex flex-col gap-8 md:flex-row">
                    <aside class="h-72 w-full shrink-0 rounded-3xl bg-white p-6 dark:bg-[#001818] md:w-64 animate-pulse">
                        <div class="mb-6 h-4 w-28 rounded bg-gray-200 dark:bg-[#002525]"></div>
                        @foreach (range(1, 5) as $row)
                            <div class="mb-4 h-8 rounded-lg bg-gray-200 dark:bg-[#002525]"></div>
                        @endforeach
                    </aside>
                    <div class="min-w-0 flex-1">
                        <div class="mb-5 h-10 w-4/5 rounded-lg bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                        <div class="mb-3 h-5 w-full rounded bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                        <div class="mb-3 h-5 w-11/12 rounded bg-gray-200 dark:bg-[#002525] animate-pulse"></div>
                        @foreach (range(1, 3) as $section)
                            <div class="mt-8 h-40 rounded-2xl bg-white dark:bg-[#001818] animate-pulse"></div>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
    @endif
    <span class="sr-only">Memuat MyStudy...</span>
</div>
