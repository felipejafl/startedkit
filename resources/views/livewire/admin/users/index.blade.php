<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">Users Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage users, assign roles and permissions</p>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search by name or email..."
            icon="magnifying-glass"
            class="flex-1"
        />
        
        <flux:select
            wire:model.live="filterStatus"
            placeholder="Filter by status"
        >
            <option value="">All Statuses</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </flux:select>

        @can('users.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Create User
            </flux:button>
        @endcan
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Roles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-zinc-100 dark:bg-zinc-800">
                                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                            {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->take(2)->map(fn($word) => \Illuminate\Support\Str::substr($word, 0, 1))->implode('') }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <flux:badge size="sm" color="blue">{{ $role->name }}</flux:badge>
                                    @empty
                                        <span class="text-xs text-zinc-500">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge
                                    :color="$user->is_active ? 'green' : 'zinc'"
                                    size="sm"
                                >
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('users.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $user->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('users.assign_roles')
                                        <flux:button
                                            wire:click="openRolesPermissions({{ $user->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="shield-check"
                                            title="Assign roles and permissions"
                                        />
                                    @endcan

                                    @can('users.deactivate')
                                        <flux:button
                                            wire:click="toggleActive({{ $user->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :icon="$user->is_active ? 'x-circle' : 'check-circle'"
                                        />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-sm text-zinc-500">No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create/Edit User Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingUser ? 'Edit User' : 'Create User' }}">
        <div class="space-y-6">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input
                    type="text"
                    wire:model="formName"
                    placeholder="John Doe"
                />
                <flux:error name="formName" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input
                    type="email"
                    wire:model="formEmail"
                    placeholder="user@example.com"
                />
                <flux:error name="formEmail" />
            </flux:field>

            <flux:field>
                <flux:checkbox wire:model="formIsActive" label="Active" />
            </flux:field>
        </div>

        <flux:spacer />

        <div class="flex gap-2">
            <flux:button
                wire:click="closeModals"
                variant="ghost"
            >
                Cancel
            </flux:button>
            <flux:button
                wire:click="saveUser"
                variant="primary"
            >
                {{ $editingUser ? 'Update' : 'Create' }}
            </flux:button>
        </div>
    </flux:modal>

    <!-- Assign Roles/Permissions Modal -->
    <flux:modal wire:model="showRolesPermissions" title="Assign Roles & Permissions">
        @if($selectedUser)
            <livewire:admin.users.assign-roles-permissions :user="$selectedUser" :wire:key="'assign-' . $selectedUser->id" />
        @endif
    </flux:modal>
</div>
