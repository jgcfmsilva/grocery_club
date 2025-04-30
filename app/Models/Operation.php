<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operation extends Model
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
    ];

    /**
     * Relação com o cartão virtual (belongsTo).
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Relação com a encomenda associada (se houver).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Define se a operação é crédito.
     */
    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }

    /**
     * Define se a operação é débito.
     */
    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }
}
