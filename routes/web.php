<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AdminController;
use App\Models\Category;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $categories = Category::limit(6)->get();
    $latestProducts = Product::with(['primaryImage', 'category', 'user'])
        ->where('status', 'active')
        ->latest()
        ->limit(6)
        ->get();
    return view('welcome', compact('categories', 'latestProducts'));
})->name('home');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/product/{slug}', [MarketplaceController::class, 'show'])->name('product.show');
Route::get('/seller/{id}', [MarketplaceController::class, 'sellerProfile'])->name('seller.profile');
Route::view('/cara-jualan', 'cara-jualan')->name('cara-jualan');
Route::view('/faq', 'faq')->name('faq');
Route::view('/hubungi', 'hubungi')->name('hubungi');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // ✅ CART — hanya SATU set, urutan penting: clear SEBELUM destroy
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');   // harus di atas {id}
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Order History
    // Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    // Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/become-seller', [ProfileController::class, 'becomeSeller'])->name('profile.becomeSeller');

    // Negotiations
    Route::get('/negotiations', [NegotiationController::class, 'index'])->name('negotiations.index');
    Route::post('/negotiations', [NegotiationController::class, 'store'])->name('negotiations.store');
    Route::put('/negotiations/{id}/accept', [NegotiationController::class, 'accept'])->name('negotiations.accept');
    Route::put('/negotiations/{id}/reject', [NegotiationController::class, 'reject'])->name('negotiations.reject');

    /*
    |----------------------------------------------------------------------
    | Seller Dashboard Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [SellerController::class, 'index'])->name('dashboard');

        Route::get('/products/create', [SellerController::class, 'create'])->name('products.create');
        Route::post('/products', [SellerController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [SellerController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [SellerController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [SellerController::class, 'destroy'])->name('products.destroy');
        Route::delete('/products/images/{id}', [SellerController::class, 'destroyImage'])->name('products.images.destroy');

        Route::get('/orders', [SellerController::class, 'orders'])->name('orders.index');
        Route::put('/orders/{id}/status', [SellerController::class, 'updateOrderStatus'])->name('orders.status');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Dashboard Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::put('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');

        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/products', [AdminController::class, 'products'])->name('products.index');
        Route::put('/products/{id}/status', [AdminController::class, 'updateProductStatus'])->name('products.status');
        Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route untuk Storage (Windows/Local)
|--------------------------------------------------------------------------
*/
Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');