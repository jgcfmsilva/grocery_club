<?php

namespace App\Models;

use App\Enums\CreditType;
use App\Enums\DebitType;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardOperation extends Model
{
    protected $table = 'operations';

    protected $fillable = [
        'card_id',
        'type',
        'value',
        'date',
        'debit_type',
        'credit_type',
        'payment_type',
        'payment_reference',
        'order_id',
        'custom',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'date' => 'date',
        'custom' => 'array',
        'type' => TransactionType::class,
        'debit_type' => DebitType::class,
        'credit_type' => CreditType::class,
        'payment_type' => PaymentMethod::class,
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isCredit(): bool
    {
        return $this->type === TransactionType::Credit;
    }

    public function isDebit(): bool
    {
        return $this->type === TransactionType::Debit;
    }
}
