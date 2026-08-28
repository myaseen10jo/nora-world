<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();
        $users = User::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusWeights = [10, 15, 20, 40, 15]; // percentages

        $shippingCities = [
            ['city' => 'Amman', 'country' => 'Jordan'],
            ['city' => 'Irbid', 'country' => 'Jordan'],
            ['city' => 'Ramallah', 'country' => 'Palestine'],
            ['city' => 'Jerusalem', 'country' => 'Palestine'],
            ['city' => 'Dubai', 'country' => 'UAE'],
            ['city' => 'Riyadh', 'country' => 'Saudi Arabia'],
            ['city' => 'London', 'country' => 'United Kingdom'],
            ['city' => 'New York', 'country' => 'United States'],
            ['city' => 'Toronto', 'country' => 'Canada'],
            ['city' => 'Berlin', 'country' => 'Germany'],
        ];

        $firstNames = ['Ahmad', 'Sara', 'Mohammed', 'Lina', 'Omar', 'Fatima', 'Yusuf', 'Nadia', 'Khalid', 'Mariam', 'Ali', 'Hana', 'Rami', 'Dina', 'Samir'];
        $lastNames = ['Hassan', 'Ali', 'Mahmoud', 'Khalil', 'Suleiman', 'Othman', 'Ibrahim', 'Nasser', 'Darwish', 'Shamoun'];

        // Generate 60 orders over the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $ordersPerDay = match(true) {
                $day <= 3  => random_int(3, 6),   // Recent days: more orders
                $day <= 7  => random_int(2, 4),
                $day <= 14 => random_int(1, 3),
                default    => random_int(0, 3),
            };

            for ($o = 0; $o < $ordersPerDay; $o++) {
                $user = $users->random();
                $status = $this->weightedRandom($statuses, $statusWeights);
                $shipCity = $shippingCities[array_rand($shippingCities)];
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];

                $orderDate = Carbon::now()->subDays($day)->hour(random_int(8, 22))->minute(random_int(0, 59));

                $order = Order::create([
                    'order_number' => 'NM-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'status' => $status,
                    'subtotal' => 0,
                    'shipping_cost' => round(mt_rand(800, 2500) / 100, 2),
                    'tax_amount' => 0,
                    'discount_amount' => random_int(0, 3) === 0 ? round(mt_rand(500, 2000) / 100, 2) : 0,
                    'total' => 0,
                    'currency' => 'USD',
                    'shipping_first_name' => $firstName,
                    'shipping_last_name' => $lastName,
                    'shipping_address_line_1' => random_int(1, 200) . ' ' . ['King Abdullah', 'Rainbow Street', 'Al-Madina', 'Al-Salt Road', 'Queen Rania'][array_rand(['King Abdullah', 'Rainbow Street', 'Al-Madina', 'Al-Salt Road', 'Queen Rania'])] . ' St',
                    'shipping_city' => $shipCity['city'],
                    'shipping_country' => $shipCity['country'],
                    'shipping_postal_code' => str_pad((string) random_int(10000, 99999), 5, '0', STR_PAD_LEFT),
                    'shipping_phone' => '+962' . random_int(700000000, 799999999),
                    'shipping_method' => ['standard', 'express', 'priority'][array_rand(['standard', 'express', 'priority'])],
                    'notes' => null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Add 1-4 items per order
                $numItems = random_int(1, 4);
                $usedProducts = [];
                $subtotal = 0;

                for ($i = 0; $i < $numItems; $i++) {
                    do {
                        $product = $products->random();
                    } while (in_array($product->id, $usedProducts));
                    $usedProducts[] = $product->id;

                    $qty = $product->stock_quantity > 1 ? random_int(1, min(3, $product->stock_quantity)) : 1;
                    $unitPrice = (float) $product->price;
                    // Randomly adjust price slightly for realism
                    if (random_int(0, 3) === 0) {
                        $unitPrice = round($unitPrice * (0.85 + (mt_rand(0, 30) / 100)), 2);
                    }
                    $lineTotal = round($unitPrice * $qty, 2);
                    $subtotal += $lineTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_slug' => $product->slug,
                        'product_image' => $product->primaryImage?->path,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'total_price' => $lineTotal,
                        'gift_wrapping' => random_int(0, 5) === 0,
                        'created_at' => $orderDate,
                    ]);
                }

                $tax = round($subtotal * 0.07, 2); // 7% tax
                $total = round($subtotal + $order->shipping_cost + $tax - $order->discount_amount, 2);

                $order->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $tax,
                    'total' => $total,
                ]);
            }
        }

        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $this->command?->info("Seeded {$totalOrders} orders with $" . number_format($totalRevenue, 2) . " total revenue");
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = random_int(1, $totalWeight);
        $cumulative = 0;

        foreach ($items as $index => $item) {
            $cumulative += $weights[$index];
            if ($random <= $cumulative) {
                return $item;
            }
        }

        return end($items);
    }
}
