<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'status',
        'date',
        'total_items',
        'shipping_cost',
        'total',
        'nif',
        'delivery_address',
        'pdf_receipt',
        'cancel_reason',
        'custom',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'total_items' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'custom' => 'array',
        'status' => OrderStatus::class,
    ];

    /**
     * Get the member who placed the order
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Get the items for the order
     */
    public function items(): HasMany
    {
        return $this->hasMany(ItemsOrder::class);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING->value);
    }

    /**
     * Scope a query to only include completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', OrderStatus::COMPLETED->value);
    }

    /**
     * Scope a query to only include canceled orders.
     */
    public function scopeCanceled($query)
    {
        return $query->where('status', OrderStatus::CANCELED->value);
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED;
    }

    /**
     * Check if order is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === OrderStatus::CANCELED;
    }
}