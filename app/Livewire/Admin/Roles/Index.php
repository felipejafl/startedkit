<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public bool $showPermissions = false;

    public ?Role $selectedRole = null;

    public ?Role $editingRole = null;

    public string $formName = '';

    public array $selectedPermissions = [];

    public function openCreateForm(): void
    {
        $this->authorize('roles.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(Role $role): void
    {
        $this->authorize('roles.update');
        $this->editingRole = $role;
        $this->formName = $role->name;
        $this->showForm = true;
    }

    public function openPermissionsModal(Role $role): void
    {
        $this->authorize('roles.assign_permissions');
        $this->selectedRole = $role;
        $this->selectedPermissions = $role->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->showPermissions = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showPermissions = false;
        $this->resetForm();
    }

    public function saveRole(): void
    {
        $this->validate([
            'formName' => ['required', 'string', 'max:255',
                $this->editingRole ? 'unique:roles,name,'.$this->editingRole->id : 'unique:roles,name'],
        ]);

        if ($this->editingRole) {
            $this->authorize('roles.update');
            $this->editingRole->update(['name' => $this->formName]);
            $this->dispatch('flash', type: 'success', message: 'Role updated successfully!');
        } else {
            $this->authorize('roles.create');
            Role::create(['name' => $this->formName, 'guard_name' => 'web']);
            $this->dispatch('flash', type: 'success', message: 'Role created successfully!');
        }

        $this->closeModals();
    }

    public function savePermissions(): void
    {
        if (! $this->selectedRole) {
            return;
        }

        $this->authorize('roles.assign_permissions');

        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
        $this->selectedRole->syncPermissions($permissions);

        $this->dispatch('flash', type: 'success', message: 'Permissions assigned successfully!');
        $this->closeModals();
    }

    public function deleteRole(Role $role): void
    {
        $this->authorize('roles.delete');

        // Protect critical roles
        if (in_array($role->name, ['super-admin', 'admin'])) {
            $this->dispatch('flash', type: 'error', message: 'Cannot delete system roles!');

            return;
        }

        // Check if role is assigned to users
        if ($role->users()->count() > 0) {
            $this->dispatch('flash', type: 'error', message: 'Cannot delete role with assigned users!');

            return;
        }

        $role->delete();
        $this->dispatch('flash', type: 'success', message: 'Role deleted successfully!');
    }

    public function render()
    {
        $query = Role::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $roles = $query->latest()->paginate(15);

        // Group permissions by prefix
        $allPermissions = Permission::all();
        $groupedPermissions = [];
        foreach ($allPermissions as $permission) {
            $prefix = explode('.', $permission->name)[0];
            $group = ucfirst($prefix);
            if (! isset($groupedPermissions[$group])) {
                $groupedPermissions[$group] = [];
            }
            $groupedPermissions[$group][] = $permission;
        }

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
            'permissions' => $groupedPermissions,
        ]);
    }

    private function resetForm(): void
    {
        $this->formName = '';
        $this->editingRole = null;
        $this->selectedPermissions = [];
    }
}
