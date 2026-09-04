<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['images', 'categories']);

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by origin
        if ($request->has('origin')) {
            $query->where('origin_type', $request->origin);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('artisan_name', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');

        $query->orderBy($sortBy, $sortDir);

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()->root()->withCount('products')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $request->category,
            'currentOrigin' => $request->origin,
            'search' => $request->search,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['images', 'categories', 'collections', 'media' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }, 'reviews' => function ($q) {
                $q->approved()->with('user')->latest()->limit(5);
            }])
            ->firstOrFail();

        // Track recently viewed (if user is logged in)
        if (auth()->check()) {
            \App\Models\RecentlyViewedProduct::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                ],
                ['updated_at' => now()]
            );
        }

        // Get related products
        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->with(['images', 'categories'])
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
