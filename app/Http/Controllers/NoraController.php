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
        $mapping = [
            'ceramics' => [
                ['image' => 'product-01.jpeg', 'name' => 'Vintage Ceramic Vase', 'slug' => 'vintage-ceramic-vase', 'tag' => 'Pre-Loved Treasure', 'price' => '$45'],
                ['image' => 'product-02.jpeg', 'name' => 'Hand-Painted Bowl', 'slug' => 'hand-painted-decorative-bowl', 'tag' => 'Vintage', 'price' => '$38'],
                ['image' => 'product-03.jpeg', 'name' => 'Decorative Plate', 'slug' => 'ornate-decorative-plate', 'tag' => 'Collectible', 'price' => '$52'],
                ['image' => 'product-04.jpeg', 'name' => 'Heritage Glassware', 'slug' => 'heritage-glassware-set', 'tag' => 'Vintage', 'price' => '$35'],
            ],
            'decorative' => [
                ['image' => 'product-05.jpeg', 'name' => 'Folk Art Doll', 'slug' => 'handmade-folk-art-doll', 'tag' => 'Handmade Heritage', 'price' => '$42'],
                ['image' => 'product-06.jpeg', 'name' => 'Artistic Figurine', 'slug' => 'decorative-artistic-figurine', 'tag' => 'Art Piece', 'price' => '$48'],
                ['image' => 'product-07.jpeg', 'name' => 'Decorative Mirror', 'slug' => 'vintage-artistic-mirror', 'tag' => 'Vintage', 'price' => '$65'],
                ['image' => 'product-08.jpeg', 'name' => 'Cultural Wall Art', 'slug' => 'cultural-wall-art-panel', 'tag' => 'Pre-Loved', 'price' => '$55'],
            ],
            'jewellery' => [
                ['image' => 'product-09.jpeg', 'name' => 'Vintage Timepiece', 'slug' => 'vintage-timepiece', 'tag' => 'Vintage Watch', 'price' => '$85'],
                ['image' => 'product-10.jpeg', 'name' => 'Heritage Necklace', 'slug' => 'heritage-necklace', 'tag' => 'Collectible', 'price' => '$48'],
                ['image' => 'product-11.jpeg', 'name' => 'Artisan Ring', 'slug' => 'artisan-ring', 'tag' => 'One of a Kind', 'price' => '$62'],
            ],
            'collectibles' => [
                ['image' => 'product-12.jpeg', 'name' => 'Commemorative Plate', 'slug' => 'commemorative-collector-plate', 'tag' => 'Commemorative', 'price' => '$58'],
                ['image' => 'product-13.jpeg', 'name' => 'Antique Ornament', 'slug' => 'antique-decorative-ornament', 'tag' => 'Antique', 'price' => '$40'],
                ['image' => 'product-14.jpeg', 'name' => 'Heritage Collectible', 'slug' => 'heritage-collectible-piece', 'tag' => 'Collectible', 'price' => '$50'],
            ],
            'accessories' => [
                ['image' => 'product-15.jpeg', 'name' => 'Vintage Handbag', 'slug' => 'vintage-handbag', 'tag' => 'Pre-Loved', 'price' => '$55'],
                ['image' => 'product-16.jpeg', 'name' => 'Classic Accessory', 'slug' => 'classic-vintage-accessory', 'tag' => 'Vintage', 'price' => '$35'],
                ['image' => 'product-17.jpeg', 'name' => 'Timeless Piece', 'slug' => 'timeless-pre-loved-piece', 'tag' => 'Vintage', 'price' => '$45'],
            ],
        ];

        return $mapping[$category] ?? [];
    }
}
