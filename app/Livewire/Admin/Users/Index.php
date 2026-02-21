<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    public string $filterRole = '';

    public string $filterStatus = '';

    public bool $showForm = false;

    public bool $showRolesPermissions = false;

    public ?User $selectedUser = null;

    public ?User $editingUser = null;

    public string $formName = '';

    public string $formEmail = '';

    public bool $formIsActive = true;

    protected $queryString = ['search', 'filterRole', 'filterStatus'];

    public function openCreateForm(): void
    {
        $this->authorize('users.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(User $user): void
    {
        $this->authorize('users.update');
        $this->editingUser = $user;
        $this->formName = $user->name;
        $this->formEmail = $user->email;
        $this->formIsActive = $user->is_active;
        $this->showForm = true;
    }

    public function openRolesPermissions(User $user): void
    {
        $this->authorize('users.assign_roles');
        $this->selectedUser = $user;
        $this->showRolesPermissions = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showRolesPermissions = false;
        $this->resetForm();
    }

    public function saveUser(): void
    {
        $this->validate([
            'formName' => 'required|string|max:255',
            'formEmail' => ['required', 'email', 'max:255',
                $this->editingUser ? 'unique:users,email,'.$this->editingUser->id : 'unique:users,email'],
        ]);

        if ($this->editingUser) {
            $this->authorize('users.update');
            $this->editingUser->update([
                'name' => $this->formName,
                'email' => $this->formEmail,
                'is_active' => $this->formIsActive,
            ]);
            $this->dispatch('flash', type: 'success', message: 'User updated successfully!');
        } else {
            $this->authorize('users.create');
            User::create([
                'name' => $this->formName,
                'email' => $this->formEmail,
                'password' => bcrypt('tempPassword123'),
                'is_active' => $this->formIsActive,
                'email_verified_at' => now(),
            ]);
            $this->dispatch('flash', type: 'success', message: 'User created successfully!');
        }

        $this->closeModals();
    }

    public function toggleActive(User $user): void
    {
        $this->authorize('users.deactivate');
        $user->update(['is_active' => ! $user->is_active]);
        $this->dispatch('flash', type: 'success', message: 'User status updated!');
    }

    public function deleteUser(User $user): void
    {
        $this->authorize('users.update');

        if ($user->hasRole('super-admin') && auth()->user()->id !== $user->id) {
            // Only super-admin can delete other super-admins
            if (! auth()->user()->hasRole('super-admin')) {
                $this->dispatch('flash', type: 'error', message: 'Cannot delete super-admin users!');

                return;
            }
        }

        $user->delete();
        $this->dispatch('flash', type: 'success', message: 'User deleted successfully!');
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === '1');
        }

        $users = $query->latest()->paginate(15);
        $roles = \Spatie\Permission\Models\Role::all();

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    private function resetForm(): void
    {
        $this->formName = '';
        $this->formEmail = '';
        $this->formIsActive = true;
        $this->editingUser = null;
    }
}
