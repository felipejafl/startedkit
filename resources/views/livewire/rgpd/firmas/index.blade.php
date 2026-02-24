<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">RGPD Firmas Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage email signatures associated to mail accounts</p>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search by signature or mail account..."
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

        @can('firmas.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Add Firma
            </flux:button>
        @endcan
    </div>

    <!-- Firmas Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Mail Account
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Firma
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
                    @forelse($firmas as $firma)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $firma->mailAccount?->email ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2">
                                    {{ $firma->firma ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge
                                    :color="$firma->is_active ? 'green' : 'zinc'"
                                    size="sm"
                                >
                                    {{ $firma->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('firmas.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $firma->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('firmas.update')
                                        <flux:button
                                            wire:click="toggleActive({{ $firma->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :icon="$firma->is_active ? 'x-circle' : 'check-circle'"
                                        />
                                    @endcan

                                    @can('firmas.delete')
                                        <flux:button
                                            wire:click="openDeleteConfirm({{ $firma->id }})"
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
                                <p class="text-sm text-zinc-500">No firmas found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $firmas->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingFirma ? 'Edit Firma' : 'Add Firma' }}">
        <div class="space-y-6">
            <flux:field>
                <flux:label>Mail Account</flux:label>
                <flux:select wire:model="formMailAccountId">
                    <option value="">Select mail account</option>
                    @foreach($mailAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->email }}</option>
                    @endforeach
                </flux:select>
                @error('formMailAccountId')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Firma</flux:label>
                <flux:textarea
                    wire:model="formFirma"
                    placeholder="Signature content..."
                />
                @error('formFirma')
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
                <flux:button type="button" wire:click="saveFirma" variant="primary">
                    {{ $editingFirma ? 'Update Firma' : 'Create Firma' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteConfirm" title="Delete Firma">
        <div class="space-y-6">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this firma? This action cannot be undone.
            </p>

            <div class="flex gap-3">
                <flux:button type="button" wire:click="closeModals" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="button" wire:click="deleteFirma" variant="danger">
                    Delete Firma
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
