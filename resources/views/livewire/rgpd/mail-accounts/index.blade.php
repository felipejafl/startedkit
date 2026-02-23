<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">Mail Accounts Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage email account configurations for IMAP and SMTP</p>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search by account name or email..."
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

        @can('mail-accounts.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Add Account
            </flux:button>
        @endcan
    </div>

    <!-- Mail Accounts Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Account Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Server
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            IMAP / SMTP
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
                    @forelse($accounts as $account)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $account->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $account->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $account->server }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="text-xs text-zinc-600 dark:text-zinc-400">
                                        <span>IMAP: Port {{ $account->imap_port }}</span>
                                        <flux:badge size="sm" color="blue" class="ml-2">{{ strtoupper($account->imap_security) }}</flux:badge>
                                    </div>
                                    <div class="text-xs text-zinc-600 dark:text-zinc-400">
                                        <span>SMTP: Port {{ $account->smtp_port }}</span>
                                        <flux:badge size="sm" color="purple" class="ml-2">{{ strtoupper($account->smtp_security) }}</flux:badge>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge
                                    :color="$account->is_active ? 'green' : 'zinc'"
                                    size="sm"
                                >
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('mail-accounts.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $account->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('mail-accounts.update')
                                        <flux:button
                                            wire:click="toggleActive({{ $account->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :icon="$account->is_active ? 'x-circle' : 'check-circle'"
                                        />
                                    @endcan

                                    @can('mail-accounts.delete')
                                        <flux:button
                                            wire:click="openDeleteConfirm({{ $account->id }})"
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-sm text-zinc-500">No mail accounts found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $accounts->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingAccount ? 'Edit Mail Account' : 'Add Mail Account' }}">
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Basic Information</div>
            
            <flux:field>
                <flux:label>Account Name</flux:label>
                <flux:input
                    type="text"
                    wire:model="formName"
                    placeholder="e.g., Company Support Email"
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
                    placeholder="support@example.com"
                />
                @error('formEmail')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Email Server</flux:label>
                <flux:input
                    type="text"
                    wire:model="formServer"
                    placeholder="imap.gmail.com"
                />
                @error('formServer')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>{{ $editingAccount ? 'Password (leave empty to keep current)' : 'Password' }}</flux:label>
                <flux:input
                    type="password"
                    wire:model="formPassword"
                    placeholder="••••••••"
                />
                @error('formPassword')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <!-- IMAP Configuration -->
            <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 pt-4 border-t border-zinc-200 dark:border-zinc-700">IMAP Configuration</div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>IMAP Port</flux:label>
                    <flux:input
                        type="number"
                        wire:model="formImapPort"
                        placeholder="993"
                    />
                    @error('formImapPort')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>IMAP Security</flux:label>
                    <flux:select wire:model="formImapSecurity">
                        <option value="none">None</option>
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                    </flux:select>
                    @error('formImapSecurity')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <!-- SMTP Configuration -->
            <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 pt-4 border-t border-zinc-200 dark:border-zinc-700">SMTP Configuration</div>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>SMTP Port</flux:label>
                    <flux:input
                        type="number"
                        wire:model="formSmtpPort"
                        placeholder="587"
                    />
                    @error('formSmtpPort')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>SMTP Security</flux:label>
                    <flux:select wire:model="formSmtpSecurity">
                        <option value="none">None</option>
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                    </flux:select>
                    @error('formSmtpSecurity')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <!-- Status -->
            <flux:field>
                <flux:checkbox wire:model="formIsActive" label="Active" />
            </flux:field>

            <!-- Actions -->
            <div class="flex gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button type="button" wire:click="closeModals" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="button" wire:click="saveAccount" variant="primary">
                    {{ $editingAccount ? 'Update Account' : 'Create Account' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteConfirm" title="Delete Mail Account">
        <div class="space-y-6">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this mail account? This action cannot be undone.
            </p>

            <div class="flex gap-3">
                <flux:button type="button" wire:click="closeModals" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="button" wire:click="deleteAccount" variant="danger">
                    Delete Account
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
