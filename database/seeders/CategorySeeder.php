<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ceramics & Glassware', 'slug' => 'ceramics-glassware', 'description' => 'Vintage and pre-loved ceramics, glassware, and tableware — each piece carrying the warmth of homes it once graced.'],
            ['name' => 'Decorative Objects & Art', 'slug' => 'decorative-objects-art', 'description' => 'Artwork, decorative objects, and folk dolls that bring character and quiet beauty into any space.'],
            ['name' => 'Watches & Jewellery', 'slug' => 'watches-jewellery', 'description' => 'Timepieces and jewellery that have witnessed special moments and are ready to become part of new ones.'],
            ['name' => 'Collectibles & Commemorative', 'slug' => 'collectibles-commemorative', 'description' => 'Commemorative and collectible pieces — treasures that tell stories of culture, craft, and history.'],
            ['name' => 'Accessories & Handbags', 'slug' => 'accessories-handbags', 'description' => 'Handbags, accessories, and clothing — pre-loved treasures ready to be cherished again.'],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                ...$category,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
