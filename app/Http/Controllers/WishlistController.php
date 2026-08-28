<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlistItems = WishlistItem::where('user_id', $request->user()->id)
            ->with('product.images')
            ->get();

        return view('wishlist.index', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        WishlistItem::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
            ]
        );

        return back()->with('success', 'Product added to wishlist.');
    }

    public function remove(Request $request, WishlistItem $wishlistItem)
    {
        if ($wishlistItem->user_id !== $request->user()->id) {
            abort(403);
        }

        $wishlistItem->delete();

        return back()->with('success', 'Item removed from wishlist.');
    }
}
