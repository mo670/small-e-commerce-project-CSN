<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ShopManagementService
{
    /**
     * List all shops with owner and product counts.
     */
    public function listShops(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Shop::with(['owner'])
            ->withCount(['shopProducts']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Create a shop.
     */
    public function createShop(array $data): Shop
    {
        return Shop::create([
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'slug' => ! empty($data['slug']) ? Str::slug($data['slug']) : (Str::slug($data['name']) . '-' . Str::lower(Str::random(4))),
            'description' => $data['description'] ?? null,
            'logo' => $data['logo'] ?? null,
            'banner' => $data['banner'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update a shop.
     */
    public function updateShop(Shop $shop, array $data): Shop
    {
        $shop->update([
            'user_id' => array_key_exists('user_id', $data) ? $data['user_id'] : $shop->user_id,
            'name' => $data['name'] ?? $shop->name,
            'slug' => ! empty($data['slug']) ? Str::slug($data['slug']) : $shop->slug,
            'description' => $data['description'] ?? $shop->description,
            'logo' => $data['logo'] ?? $shop->logo,
            'banner' => $data['banner'] ?? $shop->banner,
            'email' => $data['email'] ?? $shop->email,
            'phone' => $data['phone'] ?? $shop->phone,
            'address' => $data['address'] ?? $shop->address,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $shop->is_active,
        ]);

        return $shop->fresh();
    }

    /**
     * Delete a shop.
     */
    public function deleteShop(Shop $shop): bool
    {
        return (bool) $shop->delete();
    }

    /**
     * Assign a product from catalog to a shop with shop-specific overrides.
     */
    public function assignProductToShop(Shop $shop, Product $product, array $overrideData = []): ShopProduct
    {
        return ShopProduct::updateOrCreate(
            [
                'shop_id' => $shop->id,
                'product_id' => $product->id,
            ],
            [
                'price' => $overrideData['price'] ?? $product->price,
                'details' => $overrideData['details'] ?? $product->description,
                'thumbnail' => $overrideData['thumbnail'] ?? $product->thumbnail,
                'stock' => $overrideData['stock'] ?? 10,
                'is_active' => isset($overrideData['is_active']) ? (bool) $overrideData['is_active'] : true,
            ]
        );
    }

    /**
     * Update shop-specific product settings (price, details, thumbnail, stock, is_active).
     */
    public function updateShopProduct(ShopProduct $shopProduct, array $data): ShopProduct
    {
        $shopProduct->update([
            'price' => $data['price'] ?? $shopProduct->price,
            'details' => array_key_exists('details', $data) ? $data['details'] : $shopProduct->details,
            'thumbnail' => array_key_exists('thumbnail', $data) ? $data['thumbnail'] : $shopProduct->thumbnail,
            'stock' => isset($data['stock']) ? (int) $data['stock'] : $shopProduct->stock,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $shopProduct->is_active,
        ]);

        return $shopProduct->fresh();
    }

    /**
     * Remove a product from a shop.
     */
    public function removeProductFromShop(Shop $shop, int $shopProductId): bool
    {
        return (bool) ShopProduct::where('shop_id', $shop->id)
            ->where('id', $shopProductId)
            ->delete();
    }
}
