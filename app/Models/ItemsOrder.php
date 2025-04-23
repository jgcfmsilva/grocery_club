<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemsOrder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
        'custom',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'custom' => 'array',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the subtotal for the item.
     */
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->quantity * ($this->unit_price - $this->discount);
        $this->save();
    }

    /**
     * Get the original product price before any discounts.
     */
    public function getOriginalPriceAttribute(): float
    {
        return $this->unit_price + $this->discount;
    }

    /**
     * Get the total discount applied to this item.
     */
    public function getTotalDiscountAttribute(): float
    {
        return $this->discount * $this->quantity;
    }

    /**
     * Scope a query to only include items with discounts.
     */
    public function scopeWithDiscount($query)
    {
        return $query->where('discount', '>', 0);
    }

    /**
     * Check if the item has a discount applied.
     */
    public function hasDiscount(): bool
    {
        return $this->discount > 0;
    }
}