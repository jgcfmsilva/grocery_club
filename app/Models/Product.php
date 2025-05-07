<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'description',
        'photo',
        'discount_min_qty',
        'discount',
        'stock_lower_limit',
        'stock_upper_limit',
        'custom',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'discount_min_qty' => 'integer',
        'discount' => 'decimal:2',
        'stock_lower_limit' => 'integer',
        'stock_upper_limit' => 'integer',
        'custom' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(ItemOrder::class);
    }

    /**
     * Check if the product has a discount available.
     */
    public function hasDiscount(): bool
    {
        return !is_null($this->discount) && !is_null($this->discount_min_qty);
    }

    /**
     * Calculate the discounted price for a given quantity.
     */
    public function getDiscountedPrice(int $quantity): float
    {
        if ($this->hasDiscount() && $quantity >= $this->discount_min_qty) {
            return $this->price - $this->discount;
        }
        return $this->price;
    }

    /**
     * Calculate the price with discount
     */
    public function getPriceWithDiscount(): float
    {
        if ($this->hasDiscount()) {
            return $this->price - $this->discount;
        }
        return $this->price;
    }

    /**
     * Check if the product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Check if the product is low in stock.
     */
    public function isLowInStock(): bool
    {
        return $this->stock <= $this->stock_lower_limit;
    }

    /**
     * Check if the product needs restocking.
     */
    public function needsRestocking(): bool
    {
        return $this->stock <= $this->stock_lower_limit;
    }

    /**
     * Get the recommended restock quantity.
     */
    public function getRecommendedRestockQuantity(): int
    {
        return max(0, $this->stock_upper_limit - $this->stock);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to only include out of stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Scope a query to only include products that need restocking.
     */
    public function scopeNeedsRestocking($query)
    {
        return $query->where('stock', '<=', $this->getRawOriginal('stock_lower_limit'));
    }

    /**
     * Scope a query to only include discounted products.
     */
    public function scopeDiscounted($query)
    {
        return $query->whereNotNull('discount')
                    ->whereNotNull('discount_min_qty');
    }

    /**
     * Get the image URL for the product.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return asset('storage/products/product_no_image.png');
        }
        
        return asset('storage/products/' . $this->photo);
    }
}