<?php

namespace App\Enums;

enum TransactionType: string
{
    case Credit = 'credit';
    case Debit = 'debit';

    /**
     * Get the label
     */
    public function label(): string
    {
        return match($this) {
            self::Credit => 'Credit',
            self::Debit => 'Debit',
        };
    }
}
