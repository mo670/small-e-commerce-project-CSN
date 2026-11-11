<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Shop $shop) {
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name) . '-' . Str::random(5);
            }
        });
    }

    /**
     * The owner of the shop.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Shop specific product assignments.
     */
    public function shopProducts(): HasMany
    {
        return $this->hasMany(ShopProduct::class, 'shop_id');
    }

    /**
     * Products assigned to this shop via shop_products pivot.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_products')
            ->using(ShopProduct::class)
            ->withPivot(['id', 'price', 'details', 'thumbnail', 'stock', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Active products assigned to this shop.
     */
    public function activeProducts(): BelongsToMany
    {
        return $this->products()->wherePivot('is_active', true);
    }
}

