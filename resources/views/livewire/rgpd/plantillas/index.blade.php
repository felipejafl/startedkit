<div>
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="lg" level="1">RGPD Templates Management</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage email templates for RGPD communications</p>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search by subject or body..."
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

        @can('plantillas.create')
            <flux:button
                wire:click="openCreateForm"
                variant="primary"
                icon="plus"
            >
                Add Template
            </flux:button>
        @endcan
    </div>

    <!-- Templates Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Subject
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">
                            Body Preview
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
                    @forelse($plantillas as $plantilla)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $plantilla->subject }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2">
                                    {{ Illuminate\Support\Str::limit($plantilla->body, 100) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge
                                    :color="$plantilla->is_active ? 'green' : 'zinc'"
                                    size="sm"
                                >
                                    {{ $plantilla->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @can('plantillas.update')
                                        <flux:button
                                            wire:click="openEditForm({{ $plantilla->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />
                                    @endcan

                                    @can('plantillas.update')
                                        <flux:button
                                            wire:click="toggleActive({{ $plantilla->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :icon="$plantilla->is_active ? 'x-circle' : 'check-circle'"
                                        />
                                    @endcan

                                    @can('plantillas.delete')
                                        <flux:button
                                            wire:click="openDeleteConfirm({{ $plantilla->id }})"
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
                                <p class="text-sm text-zinc-500">No templates found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $plantillas->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showForm" title="{{ $editingPlantilla ? 'Edit Template' : 'Add Template' }}">
        <div class="space-y-6">
            <flux:field>
                <flux:label>Subject</flux:label>
                <flux:input
                    type="text"
                    wire:model="formSubject"
                    placeholder="e.g., Data Access Request Confirmation"
                />
                @error('formSubject')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Body</flux:label>
                <flux:textarea
                    wire:model="formBody"
                    placeholder="Template body content..."
                    rows="8"
                />
                @error('formBody')
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
                <flux:button type="button" wire:click="savePlantilla" variant="primary">
                    {{ $editingPlantilla ? 'Update Template' : 'Create Template' }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteConfirm" title="Delete Template">
        <div class="space-y-6">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this template? This action cannot be undone.
            </p>

            <div class="flex gap-3">
                <flux:button type="button" wire:click="closeModals" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="button" wire:click="deletePlantilla" variant="danger">
                    Delete Template
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
