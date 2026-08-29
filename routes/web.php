Route::get('/debug-test', fn() => response()->json(['status' => 'ok', 'php' => phpversion(), 'debug' => config('app.debug'), 'env' => config('app.env')]));

<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// NORA Pages
Route::get('/our-story', [\App\Http\Controllers\NoraController::class, 'about'])->name('nora.about');
Route::get('/gallery', [\App\Http\Controllers\NoraController::class, 'gallery'])->name('nora.gallery');

// Search API
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Dashboard (Breeze redirect target)
Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Collections
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])->name('collections.show');

// Static Pages
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// Theme Switching
Route::post('/theme/switch', [\App\Http\Controllers\ThemeController::class, 'switch'])->name('theme.switch');
Route::post('/theme/set', [\App\Http\Controllers\ThemeController::class, 'switch'])->name('theme.set');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlistItem}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/shipping-methods', [CheckoutController::class, 'getShippingMethods'])->name('checkout.shipping-methods');
    Route::post('/checkout/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');
    Route::post('/checkout/create-paypal-order', [CheckoutController::class, 'createPayPalOrder'])->name('checkout.create-paypal-order');
    Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
    Route::get('/checkout/paypal/cancel', [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/auth.php';
