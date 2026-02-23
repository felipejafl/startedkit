@props(['href' => '#', 'active' => false])

<a 
    href="{{ $href }}"
    {{ $attributes->class([
        'block px-4 py-2 rounded-lg text-sm font-medium transition-colors',
        'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100' => $active,
        'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' => !$active,
    ]) }}
>
    {{ $slot }}
</a>
