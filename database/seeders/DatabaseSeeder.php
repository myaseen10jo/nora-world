<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Wipe old sample data ────────────────────────────────
        // Delete in reverse dependency order (SQLite compatible)
        $tables = [
            'product_category', 'collection_product', 'homepage_section_product',
            'product_images', 'products', 'collections', 'homepage_sections',
            'testimonials', 'tags', 'categories', 'pages', 'settings',
            'shipping_methods', 'shipping_zones', 'reviews', 'newsletter_subscribers',
            'recently_viewed_products', 'wishlist_items', 'cart_items',
            'order_items', 'payments', 'orders', 'addresses', 'users',
        ];
        foreach ($tables as $table) {
            DB::table($table)->delete();
        }

        // ── Create admin user ───────────────────────────────────
        User::create([
            'name' => 'Admin',
            'email' => 'admin@nora.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // ── Create test customer ────────────────────────────────
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // ── Run seeders in order ────────────────────────────────
        $this->call([
            CategorySeeder::class,
            CollectionSeeder::class,
            ProductSeeder::class,
            ShippingZoneSeeder::class,
            SettingSeeder::class,
            HomepageSectionSeeder::class,
            PageSeeder::class,
            TestimonialSeeder::class,
            DemoOrderSeeder::class,
        ]);
    }
}
