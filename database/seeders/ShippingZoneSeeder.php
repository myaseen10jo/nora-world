<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'United States',
                'description' => 'Shipping to all US states and territories',
                'countries' => ['US'],
                'methods' => [
                    ['name' => 'Standard International Shipping', 'flat_rate' => 9.99, 'free_shipping_threshold' => 100, 'estimated_delivery_time' => '7-14 business days'],
                    ['name' => 'Express International Shipping', 'flat_rate' => 19.99, 'estimated_delivery_time' => '3-5 business days'],
                ],
            ],
            [
                'name' => 'European Union',
                'description' => 'Shipping to EU member states',
                'countries' => ['AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE'],
                'methods' => [
                    ['name' => 'Standard International Shipping', 'flat_rate' => 12.99, 'free_shipping_threshold' => 150, 'estimated_delivery_time' => '10-18 business days'],
                    ['name' => 'Express International Shipping', 'flat_rate' => 24.99, 'estimated_delivery_time' => '5-8 business days'],
                ],
            ],
            [
                'name' => 'United Kingdom',
                'description' => 'Shipping to the United Kingdom',
                'countries' => ['GB'],
                'methods' => [
                    ['name' => 'Standard International Shipping', 'flat_rate' => 11.99, 'free_shipping_threshold' => 120, 'estimated_delivery_time' => '8-15 business days'],
                    ['name' => 'Express International Shipping', 'flat_rate' => 22.99, 'estimated_delivery_time' => '4-7 business days'],
                ],
            ],
            [
                'name' => 'Other European Countries',
                'description' => 'Shipping to non-EU European countries',
                'countries' => ['AL', 'AD', 'BA', 'IS', 'XK', 'LI', 'MD', 'MC', 'ME', 'MK', 'NO', 'SM', 'RS', 'CH', 'TR', 'UA'],
                'methods' => [
                    ['name' => 'Standard International Shipping', 'flat_rate' => 14.99, 'free_shipping_threshold' => 175, 'estimated_delivery_time' => '12-20 business days'],
                    ['name' => 'Express International Shipping', 'flat_rate' => 29.99, 'estimated_delivery_time' => '6-10 business days'],
                ],
            ],
            [
                'name' => 'Rest of World',
                'description' => 'International shipping to all other countries',
                'countries' => ['*'],
                'methods' => [
                    ['name' => 'Standard International Shipping', 'flat_rate' => 19.99, 'free_shipping_threshold' => 200, 'estimated_delivery_time' => '15-25 business days'],
                    ['name' => 'Express International Shipping', 'flat_rate' => 39.99, 'estimated_delivery_time' => '7-12 business days'],
                ],
            ],
        ];

        foreach ($zones as $index => $zoneData) {
            $methods = $zoneData['methods'];
            unset($zoneData['methods']);

            $zone = ShippingZone::create([
                ...$zoneData,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);

            foreach ($methods as $methodIndex => $methodData) {
                ShippingMethod::create([
                    ...$methodData,
                    'shipping_zone_id' => $zone->id,
                    'is_active' => true,
                    'sort_order' => $methodIndex + 1,
                ]);
            }
        }
    }
}
