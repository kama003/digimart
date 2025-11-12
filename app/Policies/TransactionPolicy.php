<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view all transactions
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        // Users can view their own transactions, admins can view any transaction
        return $transaction->user_id === $user->id || $user->isAdmin();
    }
}
