<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class MembershipPolicy
{
    /**
     * Determine whether the user can view all memberships.
     */
    public function viewAny(User $user): bool
    {
        return $user->isBoard();
    }

    /**
     * Determine whether the user can view the membership.
     */
    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->isBoard();
    }

    /**
     * Determine whether the user can pay membership.
     */
    public function payMembership(User $user): bool
    {
        return $user->isPendingMember();
    }

    /**
     * Determine whether the user can delete the membership.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isBoard();
    }
}
