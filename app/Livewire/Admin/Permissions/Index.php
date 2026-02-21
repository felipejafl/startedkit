<?php

namespace App\Livewire\Admin\Permissions;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?Permission $editingPermission = null;

    public string $formName = '';

    public function openCreateForm(): void
    {
        $this->authorize('permissions.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(Permission $permission): void
    {
        $this->authorize('permissions.update');
        $this->editingPermission = $permission;
        $this->formName = $permission->name;
        $this->showForm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function savePermission(): void
    {
        $this->validate([
            'formName' => ['required', 'string', 'max:255',
                $this->editingPermission ? 'unique:permissions,name,'.$this->editingPermission->id : 'unique:permissions,name'],
        ]);

        if ($this->editingPermission) {
            $this->authorize('permissions.update');
            $this->editingPermission->update(['name' => $this->formName]);
            $this->dispatch('flash', type: 'success', message: 'Permission updated successfully!');
        } else {
            $this->authorize('permissions.create');
            Permission::create(['name' => $this->formName, 'guard_name' => 'web']);
            $this->dispatch('flash', type: 'success', message: 'Permission created successfully!');
        }

        $this->closeModals();
    }

    public function deletePermission(Permission $permission): void
    {
        $this->authorize('permissions.delete');

        // Protect critical permissions
        if ($permission->name === 'admin.access') {
            if (! auth()->user()->hasRole('super-admin')) {
                $this->dispatch('flash', type: 'error', message: 'Only super-admins can delete critical permissions!');

                return;
            }
        }

        $permission->delete();
        $this->dispatch('flash', type: 'success', message: 'Permission deleted successfully!');
    }

    public function render()
    {
        $query = Permission::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $permissions = $query->latest()->paginate(20);

        return view('livewire.admin.permissions.index', [
            'permissions' => $permissions,
        ]);
    }

    private function resetForm(): void
    {
        $this->formName = '';
        $this->editingPermission = null;
    }
}
