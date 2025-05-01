<?php

namespace App\Enums;

enum UserType: string
{
    case Member = 'member';
    case Board = 'board';
    case Employee = 'employee';
    case PendingMember = 'pending_member';

    /**
     * Get the label
     */
    public function label(): string
    {
        return match($this) {
            self::Member => 'Member',
            self::Board => 'Board',
            self::Employee => 'Employee',
            self::PendingMember => 'Pending Member',
        };
    }
}
