<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = CartItem::where('user_id', $request->user()->id)
            ->with('product.images')
            ->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Product is out of stock or insufficient quantity.');
        }

        CartItem::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->user_id !== $request->user()->id) {
            abort(403);
        }

        $product = $cartItem->product;

        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock for requested quantity.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return back()->with('success', 'Cart cleared.');
    }
}
