<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductCatalogService
{
    /**
     * Get products in the shared catalog with search, filter, and optional shop-exclusion.
     */
    public function getSharedCatalog(array $filters = [], ?int $excludeShopId = null, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['creator'])
            ->active();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if ($excludeShopId) {
            $query->whereDoesntHave('shopProducts', function (Builder $q) use ($excludeShopId) {
                $q->where('shop_id', $excludeShopId);
            });
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Create a product in the shared master catalog.
     */
    public function createCatalogProduct(array $data, ?User $user = null): Product
    {
        return Product::create([
            'created_by' => $user?->id,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']) . '-' . Str::lower(Str::random(5)),
            'category' => $data['category'] ?? null,
            'sku' => $data['sku'] ?? ('SKU-' . strtoupper(Str::random(6))),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update an existing product in the catalog.
     */
    public function updateCatalogProduct(Product $product, array $data): Product
    {
        $product->update([
            'title' => $data['title'] ?? $product->title,
            'category' => $data['category'] ?? $product->category,
            'sku' => $data['sku'] ?? $product->sku,
            'description' => $data['description'] ?? $product->description,
            'price' => $data['price'] ?? $product->price,
            'thumbnail' => $data['thumbnail'] ?? $product->thumbnail,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $product->is_active,
        ]);

        return $product->fresh();
    }

    /**
     * Delete a product from catalog.
     */
    public function deleteCatalogProduct(Product $product): bool
    {
        return (bool) $product->delete();
    }
}

