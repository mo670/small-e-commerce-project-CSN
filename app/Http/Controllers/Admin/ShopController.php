<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShopRequest;
use App\Models\Shop;
use App\Models\User;
use App\Services\ShopManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function __construct(
        protected ShopManagementService $shopService
    ) {}

    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'is_active']);
        $shops = $this->shopService->listShops($filters);

        return Inertia::render('Admin/Shops/Index', [
            'shops' => $shops,
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        $shopOwners = User::where('role', User::ROLE_SHOP_OWNER)
            ->doesntHave('shop')
            ->select('id', 'name', 'email')
            ->get();

        return Inertia::render('Admin/Shops/Create', [
            'availableOwners' => $shopOwners,
        ]);
    }

    public function store(ShopRequest $request): RedirectResponse
    {
        $this->shopService->createShop($request->validated());

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop created successfully!');
    }

    public function edit(Shop $shop): Response
    {
        $shop->load('owner');

        $availableOwners = User::where('role', User::ROLE_SHOP_OWNER)
            ->where(function ($query) use ($shop) {
                $query->doesntHave('shop')
                    ->orWhere('id', $shop->user_id);
            })
            ->select('id', 'name', 'email')
            ->get();

        return Inertia::render('Admin/Shops/Edit', [
            'shop' => $shop,
            'availableOwners' => $availableOwners,
        ]);
    }

    public function update(ShopRequest $request, Shop $shop): RedirectResponse
    {
        $this->shopService->updateShop($shop, $request->validated());

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $this->shopService->deleteShop($shop);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully.');
    }
}
