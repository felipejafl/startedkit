<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">Permissions Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage system permissions</p>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search permissions..."
            icon="magnifying-glass"
            class="flex-1"
        />

        @can('permissions.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Create Permission
            </flux:button>
        @endcan
    </div>

    <!-- Permissions Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Roles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $permission->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $permission->roles()->count() }} roles</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('permissions.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $permission->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('permissions.delete')
                                        <flux:button
                                            wire:click="deletePermission({{ $permission->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                        />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <p class="text-sm text-zinc-500">No permissions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $permissions->links() }}
        </div>
    </div>

    <!-- Create/Edit Permission Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingPermission ? 'Edit Permission' : 'Create Permission' }}">
        <flux:field>
            <flux:label>Permission Name</flux:label>
            <flux:input
                type="text"
                wire:model="formName"
                placeholder="e.g., posts.publish"
            />
            <flux:error name="formName" />
            <flux:text size="sm" class="mt-2 text-zinc-500">
                Use dot notation (e.g., module.action)
            </flux:text>
        </flux:field>

        <flux:spacer />

        <div class="flex gap-2">
            <flux:button wire:click="closeModals" variant="ghost">Cancel</flux:button>
            <flux:button wire:click="savePermission" variant="primary">
                {{ $editingPermission ? 'Update' : 'Create' }}
            </flux:button>
        </div>
    </flux:modal>
</div>
