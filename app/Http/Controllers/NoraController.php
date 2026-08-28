<?php

namespace App\Http\Controllers;

class NoraController extends Controller
{
    public function about()
    {
        return view('nora.about');
    }

    public function gallery()
    {
        $categories = $this->getProductCategories();
        return view('nora.gallery', ['categories' => $categories]);
    }

    private function getProductCategories(): array
    {
        return [
            [
                'name' => 'Ceramics & Glassware',
                'icon' => '🏺',
                'description' => 'Vintage and pre-loved ceramics, glassware, and tableware — each piece carrying the warmth of homes it once graced.',
                'products' => $this->getProductsByCategory('ceramics'),
            ],
            [
                'name' => 'Decorative Objects & Art',
                'icon' => '🎨',
                'description' => 'Artwork, decorative objects, and folk dolls that bring character and quiet beauty into any space.',
                'products' => $this->getProductsByCategory('decorative'),
            ],
            [
                'name' => 'Watches & Jewellery',
                'icon' => '⌚',
                'description' => 'Timepieces and jewellery that have witnessed special moments and are ready to become part of new ones.',
                'products' => $this->getProductsByCategory('jewellery'),
            ],
            [
                'name' => 'Collectibles & Commemorative',
                'icon' => '💎',
                'description' => 'Commemorative and collectible pieces — treasures that tell stories of culture, craft, and history.',
                'products' => $this->getProductsByCategory('collectibles'),
            ],
            [
                'name' => 'Accessories & Handbags',
                'icon' => '👜',
                'description' => 'Handbags, accessories, and clothing — pre-loved treasures ready to be cherished again.',
                'products' => $this->getProductsByCategory('accessories'),
            ],
        ];
    }

    private function getProductsByCategory(string $category): array
    {
        // Product images mapped to categories
        $mapping = [
            'ceramics' => [
                ['image' => 'product-01.jpeg', 'name' => 'Vintage Ceramic Vase', 'tag' => 'Pre-Loved Treasure'],
                ['image' => 'product-02.jpeg', 'name' => 'Hand-Painted Bowl', 'tag' => 'Vintage'],
                ['image' => 'product-03.jpeg', 'name' => 'Decorative Plate', 'tag' => 'Collectible'],
                ['image' => 'product-04.jpeg', 'name' => 'Heritage Glassware', 'tag' => 'Vintage'],
            ],
            'decorative' => [
                ['image' => 'product-05.jpeg', 'name' => 'Folk Art Doll', 'tag' => 'Handmade Heritage'],
                ['image' => 'product-06.jpeg', 'name' => 'Artistic Figurine', 'tag' => 'Art Piece'],
                ['image' => 'product-07.jpeg', 'name' => 'Decorative Mirror', 'tag' => 'Vintage'],
                ['image' => 'product-08.jpeg', 'name' => 'Cultural Wall Art', 'tag' => 'Pre-Loved'],
            ],
            'jewellery' => [
                ['image' => 'product-09.jpeg', 'name' => 'Vintage Timepiece', 'tag' => 'Vintage Watch'],
                ['image' => 'product-10.jpeg', 'name' => 'Heritage Necklace', 'tag' => 'Collectible'],
                ['image' => 'product-11.jpeg', 'name' => 'Artisan Ring', 'tag' => 'One of a Kind'],
            ],
            'collectibles' => [
                ['image' => 'product-12.jpeg', 'name' => 'Commemorative Piece', 'tag' => 'Commemorative'],
                ['image' => 'product-13.jpeg', 'name' => 'Antique Ornament', 'tag' => 'Antique'],
                ['image' => 'product-14.jpeg', 'name' => 'Heritage Collectible', 'tag' => 'Collectible'],
            ],
            'accessories' => [
                ['image' => 'product-15.jpeg', 'name' => 'Vintage Handbag', 'tag' => 'Pre-Loved'],
                ['image' => 'product-16.jpeg', 'name' => 'Classic Accessory', 'tag' => 'Vintage'],
                ['image' => 'product-17.jpeg', 'name' => 'Timeless Piece', 'tag' => 'Vintage'],
            ],
        ];

        return $mapping[$category] ?? [];
    }
}
