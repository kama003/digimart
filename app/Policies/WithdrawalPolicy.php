<?php

namespace App\Policies;

use App\Models\Withdrawal;
use App\Models\User;

class WithdrawalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Sellers can view their own withdrawals, admins can view all withdrawals
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Withdrawal $withdrawal): bool
    {
        // Users can view their own withdrawals, admins can view any withdrawal
        return $withdrawal->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only sellers and admins can create withdrawal requests
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Withdrawal $withdrawal): bool
    {
        // Only admins can approve withdrawals
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, Withdrawal $withdrawal): bool
    {
        // Only admins can reject withdrawals
        return $user->isAdmin();
    }
}
