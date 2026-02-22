{{-- Page Header Component
    Standard header for all CRUD index pages
    
    Usage:
    <x-crud::page-header>
        <x-slot:title>Products</x-slot:title>
        <x-slot:subtitle>Manage your product catalog</x-slot:subtitle>
        <x-slot:action>
            <flux:button>Create</flux:button>
        </x-slot:action>
    </x-crud::page-header>
--}}

<div class="space-y-2">
    <div class="flex items-center justify-between gap-4">
        <div class="flex-1">
            @isset($title)
                <flux:heading level="1" size="lg">
                    {{ $title }}
                </flux:heading>
            @endisset

            @isset($subtitle)
                <flux:text size="sm" color="gray">
                    {{ $subtitle }}
                </flux:text>
            @endisset
        </div>

        @isset($action)
            <div class="flex items-center gap-2">
                {{ $action }}
            </div>
        @endisset
    </div>

    @isset($subtitle)
        <flux:separator />
    @endisset
</div>
