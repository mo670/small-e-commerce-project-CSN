<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopOwnerDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $shop = $user->shop;

        if (! $shop) {
            abort(403, 'No shop assigned to your account.');
        }

        $shopProductIds = ShopProduct::where('shop_id', $shop->id)->pluck('product_id');

        $stats = [
            'listed_products' => $shopProductIds->count(),
            'active_listings' => ShopProduct::where('shop_id', $shop->id)->where('is_active', true)->count(),
            'available_in_catalog' => Product::where('is_active', true)->whereNotIn('id', $shopProductIds)->count(),
            'low_stock_count' => ShopProduct::where('shop_id', $shop->id)->where('stock', '<=', 5)->count(),
        ];

        $recentListings = ShopProduct::where('shop_id', $shop->id)
            ->with('product')
            ->latest()
            ->take(6)
            ->get();

        return Inertia::render('ShopOwner/Dashboard', [
            'shop' => $shop,
            'stats' => $stats,
            'recentListings' => $recentListings,
        ]);
    }
}

