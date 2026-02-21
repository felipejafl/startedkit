<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">Roles Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Create and configure roles</p>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search roles..."
            icon="magnifying-glass"
            class="flex-1"
        />

        @can('roles.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Create Role
            </flux:button>
        @endcan
    </div>

    <!-- Roles Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Permissions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Users
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($roles as $role)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $role->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $role->permissions->count() }} permissions</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $role->users()->count() }} users</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('roles.assign_permissions')
                                        <flux:button
                                            wire:click="openPermissionsModal({{ $role->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="lock-closed"
                                            title="Manage permissions"
                                        />
                                    @endcan

                                    @can('roles.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $role->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('roles.delete')
                                        <flux:button
                                            wire:click="deleteRole({{ $role->id }})"
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
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-sm text-zinc-500">No roles found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Create/Edit Role Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingRole ? 'Edit Role' : 'Create Role' }}">
        <flux:field>
            <flux:label>Role Name</flux:label>
            <flux:input
                type="text"
                wire:model="formName"
                placeholder="e.g., editor, moderator"
            />
            <flux:error name="formName" />
        </flux:field>

        <flux:spacer />

        <div class="flex gap-2">
            <flux:button wire:click="closeModals" variant="ghost">Cancel</flux:button>
            <flux:button wire:click="saveRole" variant="primary">
                {{ $editingRole ? 'Update' : 'Create' }}
            </flux:button>
        </div>
    </flux:modal>

    <!-- Permissions Modal -->
    <flux:modal wire:model="showPermissions" title="Manage Permissions">
        <div class="space-y-2 max-h-96 overflow-y-auto">
            @foreach($permissions as $group => $perms)
                <div class="mb-4">
                    <flux:label class="text-sm font-semibold mb-2">{{ $group }}</flux:label>
                    <div class="space-y-2 pl-2">
                        @foreach($perms as $permission)
                            <flux:checkbox
                                wire:model="selectedPermissions"
                                :value="(string)$permission->id"
                                :label="$permission->name"
                            />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <flux:spacer />

        <div class="flex gap-2">
            <flux:button wire:click="closeModals" variant="ghost">Cancel</flux:button>
            <flux:button wire:click="savePermissions" variant="primary">Save Permissions</flux:button>
        </div>
    </flux:modal>
</div>
