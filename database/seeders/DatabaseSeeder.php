<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Administrator
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'phone' => '+880 1711 000001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Shop Owners
        $techOwner = User::firstOrCreate(
            ['email' => 'techowner@example.com'],
            [
                'name' => 'Rahim Tech',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SHOP_OWNER,
                'phone' => '+880 1811 000002',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $fashionOwner = User::firstOrCreate(
            ['email' => 'fashionowner@example.com'],
            [
                'name' => 'Fatima Style',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SHOP_OWNER,
                'phone' => '+880 1911 000003',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $urbanOwner = User::firstOrCreate(
            ['email' => 'urbanowner@example.com'],
            [
                'name' => 'Tanvir Living',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SHOP_OWNER,
                'phone' => '+880 1611 000004',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Shops and assign to owners
        $techShop = Shop::firstOrCreate(
            ['slug' => 'nexus-tech-hub'],
            [
                'user_id' => $techOwner->id,
                'name' => 'Nexus Tech Hub',
                'description' => 'Your trusted hub for premium gadgets, laptops, audio gear and desk accessories.',
                'logo' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=200&h=200&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200&h=400&fit=crop&q=80',
                'email' => 'contact@nexustech.io',
                'phone' => '+880 1811 000002',
                'address' => 'Floor 4, Bashundhara City, Dhaka',
                'is_active' => true,
            ]
        );

        $fashionShop = Shop::firstOrCreate(
            ['slug' => 'aura-lifestyle'],
            [
                'user_id' => $fashionOwner->id,
                'name' => 'Aura Lifestyle & Apparel',
                'description' => 'Modern, sustainable minimalist fashion, daily wear, and curated handcrafted lifestyle goods.',
                'logo' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200&h=200&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=1200&h=400&fit=crop&q=80',
                'email' => 'hello@auralifestyle.com',
                'phone' => '+880 1911 000003',
                'address' => 'Banani Road 11, Dhaka',
                'is_active' => true,
            ]
        );

        $urbanShop = Shop::firstOrCreate(
            ['slug' => 'urban-craft-living'],
            [
                'user_id' => $urbanOwner->id,
                'name' => 'Urban Craft & Living',
                'description' => 'Handpicked home decor, ergonomic workspace furnishings and lifestyle essentials.',
                'logo' => 'https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?w=200&h=200&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200&h=400&fit=crop&q=80',
                'email' => 'support@urbancraft.com',
                'phone' => '+880 1611 000004',
                'address' => 'Dhanmondi 27, Dhaka',
                'is_active' => true,
            ]
        );

        // 4. Create Shared Products (created by Admin and by Shop Owners)
        $productsData = [
            [
                'created_by' => $admin->id,
                'title' => 'Apple MacBook Air 15-inch M3',
                'slug' => 'apple-macbook-air-15-m3',
                'category' => 'Computers',
                'sku' => 'APL-MBA-M3-15',
                'description' => 'Strikingly thin and fast with the M3 chip. Delivers up to 18 hours of battery life and a stunning Liquid Retina display.',
                'price' => 1299.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $admin->id,
                'title' => 'Sony WH-1000XM5 Wireless Headphones',
                'slug' => 'sony-wh-1000xm5-headphones',
                'category' => 'Audio',
                'sku' => 'SNY-WH-XM5',
                'description' => 'Industry-leading noise cancellation with two processors and eight microphones for unprecedented sound quality.',
                'price' => 399.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $techOwner->id, // Created by shop owner! Available in shared catalog!
                'title' => 'Logitech MX Master 3S Wireless Mouse',
                'slug' => 'logitech-mx-master-3s',
                'category' => 'Accessories',
                'sku' => 'LOG-MX-3S',
                'description' => 'Quiet clicks with 8000 DPI track-on-glass sensor. Ultimate precision and ergonomic comfort for professionals.',
                'price' => 99.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $admin->id,
                'title' => 'Keychron Q1 Pro Mechanical Keyboard',
                'slug' => 'keychron-q1-pro-keyboard',
                'category' => 'Accessories',
                'sku' => 'KEY-Q1-PRO',
                'description' => 'Full metal custom wireless mechanical keyboard with hot-swappable switches and QMK/VIA support.',
                'price' => 199.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $fashionOwner->id, // Created by fashion owner! Available in shared catalog!
                'title' => 'Handcrafted Full-Grain Leather Duffle Bag',
                'slug' => 'leather-duffle-bag',
                'category' => 'Lifestyle',
                'sku' => 'L声明-BAG-01',
                'description' => 'Exquisite artisan crafted genuine pull-up leather weekend travel bag with antique brass hardware.',
                'price' => 189.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $fashionOwner->id,
                'title' => 'Minimalist Heavyweight Cotton Hoodie',
                'slug' => 'minimalist-cotton-hoodie',
                'category' => 'Apparel',
                'sku' => 'APL-HD-001',
                'description' => '450 GSM organic French terry cotton hoodie. Pre-shrunk, drop-shoulder relaxed silhouette for all-day comfort.',
                'price' => 65.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $admin->id,
                'title' => 'Stainless Steel Insulated Tumbler 500ml',
                'slug' => 'insulated-thermal-tumbler',
                'category' => 'Lifestyle',
                'sku' => 'L声明-TMB-500',
                'description' => 'Double-wall vacuum insulation keeps drinks cold for 24 hours or hot for 12 hours. Sweat-free powder finish.',
                'price' => 32.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1577937927133-66ef06acdf18?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $urbanOwner->id,
                'title' => 'Ergonomic Mesh Office Chair with Lumbar Support',
                'slug' => 'ergonomic-mesh-chair',
                'category' => 'Furniture',
                'sku' => 'FRN-CHR-ERG1',
                'description' => 'Breathable Korean mesh with 3D adjustable armrests and dynamic self-adjusting lumbar support.',
                'price' => 280.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1580481077195-c3a821a506cb?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $admin->id,
                'title' => 'Canon EOS R50 Mirrorless Camera + 18-45mm Lens',
                'slug' => 'canon-eos-r50-camera',
                'category' => 'Cameras',
                'sku' => 'CAM-CAN-R50',
                'description' => 'Compact and lightweight 24.2 MP APS-C sensor with 4K uncropped video and Dual Pixel CMOS AF II.',
                'price' => 749.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'created_by' => $admin->id,
                // THIS PRODUCT WILL REMAIN UNASSIGNED TO ANY SHOP
                // To prove that "Products that are not assigned to any shop must not be visible to customers"
                'title' => 'DJI Mini 4 Pro Drone (Unassigned Catalog Item)',
                'slug' => 'dji-mini-4-pro-drone-unassigned',
                'category' => 'Drones',
                'sku' => 'DRN-DJI-M4P',
                'description' => 'Under 249g sub-mini drone with Omnidirectional Obstacle Sensing and 4K/60fps HDR True Vertical Shooting. Note: Unassigned to any shop.',
                'price' => 859.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1527977966376-1c8408f9f108?w=600&h=600&fit=crop&q=80',
                'is_active' => true,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $createdProducts[$data['slug']] = Product::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 5. Assign Products to Shops with Shop-Specific Overrides
        // Nexus Tech Hub Listings:
        ShopProduct::firstOrCreate(
            ['shop_id' => $techShop->id, 'product_id' => $createdProducts['apple-macbook-air-15-m3']->id],
            [
                'price' => 1249.00, // Tech Hub discount price! (Base was 1299)
                'details' => '★ Special Tech Hub Deal: Includes 2-Year Official Apple Warranty & Free Neoprene Laptop Sleeve.',
                'thumbnail' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=600&fit=crop&q=80',
                'stock' => 15,
                'is_active' => true,
            ]
        );

        ShopProduct::firstOrCreate(
            ['shop_id' => $techShop->id, 'product_id' => $createdProducts['sony-wh-1000xm5-headphones']->id],
            [
                'price' => 379.00, // Tech Hub custom price (Base was 399)
                'details' => 'Brand new sealed unit with authentic Sony Bangladesh warranty card.',
                'thumbnail' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop&q=80',
                'stock' => 25,
                'is_active' => true,
            ]
        );

        ShopProduct::firstOrCreate(
            ['shop_id' => $techShop->id, 'product_id' => $createdProducts['logitech-mx-master-3s']->id],
            [
                'price' => 95.00,
                'details' => 'Space Gray edition. Fast USB-C charging cable included.',
                'thumbnail' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600&h=600&fit=crop&q=80',
                'stock' => 40,
                'is_active' => true,
            ]
        );

        ShopProduct::firstOrCreate(
            ['shop_id' => $techShop->id, 'product_id' => $createdProducts['keychron-q1-pro-keyboard']->id],
            [
                'price' => 195.00,
                'details' => 'Gateron Jupiter Brown tactile switches pre-lubed from factory.',
                'thumbnail' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&h=600&fit=crop&q=80',
                'stock' => 12,
                'is_active' => true,
            ]
        );

        ShopProduct::firstOrCreate(
            ['shop_id' => $techShop->id, 'product_id' => $createdProducts['canon-eos-r50-camera']->id],
            [
                'price' => 720.00,
                'details' => 'Vlogger starter bundle: Includes 64GB SanDisk Extreme SD card free.',
                'thumbnail' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&h=600&fit=crop&q=80',
                'stock' => 8,
                'is_active' => true,
            ]
        );

        // Aura Lifestyle Listings:
        ShopProduct::firstOrCreate(
            ['shop_id' => $fashionShop->id, 'product_id' => $createdProducts['leather-duffle-bag']->id],
            [
                'price' => 189.00,
                'details' => 'Crafted from vegetable tanned pull-up leather. Comes in luxury linen dust bag.',
                'thumbnail' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=600&fit=crop&q=80',
                'stock' => 20,
                'is_active' => true,
            ]
        );

        ShopProduct::firstOrCreate(
            ['shop_id' => $fashionShop->id, 'product_id' => $createdProducts['minimalist-cotton-hoodie']->id],
            [
                'price' => 59.00, // Aura special seasonal discount
                'details' => 'Autumn collection - Oatmeal Heather colorway. Unisex relaxed cut.',
                'thumbnail' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&h=600&fit=crop&q=80',
                'stock' => 35,
                'is_active' => true,
            ]
        );

        // Note: Aura ALSO carries the Tumbler from shared catalog with their own price & styling!
        ShopProduct::firstOrCreate(
            ['shop_id' => $fashionShop->id, 'product_id' => $createdProducts['insulated-thermal-tumbler']->id],
            [
                'price' => 35.00, // Aura custom price
                'details' => 'Matte Sage Green finish with laser engraved Aura botanical motif.',
                'thumbnail' => 'https://images.unsplash.com/photo-1577937927133-66ef06acdf18?w=600&h=600&fit=crop&q=80',
                'stock' => 50,
                'is_active' => true,
            ]
        );

        // Urban Craft & Living Listings:
        ShopProduct::firstOrCreate(
            ['shop_id' => $urbanShop->id, 'product_id' => $createdProducts['ergonomic-mesh-chair']->id],
            [
                'price' => 275.00,
                'details' => 'All-black edition with smooth-gliding silent polyurethane casters.',
                'thumbnail' => 'https://images.unsplash.com/photo-1580481077195-c3a821a506cb?w=600&h=600&fit=crop&q=80',
                'stock' => 10,
                'is_active' => true,
            ]
        );

        ShopProduct::firstOrCreate(
            ['shop_id' => $urbanShop->id, 'product_id' => $createdProducts['insulated-thermal-tumbler']->id],
            [
                'price' => 30.00, // Urban Craft price is $30
                'details' => 'Minimalist Charcoal Grey. Essential companion for your work desk.',
                'thumbnail' => 'https://images.unsplash.com/photo-1577937927133-66ef06acdf18?w=600&h=600&fit=crop&q=80',
                'stock' => 30,
                'is_active' => true,
            ]
        );
    }
}
