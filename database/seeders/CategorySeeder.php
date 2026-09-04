<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'زجاجيات انتيك و فينتيج',
                'slug' => 'zajagiat-antique-vintage',
                'description' => 'Antique and vintage glassware — timeless pieces that carry the elegance of bygone eras. Each glass, vase, and bottle tells a story of craftsmanship and refined taste.',
                'name_en' => 'Antique & Vintage Glassware',
            ],
            [
                'name' => 'مقتنيات نسائية',
                'slug' => 'maqtniat-nisaiyah',
                'description' => 'Women\'s collectibles — handbags, jewellery, accessories, and personal treasures that have been treasured and are ready to be cherished again.',
                'name_en' => 'Women\'s Collectibles',
            ],
            [
                'name' => 'تحف ورسومات',
                'slug' => 'tuhaf-w-rusumat',
                'description' => 'Statues, artwork, and decorative objects — handcrafted pieces that bring character, heritage, and quiet beauty into any space.',
                'name_en' => 'Statues & Artwork',
            ],
            [
                'name' => 'متفرقات',
                'slug' => 'mutafarriqat',
                'description' => 'Miscellaneous treasures — unique finds that don\'t fit neatly into categories but are too special to ignore.',
                'name_en' => 'Miscellaneous',
            ],
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
