# NORA WORLD — Full Project Documentation & Migration Guide

> **Last updated:** August 26, 2026
> **Project version:** Laravel 13.29.0 · PHP 8.5.4 · Filament 3

---

## Table of Contents

1. [Project Summary](#1-project-summary)
2. [Technology Stack](#2-technology-stack)
3. [Server Requirements](#3-server-requirements)
4. [Complete File Structure](#4-complete-file-structure)
5. [Database Schema — All 30 Tables](#5-database-schema--all-30-tables)
6. [All Models, Controllers, and Resources](#6-all-models-controllers-and-resources)
7. [Environment Variables](#7-environment-variables)
8. [Backup Procedure (Current Server)](#8-backup-procedure-current-server)
9. [Migration Procedure (New Server)](#9-migration-procedure-new-server)
10. [Post-Migration Checklist](#10-post-migration-checklist)
11. [PayPal Configuration](#11-paypal-configuration)
12. [IIS Configuration](#12-iis-configuration)
13. [Seeded Data Summary](#13-seeded-data-summary)
14. [Test Suite Summary](#14-test-suite-summary)
15. [Troubleshooting](#15-troubleshooting)

---

## 1. Project Summary

NORA WORLD is a full-featured English-language e-commerce platform selling authentic handmade and heritage-inspired home products from **Jordan** and **Palestine**. It targets customers in the **United States** and **Europe**.

### What Was Built

| Feature | Status | Details |
|---|---|---|
| Laravel 13 Foundation | ✅ | Full MVC application with Breeze auth |
| Filament 3 Admin Panel | ✅ | 10 admin resources for full CRUD |
| Database (30 tables) | ✅ | Products, orders, payments, shipping, content |
| 21 Eloquent Models | ✅ | Full relationships, scopes, accessors |
| Storefront (14+ pages) | ✅ | Homepage, products, cart, checkout, orders |
| PayPal Checkout | ✅ | Server-side order creation, capture, validation |
| Shipping System | ✅ | 5 zones, configurable methods, cost calculation |
| USD Currency | ✅ | All prices in USD with $ symbol |
| Live Search Autocomplete | ✅ | Alpine.js with debounced API, keyboard nav |
| Image Upload System | ✅ | Filament FileUpload with editor, gallery management |
| Premium Animations | ✅ | Scroll reveals, hover effects, hero animations |
| Product Image Upload | ✅ | 1:1 crop, 5MB limit, alt text, primary toggle |
| Test Suite (60 tests) | ✅ | 129 assertions — all passing |
| Documentation | ✅ | README.md, ARCHITECTURE.md, MIGRATION.md |

---

## 2. Technology Stack

| Layer | Technology | Version |
|---|---|---|
| Language | PHP | 8.5.4 |
| Framework | Laravel | 13.29.0 |
| Admin Panel | Filament | 3.x |
| Auth | Laravel Breeze | Blade-based |
| CSS Framework | Tailwind CSS | 3.x (via Vite) |
| JavaScript | Alpine.js | 3.x |
| Build Tool | Vite | 8.x |
| Database | MySQL / SQLite (testing) | — |
| Payments | PayPal REST API + JS SDK | v2 |
| Fonts | Google Fonts (Figtree, Playfair Display) | — |

### Composer Packages (Direct)

- `laravel/framework` — Core framework
- `laravel/breeze` — Auth scaffolding
- `filament/filament` — Admin panel
- `filament/forms` — Form builder
- `filament/tables` — Table builder
- `livewire/livewire` — Reactive components (Filament dependency)

### NPM Packages

- `tailwindcss` — CSS utility framework
- `@tailwindcss/forms` — Form styling plugin
- `alpinejs` — Lightweight JS framework
- `vite` — Build tool
- `laravel-vite-plugin` — Laravel Vite integration

---

## 3. Server Requirements

### Minimum Requirements

| Requirement | Minimum | Recommended |
|---|---|---|
| PHP | 8.2 | 8.5 |
| MySQL | 5.7 | 8.0+ |
| Node.js | 18 | 20+ |
| NPM | 9 | 10+ |
| Composer | 2.5 | 2.8+ |
| Web Server | Apache / Nginx / IIS | Nginx or IIS |
| PHP Extensions | openssl, pdo, mbstring, tokenizer, xml, ctype, json, bcmath, curl, fileinfo, gd, zip | All of the above |

### PHP Extensions Required

```
curl
fileinfo
gd (for image processing)
json
mbstring
openssl
pdo
pdo_mysql
tokenizer
xml
ctype
bcmath
zip
```

---

## 4. Complete File Structure

```
nora-world/
│
├── app/
│   ├── Filament/
│   │   └── Resources/
│   │       ├── CategoryResource.php              # Category CRUD
│   │       ├── CategoryResource/Pages/
│   │       │   ├── CreateCategory.php
│   │       │   ├── EditCategory.php
│   │       │   └── ListCategories.php
│   │       ├── CollectionResource.php             # Collection CRUD
│   │       ├── CollectionResource/Pages/
│   │       ├── HomepageSectionResource.php        # Homepage section management
│   │       ├── HomepageSectionResource/Pages/
│   │       ├── NewsletterSubscriberResource.php   # Newsletter subscribers
│   │       ├── NewsletterSubscriberResource/Pages/
│   │       ├── OrderResource.php                  # Order management
│   │       ├── OrderResource/Pages/
│   │       ├── PageResource.php                   # Static pages
│   │       ├── PageResource/Pages/
│   │       ├── ProductResource.php                # Product CRUD (with image upload)
│   │       ├── ProductResource/Pages/
│   │       ├── SettingResource.php                # Store settings
│   │       ├── SettingResource/Pages/
│   │       ├── ShippingZoneResource.php           # Shipping zone management
│   │       ├── ShippingZoneResource/Pages/
│   │       ├── TestimonialResource.php            # Testimonials
│   │       └── TestimonialResource/Pages/
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                              # Breeze auth controllers
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── ConfirmablePasswordController.php
│   │   │   │   ├── EmailVerificationPromptController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   ├── CartController.php                 # Shopping cart
│   │   │   ├── CheckoutController.php             # Checkout + PayPal flow
│   │   │   ├── CollectionController.php           # Collection pages
│   │   │   ├── Controller.php
│   │   │   ├── HomeController.php                 # Homepage
│   │   │   ├── NewsletterController.php           # Newsletter subscribe/unsubscribe
│   │   │   ├── OrderController.php                # Order history
│   │   │   ├── PageController.php                 # Static pages
│   │   │   ├── ProductController.php              # Product listing/detail
│   │   │   ├── ProfileController.php              # User profile
│   │   │   ├── SearchController.php               # Live search API
│   │   │   └── WishlistController.php             # Wishlist
│   │   │
│   │   └── Requests/
│   │       └── Auth/
│   │           └── LoginRequest.php
│   │
│   ├── Models/                                    # 21 Eloquent models
│   │   ├── Address.php
│   │   ├── CartItem.php
│   │   ├── Category.php
│   │   ├── Collection.php
│   │   ├── HomepageSection.php
│   │   ├── NewsletterSubscriber.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Page.php
│   │   ├── Payment.php
│   │   ├── Product.php
│   │   ├── ProductImage.php
│   │   ├── RecentlyViewedProduct.php
│   │   ├── Review.php
│   │   ├── Setting.php
│   │   ├── ShippingMethod.php
│   │   ├── ShippingZone.php
│   │   ├── Tag.php
│   │   ├── Testimonial.php
│   │   ├── User.php
│   │   └── WishlistItem.php
│   │
│   ├── Providers/
│   │   └── Filament/
│   │       └── AdminPanelProvider.php
│   │
│   └── Services/
│       ├── PayPalService.php                      # PayPal API integration
│       └── ShippingService.php                    # Shipping zone/cost logic
│
├── database/
│   ├── factories/
│   │   ├── OrderFactory.php
│   │   └── ProductFactory.php
│   │
│   ├── migrations/                                # 33 migration files
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2024_01_01_000010_create_categories_table.php
│   │   ├── 2024_01_01_000011_create_products_table.php
│   │   ├── 2024_01_01_000012_create_product_images_table.php
│   │   ├── 2024_01_01_000013_create_product_category_table.php
│   │   ├── 2024_01_01_000014_create_addresses_table.php
│   │   ├── 2024_01_01_000015_create_orders_table.php
│   │   ├── 2024_01_01_000016_create_order_items_table.php
│   │   ├── 2024_01_01_000017_create_payments_table.php
│   │   ├── 2024_01_01_000018_create_shipping_zones_table.php
│   │   ├── 2024_01_01_000019_create_shipping_methods_table.php
│   │   ├── 2024_01_01_000020_create_cart_items_table.php
│   │   ├── 2024_01_01_000021_create_wishlist_items_table.php
│   │   ├── 2024_01_01_000022_create_collections_table.php
│   │   ├── 2024_01_01_000023_create_collection_product_table.php
│   │   ├── 2024_01_01_000024_create_homepage_sections_table.php
│   │   ├── 2024_01_01_000025_create_homepage_section_product_table.php
│   │   ├── 2024_01_01_000026_create_testimonials_table.php
│   │   ├── 2024_01_01_000027_create_newsletter_subscribers_table.php
│   │   ├── 2024_01_01_000028_create_pages_table.php
│   │   ├── 2024_01_01_000029_create_settings_table.php
│   │   ├── 2024_01_01_000030_create_tags_table.php
│   │   ├── 2024_01_01_000031_create_reviews_table.php
│   │   ├── 2024_01_01_000032_create_recently_viewed_products_table.php
│   │   └── 2024_01_01_000033_add_is_admin_to_users_table.php
│   │
│   └── seeders/
│       ├── CategorySeeder.php
│       ├── CollectionSeeder.php
│       ├── DatabaseSeeder.php
│       ├── HomepageSectionSeeder.php
│       ├── PageSeeder.php
│       ├── ProductSeeder.php
│       ├── SettingSeeder.php
│       ├── ShippingZoneSeeder.php
│       └── TestimonialSeeder.php
│
├── public/
│   ├── build/                                     # Compiled CSS/JS (Vite output)
│   │   ├── assets/
│   │   │   ├── app-VAUaz4-M.css                   # Compiled Tailwind CSS
│   │   │   ├── app-B4lpXC_K.js                    # Compiled JS (Alpine.js)
│   │   │   └── manifest.json
│   │   └── .vite/
│   │
│   ├── images/
│   │   ├── placeholder-product.svg                # Product placeholder image
│   │   └── placeholder-category.svg               # Category placeholder image
│   │
│   ├── storage -> ../storage/app/public           # Symlink to storage
│   │
│   ├── .htaccess
│   ├── favicon.ico
│   └── index.php
│
├── resources/
│   ├── css/
│   │   └── app.css                                # Custom CSS (animations, components)
│   │
│   ├── js/
│   │   └── app.js                                 # Alpine.js initialization
│   │
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php                      # Main layout
│       │   ├── footer.blade.php                   # Site footer
│       │   └── navigation.blade.php               # Header + search autocomplete
│       │
│       ├── components/
│       │   └── product-card.blade.php             # Reusable product card
│       │
│       ├── home/
│       │   └── index.blade.php                    # Homepage (14 sections)
│       │
│       ├── products/
│       │   ├── index.blade.php                    # Product listing
│       │   └── show.blade.php                     # Product detail
│       │
│       ├── cart/
│       │   └── index.blade.php                    # Shopping cart
│       │
│       ├── checkout/
│       │   └── index.blade.php                    # Checkout with PayPal
│       │
│       ├── orders/
│       │   ├── index.blade.php                    # Order history
│       │   └── show.blade.php                     # Order detail
│       │
│       ├── collections/
│       │   ├── index.blade.php                    # Collections listing
│       │   └── show.blade.php                     # Collection detail
│       │
│       ├── pages/
│       │   └── show.blade.php                     # Static pages
│       │
│       ├── wishlist/
│       │   └── index.blade.php                    # Wishlist
│       │
│       └── profile/
│           └── edit.blade.php                     # User profile
│
├── routes/
│   ├── web.php                                    # All web routes
│   └── auth.php                                   # Breeze auth routes
│
├── storage/
│   ├── app/
│   │   └── public/                                # Uploaded files (product images)
│   │       ├── products/                          # Product images go here
│   │       └── categories/                        # Category images go here
│   ├── framework/
│   │   ├── cache/
│   │   └── views/                                 # Compiled Blade views
│   └── logs/
│       └── laravel.log
│
├── tests/
│   ├── Feature/
│   │   ├── Auth/                                  # 6 Breeze auth tests
│   │   │   ├── AuthenticationTest.php
│   │   │   ├── EmailVerificationTest.php
│   │   │   ├── PasswordConfirmationTest.php
│   │   │   ├── PasswordResetTest.php
│   │   │   ├── PasswordUpdateTest.php
│   │   │   └── RegistrationTest.php
│   │   ├── CheckoutTest.php                       # 6 checkout tests
│   │   ├── ExampleTest.php                        # 1 homepage test
│   │   ├── PaymentTest.php                        # 10 PayPal tests
│   │   ├── ProductPricingTest.php                 # 5 pricing tests
│   │   ├── ProfileTest.php                        # 5 profile tests
│   │   ├── SearchTest.php                         # 6 search tests
│   │   └── ShippingZoneTest.php                   # 8 shipping tests
│   └── Unit/
│       └── ExampleTest.php                        # 1 unit test
│
├── .env.example                                   # Environment template
├── .env                                           # ⚠️ YOUR SECRETS — DO NOT MIGRATE
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
├── phpunit.xml
├── artisan
├── README.md
├── ARCHITECTURE.md
└── MIGRATION.md                                   # This file
```

**Total files (excluding vendor/node_modules):** ~254

---

## 5. Database Schema — All 30 Tables

### Core Catalog

| # | Table | Purpose | Key Columns |
|---|---|---|---|
| 1 | `users` | User accounts | id, name, email, password, is_admin |
| 2 | `categories` | Product categories (hierarchical) | id, name, slug, parent_id, image, is_active, sort_order |
| 3 | `products` | Product catalog | id, name, slug, price, compare_at_price, stock_quantity, origin_type, artisan_name, + 15 more fields |
| 4 | `product_images` | Product image gallery | id, product_id, path, alt_text, sort_order, is_primary |
| 5 | `product_category` | Product ↔ Category pivot | product_id, category_id |
| 6 | `collections` | Curated collections | id, name, slug, description, is_active, sort_order |
| 7 | `collection_product` | Collection ↔ Product pivot | collection_id, product_id, sort_order |
| 8 | `tags` | Product tags | id, name, slug |
| 9 | `product_tag` | Product ↔ Tag pivot | product_id, tag_id |

### Orders & Payments

| # | Table | Purpose | Key Columns |
|---|---|---|---|
| 10 | `orders` | Customer orders | id, order_number, user_id, status, subtotal, shipping_cost, total, currency, shipping_* fields |
| 11 | `order_items` | Order line items | id, order_id, product_id, quantity, unit_price, total_price |
| 12 | `payments` | PayPal payment records | id, order_id, paypal_order_id, paypal_capture_id, status, amount, currency, metadata |

### Shipping

| # | Table | Purpose | Key Columns |
|---|---|---|---|
| 13 | `shipping_zones` | Geographic zones | id, name, countries (JSON), is_active |
| 14 | `shipping_methods` | Methods per zone | id, shipping_zone_id, name, flat_rate, free_shipping_threshold, estimated_delivery_time |

### Shopping

| # | Table | Purpose | Key Columns |
|---|---|---|---|
| 15 | `cart_items` | Shopping cart | id, user_id, product_id, quantity, gift_wrapping |
| 16 | `wishlist_items` | Wishlists | id, user_id, product_id |
| 17 | `addresses` | Saved addresses | id, user_id, label, address fields |

### Content Management

| # | Table | Purpose | Key Columns |
|---|---|---|---|
| 18 | `homepage_sections` | Homepage sections | id, type, title, subtitle, description, is_active, sort_order, settings (JSON) |
| 19 | `homepage_section_product` | Section ↔ Product pivot | homepage_section_id, product_id |
| 20 | `testimonials` | Customer testimonials | id, customer_name, customer_location, content, rating, is_active, is_featured |
| 21 | `pages` | Static pages (About, Policies) | id, title, slug, content, meta_title, meta_description, is_active |
| 22 | `settings` | Key-value settings store | id, key, value, type, group |
| 23 | `newsletter_subscribers` | Newsletter list | id, email, is_active, subscribed_at |

### User Activity

| # | Table | Purpose | Key Columns |
|---|---|---|---|
| 24 | `reviews` | Product reviews | id, product_id, user_id, rating, title, content, is_approved |
| 25 | `recently_viewed_products` | Browsing history | id, user_id, product_id |
| 26 | `addresses` | Saved shipping addresses | id, user_id, label, first_name, last_name, address fields |

### System

| # | Table | Purpose |
|---|---|---|
| 27 | `sessions` | Laravel session storage |
| 28 | `cache` | Application cache |
| 29 | `jobs` | Queue jobs |
| 30 | `failed_jobs` | Failed queue jobs |

---

## 6. All Models, Controllers, and Resources

### Models (21)

| Model | Key Relationships |
|---|---|
| `User` | hasMany: CartItem, WishlistItem, Order, Address, Review, RecentlyViewedProduct |
| `Product` | belongsToMany: Category, Collection, Tag; hasMany: ProductImage, OrderItem, Review |
| `Category` | belongsToMany: Product; hasMany: subcategories (self-referencing) |
| `ProductImage` | belongsTo: Product |
| `Collection` | belongsToMany: Product |
| `Order` | belongsTo: User; hasMany: OrderItem, Payment; belongsTo: ShippingZone |
| `OrderItem` | belongsTo: Order, Product |
| `Payment` | belongsTo: Order |
| `ShippingZone` | hasMany: ShippingMethod |
| `ShippingMethod` | belongsTo: ShippingZone |
| `CartItem` | belongsTo: User, Product |
| `WishlistItem` | belongsTo: User, Product |
| `HomepageSection` | belongsToMany: Product |
| `Testimonial` | — |
| `Page` | — |
| `Setting` | — |
| `NewsletterSubscriber` | — |
| `Tag` | belongsToMany: Product |
| `Review` | belongsTo: Product, User |
| `RecentlyViewedProduct` | belongsTo: User, Product |
| `Address` | belongsTo: User |

### Controllers (14)

| Controller | Routes | Purpose |
|---|---|---|
| `HomeController` | `GET /` | Homepage with all sections |
| `ProductController` | `GET /products`, `GET /products/{slug}` | Product listing & detail |
| `CartController` | `GET /cart`, `POST /cart/add`, `PUT /cart/{id}`, `DELETE /cart/{id}`, `DELETE /cart` | Full cart CRUD |
| `CheckoutController` | `GET /checkout`, `POST /checkout/*`, `GET /checkout/paypal/*` | Checkout + PayPal flow |
| `OrderController` | `GET /orders`, `GET /orders/{id}` | Order history & detail |
| `WishlistController` | `GET /wishlist`, `POST /wishlist/add`, `DELETE /wishlist/{id}` | Wishlist management |
| `CollectionController` | `GET /collections`, `GET /collections/{slug}` | Collection pages |
| `PageController` | `GET /pages/{slug}` | Static pages |
| `NewsletterController` | `POST /newsletter/subscribe`, `POST /newsletter/unsubscribe` | Newsletter |
| `SearchController` | `GET /search?q=` | Live search API (JSON) |
| `ProfileController` | `GET /profile`, `PATCH /profile`, `DELETE /profile` | User profile |
| `Auth/*` | Various | Breeze authentication |

### Filament Admin Resources (10)

| Resource | Management |
|---|---|
| `ProductResource` | Product CRUD with image upload gallery, all handmade fields |
| `CategoryResource` | Category CRUD with image upload, parent/child hierarchy |
| `OrderResource` | Order listing, status updates, payment info |
| `CollectionResource` | Curated collection management |
| `HomepageSectionResource` | Homepage section activate/deactivate, reorder, edit |
| `ShippingZoneResource` | Shipping zone & method configuration |
| `TestimonialResource` | Customer testimonial management |
| `PageResource` | Static page content (About, Policies) |
| `SettingResource` | Store settings (key-value) |
| `NewsletterSubscriberResource` | Newsletter subscriber management |

---

## 7. Environment Variables

### Full `.env.example`

```env
APP_NAME=NORA WORLD
APP_ENV=local
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nora-world
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=nora-world

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@nora-world.com"
MAIL_FROM_NAME="${APP_NAME}"

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# PayPal Configuration
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_CURRENCY=USD
```

### ⚠️ NEVER MIGRATE `.env`

The `.env` file contains secrets. Always create a new one on the target server.

---

## 8. Backup Procedure (Current Server)

### Step 1: Stop the Application

```bash
# If using a queue worker
php artisan queue:stop

# Put app in maintenance mode
php artisan down
```

### Step 2: Export the Database

```bash
# MySQL
mysqldump -u root -p nora-world > nora-world_database_backup.sql

# Or with full options
mysqldump -u root -p --single-transaction --routines --triggers nora-world > nora-world_database_backup.sql
```

### Step 3: Create the Backup Archive

```bash
# From the PARENT directory of nora-world/
cd C:\inetpub\wwwroot

# Create zip archive (exclude vendor, node_modules, storage/framework, storage/logs)
# On Windows with PowerShell:
Compress-Archive -Path "nora-world" -DestinationPath "nora-world_backup_$(Get-Date -Format 'yyyy-MM-dd_HHmmss').zip" -CompressionLevel Optimal

# OR on Linux/Mac:
tar -czf nora-world_backup_$(date +%Y%m%d_%H%M%S).tar.gz \
    --exclude='nora-world/vendor' \
    --exclude='nora-world/node_modules' \
    --exclude='nora-world/storage/framework' \
    --exclude='nora-world/storage/logs' \
    --exclude='nora-world/public/build' \
    --exclude='nora-world/.env' \
    nora-world/
```

### Step 4: Backup Uploaded Files Separately

```bash
# Product images and category images
# These are in storage/app/public/
# On Windows:
xcopy /E /I "nora-world\storage\app\public" "nora-world_uploads_backup"

# On Linux/Mac:
cp -r nora-world/storage/app/public nora-world_uploads_backup
```

### Step 5: Export Composer.lock and Package-lock.json

These are already in the project files. They ensure identical dependency versions on the new server.

### Step 6: Copy the Compiled Assets

The `public/build/` directory contains compiled CSS and JS. It will be regenerated, but having a copy ensures fallback.

### Step 7: Reactivate the Application

```bash
php artisan up
```

### Complete Backup Checklist

```
✅ nora-world_database_backup.sql       (Database export)
✅ nora-world_uploads_backup/           (Product/category images)
✅ nora-world_backup_YYYYMMDD.zip       (Full project files)
✅ .env values noted separately         (PayPal keys, DB creds, APP_KEY)
✅ composer.lock                        (In the zip)
✅ package-lock.json                    (In the zip)
```

---

## 9. Migration Procedure (New Server)

### Step 1: Install Server Requirements

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-gd php8.2-zip php8.2-bcmath php8.2-fileinfo php8.2-tokenizer mysql-server nginx composer nodejs npm

# Or on Windows:
# Install PHP 8.2+ from windows.php.net
# Install MySQL from dev.mysql.com
# Install Composer from getcomposer.org
# Install Node.js from nodejs.org
# Install IIS + URL Rewrite module
```

### Step 2: Create the Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE nora-world CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'nora-world_user'@'localhost' IDENTIFIED BY 'YOUR_SECURE_PASSWORD';
GRANT ALL PRIVILEGES ON nora-world.* TO 'nora-world_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Deploy Project Files

```bash
# Copy the backup zip to the new server
# Unzip to web root
cd /var/www  # or C:\inetpub\wwwroot on Windows
unzip nora-world_backup_YYYYMMDD.zip
```

### Step 4: Install Dependencies

```bash
cd nora-world

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies
npm install

# Build frontend assets
npm run build
```

### Step 5: Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with new server values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nora-world
DB_USERNAME=nora-world_user
DB_PASSWORD=YOUR_SECURE_PASSWORD

# PayPal (copy from old server)
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_CURRENCY=USD
```

### Step 6: Import the Database

```bash
# Import the backup
mysql -u nora-world_user -p nora-world < nora-world_database_backup.sql

# OR run fresh migrations + seeders (if you prefer clean install)
php artisan migrate
php artisan db:seed
```

### Step 7: Restore Uploaded Files

```bash
# Copy uploaded product/category images back
cp -r nora-world_uploads_backup/* nora-world/storage/app/public/
# Or on Windows:
xcopy /E /I /Y "nora-world_uploads_backup\*" "nora-world\storage\app\public\"
```

### Step 8: Create Storage Symlink

```bash
php artisan storage:link
```

### Step 9: Set Permissions (Linux)

```bash
# Storage and cache writable
sudo chown -R www-data:www-data nora-world/storage
sudo chown -R www-data:www-data nora-world/bootstrap/cache
sudo chmod -R 775 nora-world/storage
sudo chmod -R 775 nora-world/bootstrap/cache
```

### Step 10: Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Step 11: Configure Web Server

#### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/nora-world/public;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realroot$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache (.htaccess already included)

```apache
# Ensure mod_rewrite is enabled
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### IIS (web.config)

The project includes an `.htaccess` for Apache. For IIS, create a `web.config`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Laravel Routes" stopProcessing="true">
                    <match url="^(.*)$" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php/{R:1}" appendQueryString="true" />
                </rule>
            </rules>
        </rewrite>
        <staticContent>
            <remove fileExtension=".svg" />
            <mimeMap fileExtension=".svg" mimeType="image/svg+xml" />
        </staticContent>
    </system.webServer>
</configuration>
```

### Step 12: Verify the Deployment

```bash
# Test the application
php artisan about
php artisan route:list

# Run tests (optional, on production use --env=testing)
php artisan test

# Clear all caches and rebuild
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

---

## 10. Post-Migration Checklist

```
✅ Website loads at https://yourdomain.com
✅ Homepage renders with all sections
✅ Product pages load with images
✅ Search autocomplete works
✅ Admin panel accessible at /admin
✅ Login with admin credentials works
✅ PayPal sandbox checkout works
✅ Storage symlink exists (public/storage → storage/app/public)
✅ Uploaded images display correctly
✅ Email subscription form works
✅ All navigation links work
✅ Mobile responsive design works
✅ SSL certificate installed (for production)
✅ PayPal credentials changed to LIVE (for production)
✅ APP_DEBUG=false in production
✅ APP_ENV=production in .env
✅ Cron job scheduled: * * * * * cd /path/to/nora-world && php artisan schedule:run
```

---

## 11. PayPal Configuration

### Sandbox (Development)

1. Go to https://developer.paypal.com
2. Create a **Business** sandbox account (merchant)
3. Create a **Personal** sandbox account (buyer)
4. Copy Business account → API Credentials → Client ID + Secret
5. Add to `.env`:

```env
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=你的sandbox_client_id
PAYPAL_CLIENT_SECRET=你的sandbox_secret
PAYPAL_CURRENCY=USD
```

### Production

1. Go to https://developer.paypal.com → Go Live
2. Complete the Go Live checklist
3. Copy production Client ID + Secret
4. Update `.env`:

```env
PAYPAL_MODE=live
PAYPAL_CLIENT_ID=你的production_client_id
PAYPAL_CLIENT_SECRET=你的production_secret
PAYPAL_CURRENCY=USD
```

### PayPal Flow Summary

```
Customer → Checkout Page → Click "Pay with PayPal"
    ↓
Server creates PayPal order (validates cart, calculates totals)
    ↓
PayPal popup opens → Customer approves payment
    ↓
PayPal redirects to /checkout/paypal/success
    ↓
Server captures payment → Validates amount → Updates order status
    ↓
Stock reduced → Cart cleared → Order confirmation shown
```

---

## 12. IIS Configuration

If deploying on Windows IIS:

1. **Enable URL Rewrite** — Install IIS URL Rewrite Module
2. **Enable FastCGI** — Configure PHP via FastCGI
3. **Application Pool** — Set to "No Managed Code"
4. **Physical Path** — Point to `nora-world/public/`
5. **web.config** — Create for URL rewriting (see Step 11)
6. **Storage Symlink** — IIS may need a junction instead:
   ```cmd
   mklink /J C:\inetpub\wwwroot\nora-world\public\storage C:\inetpub\wwwroot\nora-world\storage\app\public
   ```
7. **Permissions** — Give IIS_IUSRS read/write to `storage/` and `bootstrap/cache/`

---

## 13. Seeded Data Summary

When running `php artisan db:seed`, the following data is created:

### Admin User
| Field | Value |
|---|---|
| Name | Admin |
| Email | admin@nora-world.com |
| Password | password |
| Is Admin | true |

### Categories (14)
Home Décor · Ceramics and Pottery · Olive Wood Products · Palestinian Embroidery · Jordanian Handicrafts · Tableware and Kitchen · Textiles and Cushions · Wall Art · Mosaic and Glass Art · Brass and Metal Crafts · Baskets and Natural Fiber Products · Gifts and Souvenirs · Seasonal Collections · Sale

### Shipping Zones (5)
| Zone | Countries |
|---|---|
| United States | US |
| European Union | AT, BE, BG, HR, CY, CZ, DK, EE, FI, FR, DE, GR, HU, IE, IT, LV, LT, LU, MT, NL, PL, PT, RO, SK, SI, ES, SE |
| United Kingdom | GB |
| Other European | CH, NO, IS, AL, BA, ME, MK, RS, UA, MD, BY, TR |
| Rest of World | All other |

### Shipping Methods (per zone)
- Standard International Shipping — $9.99 flat rate, free over $100
- Express International Shipping — $19.99 flat rate, free over $200
- Free Shipping on Eligible Orders — $0, threshold configurable

### Homepage Sections (14)
Announcement Bar · Header · Hero · Featured Categories · Promotional Banners · Best Sellers · New Arrivals · Products on Sale · Artisan Story · Curated Collections · Recently Viewed · Testimonials · Newsletter · Footer

### Static Pages
About Us · Shipping Policy · Returns & Refunds · Privacy Policy · Terms & Conditions · Contact Us

### Testimonials (sample)
Pre-seeded with sample customer reviews

### Collections (sample)
Jordanian Heritage · Palestinian Heritage · Handcrafted Home Décor · Gifts with a Story

### Store Settings
Currency: USD · Store Name: NORA WORLD · PayPal Mode: Sandbox

---

## 14. Test Suite Summary

**60 tests · 129 assertions · All passing**

| Test File | Tests | Coverage |
|---|---|---|
| `ProductPricingTest` | 5 | USD pricing, sale detection, discount %, cart subtotal |
| `ShippingZoneTest` | 8 | Zone determination, cost calculation, free shipping, country serving |
| `CheckoutTest` | 6 | Auth required, cart display, empty redirect, PayPal validation, stock prevention |
| `PaymentTest` | 10 | Statuses (pending/captured/failed/cancelled/refunded), formatted amounts, idempotency, order not paid without capture, stock rules, customer order viewing |
| `SearchTest` | 6 | Short query, matching products, active/in-stock filter, data structure, artisan search, empty query |
| `ProfileTest` | 5 | Profile display, update, email verification, account deletion, password validation |
| `Auth/*` (Breeze) | 19 | Login, register, logout, email verification, password reset/confirm |
| `ExampleTest` | 1 | Homepage loads |

Run tests:
```bash
php artisan test
```

---

## 15. Troubleshooting

### Common Issues

| Issue | Solution |
|---|---|
| `Route [dashboard] not defined` | Run `php artisan route:clear` and check `routes/web.php` has dashboard route |
| Images not showing | Check `php artisan storage:link` exists, verify `public/storage` symlink |
| `500` on homepage | Run `php artisan view:clear`, check `storage/logs/laravel.log` |
| PayPal not working | Verify `.env` has valid sandbox credentials, check `PAYPAL_MODE=sandbox` |
| CSS not loading | Run `npm run build`, check `public/build/` exists |
| Migration errors | Check MySQL version supports JSON columns (5.7+) |
| `Class not found` errors | Run `composer dump-autoload` |
| Permission denied (Linux) | `sudo chown -R www-data:www-data storage bootstrap/cache` |

### Useful Commands

```bash
# Clear all caches
php artisan config:cache && php artisan config:clear
php artisan route:cache && php artisan route:clear
php artisan view:cache && php artisan view:clear

# Reset database (development only)
php artisan migrate:fresh --seed

# Check configuration
php artisan about
php artisan config:show
php artisan route:list

# View logs
tail -f storage/logs/laravel.log

# Recompile assets
npm run build
```

---

## Quick Reference: What to Copy to New Server

```
1. nora-world/                    ← Full project directory (minus vendor/node_modules)
2. nora-world_database_backup.sql ← Database export
3. nora-world_uploads_backup/     ← Uploaded images
4. .env values                    ← Manually recreate (NEVER copy .env directly)
```

### Minimum files for a fresh install (without database backup):

```
nora-world/
├── app/                          ← All PHP code
├── bootstrap/
├── config/
├── database/                     ← Migrations + Seeders
├── public/
│   ├── images/                   ← Placeholder SVGs
│   └── index.php
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/                    ← All Blade templates
├── routes/
├── storage/app/public/           ← Uploaded images
├── tests/
├── .env.example
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
└── phpunit.xml
```

Then run:
```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
# Edit .env with your database credentials
php artisan migrate
php artisan db:seed
php artisan storage:link
```
