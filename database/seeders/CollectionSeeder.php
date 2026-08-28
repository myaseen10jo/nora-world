<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            ['name' => 'Jordanian Heritage', 'slug' => 'jordanian-heritage', 'description' => 'Products that celebrate the rich heritage and craftsmanship of Jordan.', 'sort_order' => 1],
            ['name' => 'Palestinian Heritage', 'slug' => 'palestinian-heritage', 'description' => 'Authentic Palestinian handcrafted products preserving centuries of tradition.', 'sort_order' => 2],
            ['name' => 'Handcrafted Home Décor', 'slug' => 'handcrafted-home-decor', 'description' => 'Beautiful handmade items to transform your living space.', 'sort_order' => 3],
            ['name' => 'Gifts with a Story', 'slug' => 'gifts-with-a-story', 'description' => 'Meaningful gifts that carry the stories of Levantine artisans.', 'sort_order' => 4],
            ['name' => 'Tableware and Kitchen', 'slug' => 'tableware-and-kitchen-collection', 'description' => 'Handmade kitchen essentials and tableware.', 'sort_order' => 5],
            ['name' => 'Wall Art and Textiles', 'slug' => 'wall-art-and-textiles', 'description' => 'Stunning wall art and textile pieces for your home.', 'sort_order' => 6],
        ];

        foreach ($collections as $collection) {
            Collection::create([
                ...$collection,
                'is_active' => true,
            ]);
        }
    }
}
