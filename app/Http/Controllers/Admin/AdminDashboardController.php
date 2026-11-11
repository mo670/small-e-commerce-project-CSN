<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('is_active', true)->count(),
            'catalog_products' => Product::count(),
            'shop_owners' => User::where('role', User::ROLE_SHOP_OWNER)->count(),
            'total_assignments' => ShopProduct::count(),
        ];

        $recentShops = Shop::with('owner')->latest()->take(5)->get();
        $recentProducts = Product::with('creator')->latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentShops' => $recentShops,
            'recentProducts' => $recentProducts,
        ]);
    }
}
