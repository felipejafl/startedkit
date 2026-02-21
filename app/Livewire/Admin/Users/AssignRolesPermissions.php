<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignRolesPermissions extends Component
{
    public ?User $user = null;

    public array $selectedRoles = [];

    public array $selectedPermissions = [];

    public function mount(?User $user = null): void
    {
        if ($user) {
            $this->user = $user;
            $this->selectedRoles = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();
            $this->selectedPermissions = $user->getDirectPermissions()->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        }
    }

    public function save(): void
    {
        if (! $this->user) {
            return;
        }

        // Prevent non-super-admin from assigning super-admin role
        if (in_array('super-admin', $this->selectedRoles) && ! auth()->user()->hasRole('super-admin')) {
            $this->dispatch('flash', type: 'error', message: 'You cannot assign super-admin role!');

            return;
        }

        $roles = Role::whereIn('id', $this->selectedRoles)->get();
        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

        $this->user->syncRoles($roles);
        $this->user->syncPermissions($permissions);

        $this->dispatch('flash', type: 'success', message: 'Roles and permissions saved!');
        $this->dispatch('close-modal');
    }

    public function render()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('livewire.admin.users.assign-roles-permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
