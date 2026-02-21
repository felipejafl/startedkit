<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();

        $recentUsers = User::latest()->take(5)->get();

        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalRoles' => $totalRoles,
            'totalPermissions' => $totalPermissions,
            'recentUsers' => $recentUsers,
        ]);
    }
}
