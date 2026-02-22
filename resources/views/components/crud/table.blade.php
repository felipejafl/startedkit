{{-- Table Component
    Standard data table with Flux styling
    
    Usage:
    <x-crud::table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Product</td>
                <td><flux:badge>Active</flux:badge></td>
                <td>
                    <flux:button>Edit</flux:button>
                </td>
            </tr>
        </tbody>
    </x-crud::table>
--}}

<div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
    <table class="w-full text-sm">
        {{ $slot }}
    </table>
</div>

<style>
    table thead tr {
        @apply bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700;
    }

    table thead th {
        @apply px-4 py-3 text-left font-semibold text-zinc-900 dark:text-zinc-50;
    }

    table tbody tr {
        @apply border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-900/50;
    }

    table tbody td {
        @apply px-4 py-3 text-zinc-700 dark:text-zinc-300;
    }

    table tbody tr:last-child {
        @apply border-b-0;
    }
</style>
