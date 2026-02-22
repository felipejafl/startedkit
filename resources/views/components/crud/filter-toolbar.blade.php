{{-- Filter Toolbar Component
    Standard toolbar for filters, search, and actions
    
    Usage:
    <x-crud::filter-toolbar>
        <flux:input wire:model.live.debounce-500ms="search" placeholder="Search..." />
        <flux:select wire:model.live="status">
            <option value="">All</option>
            <option value="1">Active</option>
        </flux:select>
        <flux:button wire:click="resetFilters">Reset</flux:button>
    </x-crud::filter-toolbar>
--}}

<div class="flex flex-wrap gap-3 items-end">
    {{ $slot }}
</div>
