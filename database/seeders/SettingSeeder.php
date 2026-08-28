<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'store_name', 'value' => 'NORA', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_tagline', 'value' => 'Authentic Handmade Heritage from Jordan and Palestine', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_email', 'value' => 'info@noormarket.com', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_phone', 'value' => '+962-XX-XXXXXXX', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_address', 'value' => 'Amman, Jordan', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'general', 'type' => 'text'],
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'general', 'type' => 'text'],

            // Social Media
            ['key' => 'facebook_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'twitter_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'pinterest_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'tiktok_url', 'value' => '', 'group' => 'social', 'type' => 'text'],

            // Announcement Bar
            ['key' => 'announcement_text', 'value' => 'International Shipping to USA & Europe | Secure Checkout with PayPal', 'group' => 'announcement', 'type' => 'text'],
            ['key' => 'announcement_active', 'value' => '1', 'group' => 'announcement', 'type' => 'boolean'],

            // PayPal
            ['key' => 'paypal_mode', 'value' => 'sandbox', 'group' => 'paypal', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
