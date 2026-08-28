<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all active homepage sections ordered
        $sections = HomepageSection::active()
            ->ordered()
            ->with('products')
            ->get();

        // Get featured categories
        $featuredCategories = \App\Models\Category::active()
            ->root()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        // Get best sellers (products with most orders)
        $bestSellers = Product::active()
            ->featured()
            ->inStock()
            ->with(['images', 'categories'])
            ->limit(8)
            ->get();

        // Get new arrivals
        $newArrivals = Product::active()
            ->inStock()
            ->newArrivals(30)
            ->with(['images', 'categories'])
            ->limit(8)
            ->get();

        // Get products on sale
        $onSale = Product::active()
            ->inStock()
            ->whereNotNull('compare_at_price')
            ->whereColumn('compare_at_price', '>', 'price')
            ->with(['images', 'categories'])
            ->limit(8)
            ->get();

        // Get collections
        $collections = Collection::active()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        // Get testimonials
        $testimonials = Testimonial::active()
            ->featured()
            ->limit(6)
            ->get();

        return view('home.index', [
            'sections' => $sections,
            'featuredCategories' => $featuredCategories,
            'bestSellers' => $bestSellers,
            'newArrivals' => $newArrivals,
            'onSale' => $onSale,
            'collections' => $collections,
            'testimonials' => $testimonials,
        ]);
    }
}
