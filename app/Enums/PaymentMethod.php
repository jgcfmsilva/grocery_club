<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Visa = 'Visa';
    case PayPal = 'PayPal';
    case MBWay = 'MB WAY';

    /**
     * Get the label
     */
    public function label(): string
    {
        return match($this) {
            self::Visa => 'Visa',
            self::PayPal => 'PayPal',
            self::MBWay => 'MB WAY'
        };
    }
}
