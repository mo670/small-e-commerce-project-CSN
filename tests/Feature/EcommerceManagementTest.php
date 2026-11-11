<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $shopOwner1;
    protected User $shopOwner2;
    protected Shop $shop1;
    protected Shop $shop2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);

        // 2. Create Shop Owners
        $this->shopOwner1 = User::factory()->create([
            'role' => User::ROLE_SHOP_OWNER,
            'name' => 'Shop Owner 1',
            'email' => 'owner1@test.com',
        ]);

        $this->shopOwner2 = User::factory()->create([
            'role' => User::ROLE_SHOP_OWNER,
            'name' => 'Shop Owner 2',
            'email' => 'owner2@test.com',
        ]);

        // 3. Create Shops
        $this->shop1 = Shop::create([
            'user_id' => $this->shopOwner1->id,
            'name' => 'Alpha Gadgets',
            'slug' => 'alpha-gadgets',
            'is_active' => true,
        ]);

        $this->shop2 = Shop::create([
            'user_id' => $this->shopOwner2->id,
            'name' => 'Beta Fashion',
            'slug' => 'beta-fashion',
            'is_active' => true,
        ]);
    }

    /**
     * Requirement: Admin Authentication & Access.
     */
    public function test_admin_can_access_admin_panel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $response = $this->actingAs($this->shopOwner1)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Requirement: Shop Management (CRUD) by Admin.
     */
    public function test_admin_can_create_shop(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shops.store'), [
            'name' => 'New Awesome Store',
            'slug' => 'new-awesome-store',
            'description' => 'A great store',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.shops.index'));
        $this->assertDatabaseHas('shops', ['slug' => 'new-awesome-store']);
    }

    /**
     * Requirement: Create and manage shop owner accounts by Admin.
     */
    public function test_admin_can_create_shop_owner_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shop-owners.store'), [
            'name' => 'New Merchant',
            'email' => 'merchant@test.com',
            'password' => 'password123',
            'phone' => '+8801700000000',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.shop-owners.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'merchant@test.com',
            'role' => User::ROLE_SHOP_OWNER,
        ]);
    }

    /**
     * Requirement: Product management (CRUD) by Admin in shared catalog.
     */
    public function test_admin_can_create_catalog_product(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Universal Wireless Mouse',
            'category' => 'Electronics',
            'sku' => 'MOUSE-001',
            'price' => 50.00,
            'description' => 'Universal mouse',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'title' => 'Universal Wireless Mouse',
            'price' => 50.00,
        ]);
    }

    /**
     * Requirement: Shop owners can browse the shared catalog and add products to their shops.
     */
    public function test_shop_owner_can_add_catalog_product_to_own_shop(): void
    {
        $product = Product::create([
            'created_by' => $this->admin->id,
            'title' => 'Mechanical Keyboard',
            'category' => 'Accessories',
            'sku' => 'KEY-001',
            'price' => 100.00,
            'description' => 'High quality keyboard',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->shopOwner1)->post(route('shop-owner.catalog.assign'), [
            'product_id' => $product->id,
            'price' => 120.00, // Shop 1 custom price!
            'details' => 'Custom Shop 1 Special Warranty Included',
            'stock' => 15,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('shop_products', [
            'shop_id' => $this->shop1->id,
            'product_id' => $product->id,
            'price' => 120.00,
            'details' => 'Custom Shop 1 Special Warranty Included',
        ]);
    }

    /**
     * Requirement: Shop owners can create their own products, which should also become available in the shared catalog.
     */
    public function test_shop_owner_created_product_becomes_available_in_shared_catalog(): void
    {
        $response = $this->actingAs($this->shopOwner1)->post(route('shop-owner.my-products.store'), [
            'title' => 'Handmade Leather Wallet',
            'category' => 'Accessories',
            'catalog_price' => 45.00,
            'shop_price' => 40.00, // Custom discounted shop price
            'description' => 'Genuine top-grain leather',
            'stock' => 20,
        ]);

        $response->assertRedirect(route('shop-owner.my-products.index'));

        // 1. Must exist in shared master catalog
        $this->assertDatabaseHas('products', [
            'title' => 'Handmade Leather Wallet',
            'created_by' => $this->shopOwner1->id,
            'price' => 45.00,
        ]);

        // 2. Must be listed in shopOwner1's shop
        $this->assertDatabaseHas('shop_products', [
            'shop_id' => $this->shop1->id,
            'price' => 40.00,
        ]);

        // 3. ShopOwner 2 must now be able to see it in the shared catalog!
        $catalogResponse = $this->actingAs($this->shopOwner2)->get(route('shop-owner.catalog.index'));
        $catalogResponse->assertStatus(200);
    }

    /**
     * Requirement: Shop owners can set or modify product prices, details, and thumbnails for their own shops.
     */
    public function test_shop_owner_can_modify_shop_product_price_and_details(): void
    {
        $product = Product::create([
            'created_by' => $this->admin->id,
            'title' => 'Smart Watch Pro',
            'price' => 200.00,
            'description' => 'Catalog description',
            'is_active' => true,
        ]);

        $shopProduct = ShopProduct::create([
            'shop_id' => $this->shop1->id,
            'product_id' => $product->id,
            'price' => 210.00,
            'details' => 'Initial details',
            'stock' => 5,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->shopOwner1)->put(
            route('shop-owner.my-products.update', $shopProduct->id),
            [
                'price' => 199.99,
                'details' => 'Updated Store Promo: Free Screen Guard!',
                'thumbnail' => 'https://example.com/custom-watch.jpg',
                'stock' => 10,
                'is_active' => true,
            ]
        );

        $response->assertRedirect(route('shop-owner.my-products.index'));
        $this->assertDatabaseHas('shop_products', [
            'id' => $shopProduct->id,
            'price' => 199.99,
            'details' => 'Updated Store Promo: Free Screen Guard!',
            'thumbnail' => 'https://example.com/custom-watch.jpg',
        ]);
    }

    /**
     * Requirement: Customer View
     * Customers should only see products assigned to a shop.
     * Display shop-specific product information, prices, and thumbnails.
     * Products that are not assigned to any shop must not be visible to customers.
     */
    public function test_customer_only_sees_products_assigned_to_visited_shop(): void
    {
        // Product A assigned to Shop 1
        $productA = Product::create([
            'title' => 'Assigned to Shop 1 Only',
            'price' => 50.00,
            'is_active' => true,
        ]);
        ShopProduct::create([
            'shop_id' => $this->shop1->id,
            'product_id' => $productA->id,
            'price' => 55.00, // Shop 1 specific price
            'details' => 'Shop 1 specific description',
            'is_active' => true,
        ]);

        // Product B assigned to Shop 2
        $productB = Product::create([
            'title' => 'Assigned to Shop 2 Only',
            'price' => 70.00,
            'is_active' => true,
        ]);
        ShopProduct::create([
            'shop_id' => $this->shop2->id,
            'product_id' => $productB->id,
            'price' => 75.00,
            'details' => 'Shop 2 specific description',
            'is_active' => true,
        ]);

        // Product C in shared catalog BUT UNASSIGNED TO ANY SHOP
        $productC = Product::create([
            'title' => 'Completely Unassigned Product',
            'price' => 999.00,
            'is_active' => true,
        ]);

        // 1. Visit Shop 1 Storefront
        $responseShop1 = $this->get(route('storefront.shop', $this->shop1->slug));
        $responseShop1->assertStatus(200);

        // Assert Shop 1 sees its assigned Product A
        $responseShop1->assertInertia(fn ($page) => 
            $page->component('Storefront/ShopShow')
                ->has('shopProducts.data', 1)
                ->where('shopProducts.data.0.product.id', $productA->id)
                ->where('shopProducts.data.0.price', '55.00') // Shop-specific price!
                ->where('shopProducts.data.0.details', 'Shop 1 specific description')
        );

        // 2. Direct attempt to view unassigned Product C on any shop must return 404
        $responseUnassigned = $this->get(route('storefront.product', [$this->shop1->slug, 9999]));
        $responseUnassigned->assertStatus(404);
    }
}

