@props(['items' => []])

<aside class="w-64 bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 flex flex-col min-h-screen">
    {{-- Logo / Header --}}
    <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
        <a href="/" class="font-bold text-lg text-zinc-900 dark:text-white flex items-center gap-2">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>

    {{-- Navigation Items --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
        {{ $slot }}
    </nav>

    {{-- Footer / User Info --}}
    <div class="border-t border-zinc-200 dark:border-zinc-800 p-4">
        @auth
            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                Logged in as <strong class="text-zinc-900 dark:text-white">{{ auth()->user()->name }}</strong>
            </div>
        @endauth
    </div>
</aside>
