<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::active()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('collections.index', [
            'collections' => $collections,
        ]);
    }

    public function show(string $slug)
    {
        $collection = Collection::where('slug', $slug)
            ->active()
            ->withCount('products')
            ->firstOrFail();

        $products = $collection->products()
            ->active()
            ->with(['images', 'categories'])
            ->paginate(12);

        return view('collections.show', [
            'collection' => $collection,
            'products' => $products,
        ]);
    }
}
