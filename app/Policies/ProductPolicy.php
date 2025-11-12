<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view the product list (including guests)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Product $product): bool
    {
        // Guests and customers can only view approved and active products
        // Sellers can view their own products regardless of status
        // Admins can view any product
        if ($user && $user->isAdmin()) {
            return true;
        }

        if ($user && $product->user_id === $user->id) {
            return true;
        }

        return $product->is_approved && $product->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only sellers and admins can create products
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        // Sellers can update their own products, admins can update any product
        return $product->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        // Sellers can delete their own products, admins can delete any product
        return $product->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Product $product): bool
    {
        // Only admins can approve products
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, Product $product): bool
    {
        // Only admins can reject products
        return $user->isAdmin();
    }
}
