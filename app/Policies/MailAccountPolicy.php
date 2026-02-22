<?php

namespace App\Policies;

use App\Models\MailAccount;
use App\Models\User;

class MailAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('mail-accounts.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MailAccount $mailAccount): bool
    {
        return $user->can('mail-accounts.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('mail-accounts.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MailAccount $mailAccount): bool
    {
        return $user->can('mail-accounts.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MailAccount $mailAccount): bool
    {
        return $user->can('mail-accounts.delete');
    }
}
