<?php

namespace App\Enums;

enum CreditType: string
{
    case Payment = 'payment';
    case OrderCancellation = 'order_cancellation';

    /**
     * Get the label
     */
    public function label(): string
    {
        return match($this) {
            self::Payment => 'Payment',
            self::OrderCancellation => 'Order Cancellation'
        };
    }
}
