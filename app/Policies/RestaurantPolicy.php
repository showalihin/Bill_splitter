<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;

class RestaurantPolicy
{
    /**
     * Admins bypass every single policy check automatically.
     * Laravel calls this before any other method — return true to grant all.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // continue to individual checks
    }

    /**
     * Any authenticated user can see the restaurants list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * A user can view:
     *   - any global restaurant
     *   - their own private restaurant
     */
    public function view(User $user, Restaurant $restaurant): bool
    {
        return $restaurant->isGlobal() || $restaurant->isOwnedBy($user);
    }

    /**
     * Any authenticated user can create a restaurant (it will be private by default).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner can edit their private restaurant.
     * Global restaurants are admin-only (handled by before()).
     */
    public function update(User $user, Restaurant $restaurant): bool
    {
        return $restaurant->isOwnedBy($user);
    }

    /**
     * Only the owner can delete their own restaurant.
     */
    public function delete(User $user, Restaurant $restaurant): bool
    {
        return $restaurant->isOwnedBy($user);
    }

    /**
     * A user can request global status only for their own private restaurants
     * that haven't already been approved or are not already pending.
     */
    public function requestGlobal(User $user, Restaurant $restaurant): bool
    {
        return $restaurant->isOwnedBy($user)
            && $restaurant->isPrivate()
            && $restaurant->status !== 'pending'
            && $restaurant->status !== 'approved';
    }
}
