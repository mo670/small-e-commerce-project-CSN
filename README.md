# ShopSphere — Small E-Commerce Management System

> **Author:** [@mo670](https://github.com/mo670)  
> **Repository:** `https://github.com/mo670/small-e-commerce-project-CSN`  
> **Tech Stack:** Laravel 12 (PHP 8.2+) • Vue.js 3 (Inertia.js + Composition API + Tailwind CSS) • MySQL / SQLite Compatible

---

## 1. Project Overview & Architectural Design

ShopSphere is a multi-tenant e-commerce management system developed to fulfill the **Small E-commerce Management** requirements. It implements a **Shared Product Catalog** pattern where:
- **Administrators** and **Shop Owners** can contribute master products into a universal catalog.
- **Shop Owners** can browse the shared catalog and select products to list in their individual storefronts.
- **Shop Owners** have the autonomy to **override and customize product prices, marketing details, descriptions, and thumbnail images** specifically for their stores without affecting the master catalog or other merchants.
- **Customers** browse store-specific storefronts, **only see products assigned to that particular shop** with its customized pricing and thumbnail, and can add items to their **Shopping Cart** and complete checkout. Unassigned catalog products are strictly invisible to customers.

```
                          ┌────────────────────────┐
                          │     ADMINISTRATOR      │
                          └───────────┬────────────┘
                                      │ Manages Shops, Owners & Catalog
                                      ▼
                        ┌───────────────────────────┐
                        │   SHARED MASTER CATALOG   │
                        │   (products table)        │
                        └───────┬───────────┬───────┘
                                │           │
              Browse & Add      │           │ Browse & Add
           (With custom price)  │           │ (With custom price)
                                ▼           ▼
                     ┌──────────────┐   ┌──────────────┐
                     │ Shop A Pivot │   │ Shop B Pivot │
                     │(shop_products│   │(shop_products│
                     │  $1249.00)   │   │  $1299.00)   │
                     └──────┬───────┘   └──────┬───────┘
                            │                  │
                            ▼                  ▼
                     ┌──────────────┐   ┌──────────────┐
                     │  Customer    │   │  Customer    │
                     │ View: Shop A │   │ View: Shop B │
                     └──────────────┘   └──────────────┘
```

---

## 2. Functional Requirements Matrix

| Requirement | Implementation Details | Status |
|---|---|:---:|
| **Admin Authentication** | Secure Laravel multi-role auth, session protection, `EnsureUserIsAdmin` middleware. | ✅ Verified |
| **Admin Shop Management (CRUD)** | Create, list, edit, and delete shops, manage domain slugs, logos, banners, and status. | ✅ Verified |
| **Admin Product Management (CRUD)** | Full CRUD on the master catalog with SKU, category, base price, thumbnail, and descriptions. | ✅ Verified |
| **Manage Shop Owner Accounts** | Admin can create shop owners, manage status, and map/reassign them to stores. | ✅ Verified |
| **Browse Shared Catalog** | Shop owners can search and filter the shared catalog with one-click "Add to Shop". | ✅ Verified |
| **Shared Catalog Availability** | Products created by admin or any shop owner immediately appear in the shared catalog. | ✅ Verified |
| **Shop Owner Product Creation** | Shop owners can create products that save to the shared catalog and auto-assign to their shop. | ✅ Verified |
| **Shop-Specific Customization** | Shop owners can override and set custom prices, details, thumbnails, and stock for their shop. | ✅ Verified |
| **Customer Shop-Specific View** | Customers only see active products assigned to a visited shop with shop-specific prices & images. | ✅ Verified |
| **Unassigned Products Hidden** | Products unassigned to any shop are never visible in customer storefronts (tested via automated test). | ✅ Verified |
| **Customer Add to Cart & Checkout** | Persistent reactive shopping cart (`useCart`), slide-over cart drawer, quantity controls, and checkout. | ✅ Verified |

---

## 3. Technology Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Architecture:** Clean Architecture with Service Layer (`ProductCatalogService`, `ShopManagementService`, `StorefrontService`) & Form Requests
- **Frontend:** Vue.js 3 (`<script setup>` Composition API), Inertia.js, Tailwind CSS, Lucide Icons (`lucide-vue-next`)
- **Database:** SQLite (default for zero-configuration evaluation) or MySQL (fully supported via `.env`)
- **Testing:** PHPUnit / Laravel Feature Tests (100% passed, 34 tests, 101 assertions)

---

## 4. Setup & Running Instructions (Step-by-Step)

Follow these step-by-step instructions to set up and run the project from a fresh clone.

### Prerequisites
Make sure you have the following installed on your machine:
- **PHP >= 8.2** with `pdo_sqlite` or `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- **Composer** (v2.x)
- **Node.js** (v18+ or v20+) and **npm**

---

### Step 1: Clone the Repository
```bash
git clone https://github.com/mo670/small-e-commerce-project-CSN.git
cd small-e-commerce-project-CSN
```

### Step 2: Install PHP & Node Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Setup
Copy the example environment file:
```bash
# Linux / macOS / Git Bash:
cp .env.example .env

# Windows PowerShell:
copy .env.example .env
```

Generate application encryption key:
```bash
php artisan key:generate
```

### Step 4: Storage Symlink
Connect the public storage symlink for uploaded product/shop images:
```bash
php artisan storage:link
```

### Step 5: Database Setup & Demo Seeding

#### Option A: SQLite (Quickest, Zero Configuration — Default in `.env.example`)
Ensure SQLite is selected in `.env`:
```env
DB_CONNECTION=sqlite
```
Create the SQLite database file if it does not exist:
```bash
# Linux / macOS:
touch database/database.sqlite

# Windows PowerShell:
if (!(Test-Path database/database.sqlite)) { New-Item database/database.sqlite }
```

Run migrations and seed realistic demo data:
```bash
php artisan migrate:fresh --seed
```

#### Option B: MySQL
If you prefer running on MySQL, create an empty database `ecommerce_management` in your MySQL server and configure `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_management
DB_USERNAME=root
DB_PASSWORD=your_password
```
Then run:
```bash
php artisan migrate:fresh --seed
```

### Step 6: Build Frontend Assets
Build the production assets with Vite:
```bash
npm run build
```
*(Or run `npm run dev` in a separate terminal during development)*

### Step 7: Start the Local Development Server
```bash
php artisan serve
```
The application will now be live at **`http://localhost:8000`** (or `http://127.0.0.1:8000`).

---

## 5. Demo Accounts & Test Credentials

The database seeder automatically creates pre-configured accounts and stores. You can also use the **1-Click Quick Demo Login** buttons on the `/login` page:

| Role | Email | Password | Assigned Shop | Notes |
|---|---|---|---|---|
| **System Administrator** | `admin@example.com` | `password` | — | Access to `/admin/dashboard`, CRUD on all shops, owners, and catalog |
| **Shop Owner 1** | `techowner@example.com` | `password` | **Nexus Tech Hub** | Electronics & Gadgets Store (`/shop/nexus-tech-hub`) |
| **Shop Owner 2** | `fashionowner@example.com` | `password` | **Aura Lifestyle** | Apparel & Accessories Store (`/shop/aura-lifestyle`) |
| **Shop Owner 3** | `urbanowner@example.com` | `password` | **Urban Craft & Living** | Furniture & Living Store (`/shop/urban-craft-living`) |

---

## 6. How to Verify Core Requirements

### 1. Test Admin Panel
1. Log in as `admin@example.com` / `password`.
2. Go to **Shops** (`/admin/shops`) → Create, Edit, or Delete shops.
3. Go to **Shop Owners** (`/admin/shop-owners`) → Create merchant logins and link them to stores.
4. Go to **Shared Catalog** (`/admin/products`) → Add, Edit, or Delete products in the universal catalog.

### 2. Test Shop Owner & Shared Catalog Flow
1. Log in as `techowner@example.com` / `password`.
2. Navigate to **Shared Catalog** (`/shop-owner/catalog`):
   - Browse all products created by Admin or other merchants.
   - Click **"Add to Shop"** on any item. A modal appears allowing you to specify **your own price**, **custom details**, and **custom thumbnail**.
3. Navigate to **My Shop Products** (`/shop-owner/my-products`):
   - Modify prices, descriptions, thumbnails, and stock counts.
4. Click **Create Product** (`/shop-owner/my-products/create`):
   - Fill in product information.
   - Upon submission, the product is published to the **shared catalog** for all other merchants, and automatically added to your store.
5. Log in as `fashionowner@example.com` and verify that the new product created by `techowner` is now visible in the shared catalog ready to be added.

### 3. Test Customer Storefront, Visibility Rule & Shopping Cart
1. Visit the public home page at **`http://localhost:8000`**.
2. Click on **"Nexus Tech Hub"** (`/shop/nexus-tech-hub`):
   - Observe only products assigned to Nexus Tech Hub are shown.
   - Notice the shop-specific price overrides (e.g. MacBook Air listed at $1249.00 with Tech Hub warranty bundle).
   - Click **"Add to Cart"** on any product or open a product page to select quantity and click **"Buy Now"** to open the interactive slide-over Cart Drawer and complete checkout.
3. Click on **"Aura Lifestyle"** (`/shop/aura-lifestyle`):
   - Notice only apparel and lifestyle products assigned to Aura are shown.
   - Observe that the *Stainless Steel Tumbler* shared product is listed at $35.00 with custom Aura styling, while in *Urban Craft & Living* it is listed at $30.00.
4. **Unassigned Products Test:**
   - In the seeder, the product **"DJI Mini 4 Pro Drone (Unassigned Catalog Item)"** exists in the master catalog but is NOT assigned to any shop.
   - Browse any shop storefront — this product is **strictly invisible** to customers, fulfilling the core rule: *"Products that are not assigned to any shop must not be visible to customers."*

---

## 7. Running Automated Tests

A comprehensive test suite is included in `tests/Feature/EcommerceManagementTest.php`.

Run the test suite:
```bash
php artisan test
```

Expected output:
```
PASS  Tests\Feature\EcommerceManagementTest
✓ admin can access admin panel
✓ non admin cannot access admin panel
✓ admin can create shop
✓ admin can create shop owner account
✓ admin can create catalog product
✓ shop owner can add catalog product to own shop
✓ shop owner created product becomes available in shared catalog
✓ shop owner can modify shop product price and details
✓ customer only sees products assigned to visited shop

Tests: 34 passed (101 assertions)
```

---

## 8. Directory & Architecture Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/               # Admin Dashboard, Shops, Owners, Products
│   │   │   ├── ShopOwner/           # Merchant Dashboard, Catalog, MyProducts
│   │   │   ├── Storefront/          # Public Customer Storefront & Shop Views
│   │   │   └── UploadController.php # Direct image upload handler
│   │   ├── Middleware/
│   │   │   ├── EnsureUserIsAdmin.php
│   │   │   ├── EnsureUserIsShopOwner.php
│   │   │   └── HandleInertiaRequests.php
│   │   └── Requests/                # Form validation classes
│   ├── Models/
│   │   ├── User.php                 # Multi-role authentication (admin, shop_owner, customer)
│   │   ├── Shop.php                 # Tenant shop entity
│   │   ├── Product.php              # Master shared catalog entity
│   │   └── ShopProduct.php          # Tenant pivot (custom price, details, thumbnail, stock)
│   └── Services/                    # Service layer architecture
│       ├── ProductCatalogService.php
│       ├── ShopManagementService.php
│       └── StorefrontService.php
├── database/
│   ├── migrations/                  # Schema definitions with foreign keys & indexes
│   └── seeders/DatabaseSeeder.php   # Demo admin, shop owners, shops, and products
├── resources/
│   └── js/
│       ├── Components/              # Reusable UI (CartDrawer, ImageUploader, Pagination, StatCard, Toast)
│       ├── Composables/             # Reactive state (useCart.js)
│       ├── Layouts/                 # AdminLayout, ShopOwnerLayout, StorefrontLayout
│       └── Pages/                   # Vue 3 Inertia page components
└── routes/
    └── web.php                      # Organized route definitions with role-gated groups
```
