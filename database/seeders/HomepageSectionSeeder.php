<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'type' => 'announcement_bar',
                'title' => null,
                'subtitle' => null,
                'content' => 'International shipping to USA & Europe | Secure checkout with PayPal | Handmade products from Jordan and Palestine',
                'settings' => ['background_color' => '#556B2F', 'text_color' => '#FFFFFF'],
                'sort_order' => 1,
            ],
            [
                'type' => 'hero',
                'title' => 'Discover Handmade Heritage',
                'subtitle' => 'Authentic home products crafted by artisans from Jordan and Palestine',
                'content' => 'Explore our curated collection of handmade ceramics, olive wood products, Palestinian embroidery, and traditional Jordanian handicrafts. Each piece tells a story of heritage and craftsmanship.',
                'link' => '/collections',
                'link_text' => 'Shop New Arrivals',
                'settings' => ['button_style' => 'primary', 'text_position' => 'center'],
                'sort_order' => 2,
            ],
            [
                'type' => 'featured_categories',
                'title' => 'Shop by Category',
                'subtitle' => 'Explore our curated collections',
                'content' => null,
                'settings' => ['columns' => 4, 'show_images' => true],
                'sort_order' => 3,
            ],
            [
                'type' => 'promotional_banners',
                'title' => 'Special Offers',
                'subtitle' => null,
                'content' => null,
                'settings' => ['layout' => 'grid', 'columns' => 2],
                'sort_order' => 4,
            ],
            [
                'type' => 'best_sellers',
                'title' => 'Best Sellers',
                'subtitle' => 'Our most loved handmade products',
                'content' => null,
                'settings' => ['limit' => 8, 'show_badge' => true],
                'sort_order' => 5,
            ],
            [
                'type' => 'new_arrivals',
                'title' => 'New Arrivals',
                'subtitle' => 'Fresh additions to our collection',
                'content' => null,
                'settings' => ['limit' => 8, 'days' => 30],
                'sort_order' => 6,
            ],
            [
                'type' => 'on_sale',
                'title' => 'Products on Sale',
                'subtitle' => 'Great deals on handcrafted treasures',
                'content' => null,
                'settings' => ['limit' => 8, 'show_original_price' => true],
                'sort_order' => 7,
            ],
            [
                'type' => 'artisan_story',
                'title' => 'The Artisans Behind Our Products',
                'subtitle' => 'Stories of heritage and craftsmanship',
                'content' => 'Every piece in our collection carries the legacy of skilled artisans from Jordan and Palestine. From the olive groves of the West Bank to the bustling souks of Amman, our products are crafted using techniques passed down through generations. Each item is a testament to the rich cultural heritage of the Levant.',
                'image' => null,
                'settings' => ['show_artisan_names' => true],
                'sort_order' => 8,
            ],
            [
                'type' => 'curated_collections',
                'title' => 'Curated Collections',
                'subtitle' => 'Thoughtfully assembled collections',
                'content' => null,
                'settings' => ['show_images' => true, 'columns' => 3],
                'sort_order' => 9,
            ],
            [
                'type' => 'recently_viewed',
                'title' => 'Recently Viewed',
                'subtitle' => 'Continue where you left off',
                'content' => null,
                'settings' => ['limit' => 6],
                'sort_order' => 10,
            ],
            [
                'type' => 'testimonials',
                'title' => 'What Our Customers Say',
                'subtitle' => 'Real stories from our community',
                'content' => null,
                'settings' => ['limit' => 6, 'layout' => 'carousel'],
                'sort_order' => 11,
            ],
            [
                'type' => 'newsletter',
                'title' => 'Stay Connected',
                'subtitle' => 'Receive new collection updates, exclusive offers, and stories from our artisans.',
                'content' => null,
                'settings' => ['placeholder_text' => 'Enter your email address', 'button_text' => 'Subscribe'],
                'sort_order' => 12,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::create([
                ...$section,
                'is_active' => true,
            ]);
        }
    }
}
