<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\ShopOwnerController as AdminShopOwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopOwner\CatalogController as ShopOwnerCatalogController;
use App\Http\Controllers\ShopOwner\MyProductController as ShopOwnerMyProductController;
use App\Http\Controllers\ShopOwner\ShopOwnerDashboardController;
use App\Http\Controllers\Storefront\StorefrontController;
use App\Http\Controllers\UploadController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/shop/{slug}', [StorefrontController::class, 'shopShow'])->name('storefront.shop');
Route::get('/shop/{slug}/product/{shopProduct}', [StorefrontController::class, 'productShow'])->name('storefront.product');

// Image upload helper
Route::post('/api/upload', [UploadController::class, 'upload'])->middleware('auth')->name('api.upload');

// Smart Dashboard Redirect
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    if ($user->role === User::ROLE_ADMIN) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === User::ROLE_SHOP_OWNER) {
        return redirect()->route('shop-owner.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('shops', AdminShopController::class);
    Route::resource('shop-owners', AdminShopOwnerController::class);
    Route::resource('products', AdminProductController::class);
});

// Shop Owner Panel Routes
Route::middleware(['auth', 'shop_owner'])->prefix('shop-owner')->name('shop-owner.')->group(function () {
    Route::get('/dashboard', [ShopOwnerDashboardController::class, 'index'])->name('dashboard');

    // Shared Catalog
    Route::get('/catalog', [ShopOwnerCatalogController::class, 'index'])->name('catalog.index');
    Route::post('/catalog/assign', [ShopOwnerCatalogController::class, 'assign'])->name('catalog.assign');

    // Shop Product Listings
    Route::resource('my-products', ShopOwnerMyProductController::class)->parameters([
        'my-products' => 'shopProduct',
    ]);
});

// Common Authenticated Routes (Profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
