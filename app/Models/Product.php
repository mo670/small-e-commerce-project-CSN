<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'slug',
        'category',
        'sku',
        'description',
        'price',
        'thumbnail',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    /**
     * User who created this product in the master catalog (admin or shop owner).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Shop product assignment records.
     */
    public function shopProducts(): HasMany
    {
        return $this->hasMany(ShopProduct::class, 'product_id');
    }

    /**
     * Shops this product is assigned to.
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_products')
            ->using(ShopProduct::class)
            ->withPivot(['id', 'price', 'details', 'thumbnail', 'stock', 'is_active'])
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
