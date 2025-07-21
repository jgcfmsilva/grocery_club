<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    /**
     * Get the label for the status
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELED => 'Canceled',
        };
    }

    /**
     * Get the CSS class for the status badge
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-500 text-white',
            self::COMPLETED => 'bg-green-500 text-white',
            self::CANCELED => 'bg-red-500 text-white',
        };
    }

    /**
     * Get all status values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
