<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\MarketplaceController;
use App\Models\Category;
use App\Models\Product;

Route::get('/', function () {
    $categories = Category::limit(6)->get();
    $latestProducts = Product::with(['primaryImage', 'category', 'user'])
        ->where('status', 'active')
        ->latest()
        ->limit(4)
        ->get();
    return view('welcome', compact('categories', 'latestProducts'));
})->name('home');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/product/{slug}', [MarketplaceController::class, 'show'])->name('product.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Order History
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/become-seller', [App\Http\Controllers\ProfileController::class, 'becomeSeller'])->name('profile.becomeSeller');

    // Negotiations
    Route::get('/negotiations', [App\Http\Controllers\NegotiationController::class, 'index'])->name('negotiations.index');
    Route::post('/negotiations', [App\Http\Controllers\NegotiationController::class, 'store'])->name('negotiations.store');
    Route::put('/negotiations/{id}/accept', [App\Http\Controllers\NegotiationController::class, 'accept'])->name('negotiations.accept');
    Route::put('/negotiations/{id}/reject', [App\Http\Controllers\NegotiationController::class, 'reject'])->name('negotiations.reject');

    // Seller Dashboard
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SellerController::class, 'index'])->name('dashboard');
        
        Route::get('/products/create', [App\Http\Controllers\SellerController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\SellerController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [App\Http\Controllers\SellerController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [App\Http\Controllers\SellerController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [App\Http\Controllers\SellerController::class, 'destroy'])->name('products.destroy');
        Route::delete('/products/images/{id}', [App\Http\Controllers\SellerController::class, 'destroyImage'])->name('products.images.destroy');
        
        Route::get('/orders', [App\Http\Controllers\SellerController::class, 'orders'])->name('orders.index');
        Route::put('/orders/{id}/status', [App\Http\Controllers\SellerController::class, 'updateOrderStatus'])->name('orders.status');
    });

    // Admin Dashboard
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::put('/users/{id}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.role');
        
        Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{id}', [App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('categories.destroy');
    });
});

// Fallback route to serve images if symlink fails on Windows
Route::get('storage/{path}', function ($path) {
    $path = storage_path('app/public/' . $path);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('path', '.*');
