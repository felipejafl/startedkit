<div class="space-y-6">
    <div>
        <flux:label class="mb-3">Roles</flux:label>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($roles as $role)
                <flux:checkbox
                    wire:model="selectedRoles"
                    :value="(string)$role->id"
                    :label="$role->name"
                />
            @endforeach
        </div>
    </div>

    <flux:separator />

    <div>
        <flux:label class="mb-3">Direct Permissions</flux:label>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($permissions as $permission)
                <flux:checkbox
                    wire:model="selectedPermissions"
                    :value="(string)$permission->id"
                    :label="$permission->name"
                />
            @endforeach
        </div>
    </div>

    <flux:spacer />

    <div class="flex gap-2">
        <flux:button
            wire:click="$dispatch('close-modal')"
            variant="ghost"
        >
            Cancel
        </flux:button>
        <flux:button
            wire:click="save"
            variant="primary"
        >
            Save
        </flux:button>
    </div>
</div>
