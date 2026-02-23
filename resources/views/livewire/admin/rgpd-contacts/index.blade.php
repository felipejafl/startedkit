<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">RGPD Contacts Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage email contact information for RGPD purposes</p>
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

        @can('rgpd-contacts.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Add Contact
            </flux:button>
        @endcan
    </div>

    <!-- Contacts Table -->
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
                            Note
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
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $contact->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $contact->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2">
                                    {{ $contact->note ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge
                                    :color="$contact->is_active ? 'green' : 'zinc'"
                                    size="sm"
                                >
                                    {{ $contact->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('rgpd-contacts.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $contact->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('rgpd-contacts.update')
                                        <flux:button
                                            wire:click="toggleActive({{ $contact->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :icon="$contact->is_active ? 'x-circle' : 'check-circle'"
                                        />
                                    @endcan

                                    @can('rgpd-contacts.delete')
                                        <flux:button
                                            wire:click="openDeleteConfirm({{ $contact->id }})"
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-sm text-zinc-500">No contacts found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $contacts->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingContact ? 'Edit Contact' : 'Add Contact' }}">
        <div class="space-y-6">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input
                    type="text"
                    wire:model="formName"
                    placeholder="e.g., John Doe"
                />
                @error('formName')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Email Address</flux:label>
                <flux:input
                    type="email"
                    wire:model="formEmail"
                    placeholder="john@example.com"
                />
                @error('formEmail')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Note</flux:label>
                <flux:textarea
                    wire:model="formNote"
                    placeholder="Any additional information..."
                />
                @error('formNote')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:checkbox wire:model="formIsActive" label="Active" />
            </flux:field>

            <!-- Actions -->
            <div class="flex gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button type="button" wire:click="closeModals" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="button" wire:click="saveContact" variant="primary">
                    {{ $editingContact ? 'Update Contact' : 'Create Contact' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteConfirm" title="Delete Contact">
        <div class="space-y-6">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this contact? This action cannot be undone.
            </p>

            <div class="flex gap-3">
                <flux:button type="button" wire:click="closeModals" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="button" wire:click="deleteContact" variant="danger">
                    Delete Contact
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
