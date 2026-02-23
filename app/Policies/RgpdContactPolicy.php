<?php

namespace App\Policies;

use App\Models\RgpdContact;
use App\Models\User;

class RgpdContactPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('contacts.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RgpdContact $rgpdContact): bool
    {
        return $user->can('contacts.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('contacts.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RgpdContact $rgpdContact): bool
    {
        return $user->can('contacts.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RgpdContact $rgpdContact): bool
    {
        return $user->can('contacts.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RgpdContact $rgpdContact): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RgpdContact $rgpdContact): bool
    {
        return false;
    }
}
