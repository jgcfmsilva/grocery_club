<?php

namespace App\Enums;

enum DebitType: string
{
    case Order = 'order';
    case MembershipFee = 'membership_fee';

    /**
     * Get the label
     */
    public function label(): string
    {
        return match($this) {
            self::Order => 'Order',
            self::MembershipFee => 'Membership Fee'
        };
    }
}
