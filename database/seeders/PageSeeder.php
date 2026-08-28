<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'content' => '<h2>International Shipping</h2>
<p>NORA ships to the United States, European Union, United Kingdom, and other international destinations.</p>

<h3>Shipping Zones and Rates</h3>
<p>We offer flat-rate shipping based on your destination. Free shipping is available on orders above the specified threshold for your shipping zone.</p>

<h3>Estimated Delivery Times</h3>
<ul>
<li><strong>United States:</strong> 7-14 business days (Standard), 3-5 business days (Express)</li>
<li><strong>European Union:</strong> 10-18 business days (Standard), 5-8 business days (Express)</li>
<li><strong>United Kingdom:</strong> 8-15 business days (Standard), 4-7 business days (Express)</li>
<li><strong>Other European Countries:</strong> 12-20 business days (Standard), 6-10 business days (Express)</li>
<li><strong>Rest of World:</strong> 15-25 business days (Standard), 7-12 business days (Express)</li>
</ul>

<h3>Customs and Import Duties</h3>
<p><strong>Import duties, customs charges, and local taxes, if applicable, are the responsibility of the customer.</strong> NORA is not responsible for any additional charges imposed by your country\'s customs office.</p>

<h3>Order Processing</h3>
<p>Orders are typically processed within 2-5 business days. Handmade and made-to-order items may require additional processing time, which will be noted on the product page.</p>',
            ],
            [
                'title' => 'Returns and Refunds',
                'slug' => 'returns-and-refunds',
                'content' => '<h2>Returns Policy</h2>
<p>We want you to be completely satisfied with your purchase. If you\'re not happy with your order, we\'re here to help.</p>

<h3>Eligibility for Returns</h3>
<ul>
<li>Items must be returned within 30 days of delivery</li>
<li>Items must be unused, undamaged, and in their original packaging</li>
<li>Custom and personalized items cannot be returned</li>
<li>Items marked as "Final Sale" cannot be returned</li>
</ul>

<h3>How to Initiate a Return</h3>
<ol>
<li>Contact our customer service team at info@nora.com</li>
<li>Provide your order number and reason for return</li>
<li>Receive return instructions and shipping label</li>
<li>Ship the item back to us</li>
<li>Receive your refund within 5-7 business days after we receive the item</li>
</ol>

<h3>Refund Process</h3>
<p>Refunds will be issued to the original PayPal account used for purchase. Shipping costs are non-refundable unless the return is due to our error.</p>

<h3>Damaged Items</h3>
<p>If your item arrives damaged, please contact us immediately with photos of the damage. We will arrange for a replacement or full refund.</p>',
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>Welcome to Nora</h2>
<p><em>From Our Home to Yours</em></p>
<p>Nora is more than a store. It is a carefully gathered collection of meaningful pieces that have lived in homes, witnessed ordinary days and special moments, and been treasured by the people who owned them.</p>

<h2>Our Story</h2>
<p>Some of our pieces were passed down through the family. Some have been part of our own home for many years, while others were personally chosen simply because they were beautiful, charming, unusual, or capable of bringing joy.</p>
<p>Here you may discover vintage and pre-loved ceramics, glassware, tableware, commemorative and collectible pieces, folk dolls, watches, jewellery, artwork, decorative objects, accessories, handbags, clothing, and other treasures that do not always belong to one category &mdash; but each has something special to offer.</p>

<h2>Our Philosophy</h2>
<p>We believe that the true value of an object is not measured only by its age, maker, material, or price. Sometimes its greatest value lies in the memories it carries, the craftsmanship preserved within it, the culture it represents, or the quiet beauty it can bring into another home.</p>

<h2>Our Promise</h2>
<p>At Nora, truth comes before marketing. Every piece is presented as honestly and respectfully as possible, using photographs of the actual item and clearly describing its condition and visible signs of age or previous use. When an item\'s exact age, origin, maker, or material cannot be confirmed, we prefer to say so rather than turn uncertainty into a claim.</p>
<p>We do not hide the marks left by time. A gentle scratch, a faded colour, or another small sign of use may simply be part of the piece\'s journey &mdash; evidence that it was owned, used, displayed, and once loved.</p>

<h2>Why We Offer These Treasures</h2>
<p>We are not parting with these belongings because they have lost their meaning. We are offering them because beautiful and meaningful things deserve to be seen, appreciated, and loved again.</p>

<h2>Our Greatest Hope</h2>
<p>Every piece finds the right person: someone who will not leave it forgotten, but will display it, use it, cherish it, and allow it to become part of a new story.</p>
<p>Thank you for visiting Nora and for giving a piece with a past the chance to have a future.</p>
<p><em>Every piece has lived a story. Now it is ready to begin another &mdash; with you.</em></p>',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2>
<p>At NORA, we take your privacy seriously. This policy describes how we collect, use, and protect your personal information.</p>

<h3>Information We Collect</h3>
<ul>
<li>Name and contact information</li>
<li>Shipping and billing addresses</li>
<li>Payment information (processed securely through PayPal)</li>
<li>Order history and preferences</li>
</ul>

<h3>How We Use Your Information</h3>
<ul>
<li>Process and fulfill your orders</li>
<li>Communicate about your orders and account</li>
<li>Send marketing communications (with your consent)</li>
<li>Improve our products and services</li>
</ul>

<h3>Data Security</h3>
<p>We use industry-standard encryption and security measures to protect your personal information. Payment information is processed securely through PayPal and is never stored on our servers.</p>',
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h2>Terms of Service</h2>
<p>By using NORA, you agree to these terms of service.</p>

<h3>Products and Pricing</h3>
<ul>
<li>All prices are displayed in USD</li>
<li>Product images are representative; slight variations may occur due to the handmade nature of products</li>
<li>We reserve the right to modify prices without notice</li>
</ul>

<h3>Orders</h3>
<ul>
<li>Orders are subject to availability</li>
<li>We reserve the right to cancel orders at our discretion</li>
<li>Payment must be received before orders are processed</li>
</ul>

<h3>Intellectual Property</h3>
<p>All content on this website, including text, images, and design, is the property of NORA and is protected by copyright laws.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::create([
                ...$page,
                'is_active' => true,
            ]);
        }
    }
}
