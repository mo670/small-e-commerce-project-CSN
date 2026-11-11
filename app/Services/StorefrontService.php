<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StorefrontService
{
    /**
     * Get all active shops for customers to browse.
     */
    public function getActiveShops(int $perPage = 9): LengthAwarePaginator
    {
        return Shop::where('is_active', true)
            ->withCount(['shopProducts' => function (Builder $q) {
                $q->where('is_active', true);
            }])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get featured shops with product count.
     */
    public function getFeaturedShops(int $limit = 6): Collection
    {
        return Shop::where('is_active', true)
            ->withCount(['shopProducts' => function (Builder $q) {
                $q->where('is_active', true);
            }])
            ->has('shopProducts')
            ->take($limit)
            ->get();
    }

    /**
     * Get shop storefront details and ONLY products assigned to this shop.
     * Products not assigned to any shop must NOT be visible.
     */
    public function getShopProducts(Shop $shop, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = ShopProduct::where('shop_id', $shop->id)
            ->where('is_active', true)
            ->whereHas('product', function (Builder $q) {
                $q->where('is_active', true);
            })
            ->with(['product']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('details', 'like', "%{$search}%")
                  ->orWhereHas('product', function (Builder $pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%")
                         ->orWhere('category', 'like', "%{$search}%");
                  });
            });
        }

        if (! empty($filters['category'])) {
            $category = $filters['category'];
            $query->whereHas('product', function (Builder $q) use ($category) {
                $q->where('category', $category);
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get single shop product for customer detail view.
     */
    public function getShopProduct(Shop $shop, int $shopProductId): ?ShopProduct
    {
        return ShopProduct::where('shop_id', $shop->id)
            ->where('id', $shopProductId)
            ->where('is_active', true)
            ->whereHas('product', function (Builder $q) {
                $q->where('is_active', true);
            })
            ->with(['product', 'shop'])
            ->first();
    }
}
