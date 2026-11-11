<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopOwner\AssignCatalogProductRequest;
use App\Models\Product;
use App\Models\ShopProduct;
use App\Services\ProductCatalogService;
use App\Services\ShopManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function __construct(
        protected ProductCatalogService $catalogService,
        protected ShopManagementService $shopService
    ) {}

    public function index(Request $request): Response
    {
        $shop = $request->user()->shop;
        if (! $shop) {
            abort(403, 'No shop found for this account.');
        }

        $filters = $request->only(['search', 'category']);
        $catalog = $this->catalogService->getSharedCatalog($filters, null, 12);

        // Get IDs of products already added to this shop
        $existingProductIds = ShopProduct::where('shop_id', $shop->id)
            ->pluck('product_id')
            ->toArray();

        $categories = Product::whereNotNull('category')->distinct()->pluck('category');

        return Inertia::render('ShopOwner/Catalog/Index', [
            'catalog' => $catalog,
            'existingProductIds' => $existingProductIds,
            'categories' => $categories,
            'filters' => $filters,
            'shop' => $shop,
        ]);
    }

    public function assign(AssignCatalogProductRequest $request): RedirectResponse
    {
        $shop = $request->user()->shop;
        if (! $shop) {
            abort(403, 'No shop found.');
        }

        $product = Product::findOrFail($request->validated('product_id'));

        $this->shopService->assignProductToShop($shop, $product, [
            'price' => $request->validated('price'),
            'details' => $request->validated('details'),
            'thumbnail' => $request->validated('thumbnail'),
            'stock' => $request->validated('stock', 10),
            'is_active' => $request->validated('is_active', true),
        ]);

        return redirect()->back()
            ->with('success', "'{$product->title}' has been added to your shop catalog!");
    }
}
