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
                'description' => 'Antique and vintage glassware — timeless pieces that carry the elegance of bygone eras.',
            ],
            [
                'name' => 'مقتنيات نسائية',
                'slug' => 'maqtniat-nisaiyah',
                'description' => 'Women\'s collectibles — handbags, jewellery, accessories, and personal treasures.',
            ],
            [
                'name' => 'تحف ورسومات',
                'slug' => 'tuhaf-w-rusumat',
                'description' => 'Statues, artwork, and decorative objects — handcrafted pieces that bring character and heritage.',
            ],
            [
                'name' => 'متفرقات',
                'slug' => 'mutafarriqat',
                'description' => 'Miscellaneous treasures — unique finds that don\'t fit neatly into categories.',
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
