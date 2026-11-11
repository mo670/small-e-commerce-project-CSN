<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Services\StorefrontService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StorefrontController extends Controller
{
    public function __construct(
        protected StorefrontService $storefrontService
    ) {}

    /**
     * Storefront Landing / Directory of active shops.
     */
    public function index(): Response
    {
        $shops = $this->storefrontService->getActiveShops(9);
        $featuredShops = $this->storefrontService->getFeaturedShops(6);

        return Inertia::render('Storefront/Home', [
            'shops' => $shops,
            'featuredShops' => $featuredShops,
        ]);
    }

    /**
     * Customer view of a specific shop.
     * ONLY products assigned to this shop are displayed.
     * Shows shop-specific product information, prices, and thumbnails.
     */
    public function shopShow(Request $request, string $slug): Response
    {
        $shop = Shop::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $filters = $request->only(['search', 'category', 'sort']);
        $shopProducts = $this->storefrontService->getShopProducts($shop, $filters, 12);

        // Fetch distinct categories available among products in THIS shop only
        $categories = Product::whereHas('shopProducts', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id)->where('is_active', true);
        })->whereNotNull('category')->distinct()->pluck('category');

        return Inertia::render('Storefront/ShopShow', [
            'shop' => $shop,
            'shopProducts' => $shopProducts,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    /**
     * Single shop-assigned product detail view.
     */
    public function productShow(string $shopSlug, int $shopProductId): Response
    {
        $shop = Shop::where('slug', $shopSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $shopProduct = $this->storefrontService->getShopProduct($shop, $shopProductId);

        if (! $shopProduct) {
            abort(404, 'Product not found or not active in this shop.');
        }

        // Related products in this shop
        $relatedProducts = $this->storefrontService->getShopProducts($shop, [], 4)
            ->filter(fn ($p) => $p->id !== $shopProduct->id)
            ->values();

        return Inertia::render('Storefront/ProductShow', [
            'shop' => $shop,
            'shopProduct' => $shopProduct,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}

