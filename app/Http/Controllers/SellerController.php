<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    // Dashboard & Stats
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->latest()->get();
        
        $totalProducts = $products->count();
        $activeProducts = $products->where('status', 'active')->count();
        
        $orderItems = OrderItem::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->with('order')->get();

        $totalSales = $orderItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $totalOrders = $orderItems->pluck('order_id')->unique()->count();

        return view('seller.dashboard', compact('products', 'totalProducts', 'activeProducts', 'totalSales', 'totalOrders'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string',
            'location' => 'required|string',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product = Product::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'location' => $request->location,
            'status' => 'active',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0, // First image becomes primary
                ]);
            }
        }

        return redirect()->route('seller.dashboard')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|in:active,sold,archived',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            // Check if product already has primary image
            $hasPrimary = $product->primaryImage ? true : false;
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary && $index === 0, // Set as primary only if it doesn't have one and it's the first in loop
                ]);
            }
        }

        return redirect()->route('seller.dashboard')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        
        foreach ($product->productImages as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        $product->delete();
        
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        Storage::disk('public')->delete($image->image_path);
        
        $productId = $image->product_id;
        $isPrimary = $image->is_primary;
        
        $image->delete();

        // If primary image was deleted, make another one primary if exists
        if ($isPrimary) {
            $anotherImage = ProductImage::where('product_id', $productId)->first();
            if ($anotherImage) {
                $anotherImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Foto produk berhasil dihapus.');
    }

    public function orders()
    {
        $orderItems = OrderItem::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->with(['order.user', 'product'])->latest()->get();

        return view('seller.orders.index', compact('orderItems'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $hasItem = OrderItem::where('order_id', $order->id)->whereHas('product', function($q){
            $q->where('user_id', Auth::id());
        })->exists();

        if (!$hasItem) abort(403);

        $request->validate(['status' => 'required|in:pending,paid,shipped,completed,cancelled']);
        
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
