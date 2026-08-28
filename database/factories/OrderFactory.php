<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 500);
        $shippingCost = fake()->randomFloat(2, 5, 25);

        return [
            'order_number' => 'NM-' . strtoupper(fake()->lexify('??????????')),
            'user_id' => null,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total' => $subtotal + $shippingCost,
            'currency' => 'USD',
            'shipping_first_name' => fake()->firstName(),
            'shipping_last_name' => fake()->lastName(),
            'shipping_company' => null,
            'shipping_address_line_1' => fake()->streetAddress(),
            'shipping_address_line_2' => null,
            'shipping_city' => fake()->city(),
            'shipping_state' => fake()->stateAbbr(),
            'shipping_postal_code' => fake()->postcode(),
            'shipping_country' => 'US',
            'shipping_phone' => fake()->phoneNumber(),
            'shipping_method' => 'Standard International Shipping',
            'estimated_delivery' => '7-14 business days',
            'shipping_zone_id' => null,
            'notes' => null,
        ];
    }
}
