<?php

namespace App\Enums;

enum ProductStockLevel: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';

    /**
     * Get the label for the stock level
     */
    public function label(): string
    {
        return match($this) {
            self::LOW => 'Low',
            self::NORMAL => 'Normal',
            self::HIGH => 'High',
        };
    }

    /**
     * Get the CSS class for the badge
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::LOW => 'bg-red-500 text-white',
            self::NORMAL => 'bg-green-500 text-white',
            self::HIGH => 'bg-yellow-500 text-white',
        };
    }

    /**
     * Get the stock level from stock and limits
     */
    public static function fromStock(int $stock, int $lower, int $upper): self
    {
        if ($stock <= $lower) {
            return self::LOW;
        }
        if ($stock >= $upper) {
            return self::HIGH;
        }
        return self::NORMAL;
    }
}
