<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('search', $request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['products' => []]);
        }

        $products = Product::active()
            ->inStock()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('artisan_name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['images' => function ($q) {
                $q->orderBy('sort_order')->limit(1);
            }])
            ->limit(8)
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->formatted_price,
                'image' => $this->resolveImagePath($product),
                'url' => route('products.show', $product->slug),
            ]);

        return response()->json(['products' => $products]);
    }

    private function resolveImagePath(Product $product): string
    {
        $image = $product->primaryImage ?? $product->images->first();

        if (!$image) {
            return asset('images/placeholder-product.svg');
        }

        $path = $image->path;

        // Check if it's a direct public path (like images/nora/products/product-01.jpeg)
        $trimmed = ltrim($path, '/');
        if (file_exists(public_path($trimmed))) {
            return asset($trimmed);
        }

        return asset('storage/' . $path);
    }
}
