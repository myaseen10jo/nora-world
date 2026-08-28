<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'customer_name' => 'Sarah M.',
                'customer_location' => 'New York, USA',
                'content' => 'Absolutely beautiful olive wood cutting board! The craftsmanship is outstanding, and you can feel the quality. It arrived well-packaged and even more stunning in person.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Thomas K.',
                'customer_location' => 'Berlin, Germany',
                'content' => 'I ordered a set of Palestinian embroidery cushions for my living room. The colors are vibrant and the stitching is incredibly detailed. A true piece of art!',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Emma L.',
                'customer_location' => 'London, UK',
                'content' => 'The mosaic table I purchased is a conversation starter in my home. Excellent quality and fast international shipping. Will definitely order again.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Michael R.',
                'customer_location' => 'Toronto, Canada',
                'content' => 'I bought several items as gifts and they were all beautifully crafted. The gift wrapping option was a nice touch. Highly recommend NORA!',
                'rating' => 4,
                'is_featured' => false,
            ],
            [
                'customer_name' => 'Leila A.',
                'customer_location' => 'Paris, France',
                'content' => 'As someone of Palestinian heritage, finding authentic embroidery online is rare. NORA delivered exactly what I was looking for. The quality exceeds my expectations.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'David W.',
                'customer_location' => 'Sydney, Australia',
                'content' => 'The brass candleholders are stunning. You can tell they are handmade with care. Shipping took a bit longer due to distance, but communication was excellent throughout.',
                'rating' => 5,
                'is_featured' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create([
                ...$testimonial,
                'is_active' => true,
            ]);
        }
    }
}
