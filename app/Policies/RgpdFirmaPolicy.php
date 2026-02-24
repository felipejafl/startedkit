<?php

namespace App\Policies;

use App\Models\RgpdFirma;
use App\Models\User;

class RgpdFirmaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('firmas.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RgpdFirma $rgpdFirma): bool
    {
        return $user->can('firmas.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('firmas.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RgpdFirma $rgpdFirma): bool
    {
        return $user->can('firmas.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RgpdFirma $rgpdFirma): bool
    {
        return $user->can('firmas.delete');
    }

    public function restore(User $user, RgpdFirma $rgpdFirma): bool
    {
        return false;
    }

    public function forceDelete(User $user, RgpdFirma $rgpdFirma): bool
    {
        return false;
    }
}
