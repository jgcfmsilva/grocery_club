<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'custom',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
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
     * Get all categories.
     */
    public static function allCategories()
    {
        return self::all();
    }

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the active products for the category.
     */
    public function activeProducts(): HasMany
    {
        return $this->products()->whereNull('deleted_at');
    }

    /**
     * Get the image URL for the category.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return asset('storage/categories/category_no_image.png');
        }
        
        return asset('storage/categories/' . $this->image);
    }

    /**
     * Scope a query to only include categories with products.
     */
    public function scopeWithProducts($query)
    {
        return $query->whereHas('products');
    }

    /**
     * Scope a query to only include categories with active products.
     */
    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('activeProducts');
    }

    /**
     * Get the total stock count for this category.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->products()->sum('stock');
    }

    /**
     * Check if the category has any products.
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Check if the category has any active products.
     */
    public function hasActiveProducts(): bool
    {
        return $this->activeProducts()->exists();
    }
}