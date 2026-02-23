<?php

namespace App\Policies;

use App\Models\RgpdPlantilla;
use App\Models\User;

class RgpdPlantillaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('plantillas.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RgpdPlantilla $rgpdPlantilla): bool
    {
        return $user->can('plantillas.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('plantillas.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RgpdPlantilla $rgpdPlantilla): bool
    {
        return $user->can('plantillas.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RgpdPlantilla $rgpdPlantilla): bool
    {
        return $user->can('plantillas.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RgpdPlantilla $rgpdPlantilla): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RgpdPlantilla $rgpdPlantilla): bool
    {
        return false;
    }
}
