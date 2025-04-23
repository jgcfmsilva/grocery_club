<?php

namespace App\Policies;

use App\Enums\OrderStatus;
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
        return $user->id === $order->member_id || $user->type === 'board';
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->member_id &&
               $order->status === OrderStatus::PENDING &&
               $order->created_at->diffInHours(now()) < 24;;
    }

    /**
     * Determine whether the user can download the order receipt.
     */
    public function downloadReceipt(User $user, Order $order): bool
    {
        return $user->id === $order->member_id &&
               $order->status === OrderStatus::COMPLETED;
    }

    /**
     * Determine whether the user can reorder items from this order.
     */
    public function reorder(User $user, Order $order): bool
    {
        return $user->id === $order->member_id;
    }

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->type, ['member', 'board']);
    }
}
