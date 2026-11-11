<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopOwner\ProductCreateRequest;
use App\Http\Requests\ShopOwner\ShopProductUpdateRequest;
use App\Models\Product;
use App\Models\ShopProduct;
use App\Services\ProductCatalogService;
use App\Services\ShopManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyProductController extends Controller
{
    public function __construct(
        protected ProductCatalogService $catalogService,
        protected ShopManagementService $shopService
    ) {}

    public function index(Request $request): Response
    {
        $shop = $request->user()->shop;
        if (! $shop) {
            abort(403, 'No shop found for your account.');
        }

        $search = $request->input('search');
        $status = $request->input('status');

        $query = ShopProduct::where('shop_id', $shop->id)
            ->with(['product.creator']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('details', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', (bool) $status);
        }

        $shopProducts = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('ShopOwner/MyProducts/Index', [
            'shopProducts' => $shopProducts,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
            'shop' => $shop,
        ]);
    }

    public function create(): Response
    {
        $categories = Product::whereNotNull('category')->distinct()->pluck('category');

        return Inertia::render('ShopOwner/MyProducts/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Create product: It becomes available in shared catalog AND is linked to current shop.
     */
    public function store(ProductCreateRequest $request): RedirectResponse
    {
        $shop = $request->user()->shop;
        if (! $shop) {
            abort(403, 'No shop found.');
        }

        $data = $request->validated();

        // 1. Create master product in shared catalog
        $catalogProduct = $this->catalogService->createCatalogProduct([
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'sku' => $data['sku'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['catalog_price'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'is_active' => true,
        ], $request->user());

        // 2. Automatically assign product to this shop with shop-specific price/details
        $shopPrice = ! empty($data['shop_price']) ? $data['shop_price'] : $data['catalog_price'];

        $this->shopService->assignProductToShop($shop, $catalogProduct, [
            'price' => $shopPrice,
            'details' => $data['description'] ?? null,
            'thumbnail' => $data['thumbnail'] ?? null,
            'stock' => $data['stock'] ?? 10,
            'is_active' => true,
        ]);

        return redirect()->route('shop-owner.my-products.index')
            ->with('success', "'{$catalogProduct->title}' was added to the shared catalog and listed in your shop!");
    }

    public function edit(ShopProduct $shopProduct): Response
    {
        $shop = request()->user()->shop;
        if ($shopProduct->shop_id !== $shop?->id) {
            abort(403, 'Unauthorized shop product edit.');
        }

        $shopProduct->load('product.creator');

        return Inertia::render('ShopOwner/MyProducts/Edit', [
            'shopProduct' => $shopProduct,
        ]);
    }

    public function update(ShopProductUpdateRequest $request, ShopProduct $shopProduct): RedirectResponse
    {
        $shop = $request->user()->shop;
        if ($shopProduct->shop_id !== $shop?->id) {
            abort(403, 'Unauthorized shop product update.');
        }

        $this->shopService->updateShopProduct($shopProduct, $request->validated());

        return redirect()->route('shop-owner.my-products.index')
            ->with('success', 'Shop product details updated successfully!');
    }

    public function destroy(ShopProduct $shopProduct): RedirectResponse
    {
        $shop = request()->user()->shop;
        if ($shopProduct->shop_id !== $shop?->id) {
            abort(403, 'Unauthorized shop product deletion.');
        }

        $this->shopService->removeProductFromShop($shop, $shopProduct->id);

        return redirect()->route('shop-owner.my-products.index')
            ->with('success', 'Product removed from your shop (remains in shared catalog).');
    }
}

