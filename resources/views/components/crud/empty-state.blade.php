{{-- Empty State Component
    Standard empty state for when no records exist
    
    Usage:
    <x-crud::empty-state>
        <x-slot:title>No products found</x-slot:title>
        <x-slot:description>Get started by creating your first product</x-slot:description>
        <flux:button>Create Product</flux:button>
    </x-crud::empty-state>
--}}

<div class="flex flex-col items-center justify-center py-12 px-4">
    <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-600 mb-4"
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
        </path>
    </svg>

    @isset($title)
        <flux:heading
            level="3" size="lg"
            class="text-center text-zinc-900 dark:text-zinc-50 mb-2">
            {{ $title }}
        </flux:heading>
    @endisset

    @isset($description)
        <p class="text-center text-zinc-600 dark:text-zinc-400 mb-6 max-w-xs">
            {{ $description }}
        </p>
    @endisset

    @if($slot->isNotEmpty())
        <div class="flex gap-3 justify-center">
            {{ $slot }}
        </div>
    @endif
</div>
