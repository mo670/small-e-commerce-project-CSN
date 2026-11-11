<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Services\ProductCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        protected ProductCatalogService $catalogService
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $query = Product::with(['creator', 'shopProducts'])
            ->withCount('shopProducts');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Product::whereNotNull('category')->distinct()->pluck('category');

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $category,
            ],
        ]);
    }

    public function create(): Response
    {
        $categories = Product::whereNotNull('category')->distinct()->pluck('category');

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $this->catalogService->createCatalogProduct($request->validated(), $request->user());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product added to the shared catalog successfully!');
    }

    public function edit(Product $product): Response
    {
        $product->load(['creator', 'shopProducts.shop']);
        $categories = Product::whereNotNull('category')->distinct()->pluck('category');

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->catalogService->updateCatalogProduct($product, $request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Catalog product updated successfully!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->catalogService->deleteCatalogProduct($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product removed from catalog.');
    }
}

