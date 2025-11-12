<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile, admins can view any profile
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile, admins can update any profile
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admins can delete users, and they cannot delete themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can ban the model.
     */
    public function ban(User $user, User $model): bool
    {
        // Only admins can ban users, and they cannot ban themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can change the role of the model.
     */
    public function changeRole(User $user, User $model): bool
    {
        // Only admins can change user roles, and they cannot change their own role
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
