<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ShopProduct extends Pivot
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'shop_products';

    protected $fillable = [
        'shop_id',
        'product_id',
        'price',
        'details',
        'thumbnail',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get effective thumbnail (fallback to catalog thumbnail if shop thumbnail is null).
     */
    public function getEffectiveThumbnailAttribute(): ?string
    {
        return $this->thumbnail ?: $this->product?->thumbnail;
    }

    /**
     * Get effective details (fallback to catalog description if shop details is null).
     */
    public function getEffectiveDetailsAttribute(): ?string
    {
        return $this->details ?: $this->product?->description;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}

