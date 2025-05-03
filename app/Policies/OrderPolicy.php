<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return (
            ($user->id === $order->member_id && $user->isMember()) || 
            ($user->type === UserType::Board)
        );
    }

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->isMemberOrBoard();
    }

    /**
     * Determine whether the user can create a new order.
     */
    public function order(User $user): bool
    {
        return $user->isMemberOrBoard();
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        return (
            ($user->id === $order->member_id && $order->status === OrderStatus::PENDING) ||
            ($user->isBoard() && $order->status === OrderStatus::PENDING)
        );
    }

    /**
     * Determine whether the user can download the order receipt.
     */
    public function downloadReceipt(User $user, Order $order): bool
    {
        return $user->id === $order->member_id && $order->status === OrderStatus::COMPLETED;
    }

    /**
     * Determine whether the user can reorder items from this order.
     */
    public function reorder(User $user, Order $order): bool
    {
        return $user->id === $order->member_id;
    }
}
