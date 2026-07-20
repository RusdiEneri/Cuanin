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
        $product = Product::with('productImages')->findOrFail($id);
        
        // Security: hanya owner yang bisa edit
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
{
    // 1. Ambil data produk berdasarkan ID
    $product = \App\Models\Product::findOrFail($id);

    // 2. Security: Pastikan yang mengedit adalah pemilik produk
    if ($product->user_id !== auth()->id()) {
        abort(403, 'Anda tidak memiliki akses untuk mengubah produk ini.');
    }

    // 3. Validasi input dari form
    $validated = $request->validate([
        'title'         => 'required|string|max:255',
        'category_id'   => 'required|exists:categories,id',
        'condition'     => 'required|in:Barang Baru,Like New,Sangat Baik,Baik,Cukup,Rusak Ringan',
        'price'         => 'required|numeric|min:0|max:9999999999999.99',
        'location'      => 'required|string|max:255',
        'description'   => 'required|string',
        'status'        => 'required|in:active,sold,archived',
        'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Maksimal 5MB per foto
    ]);

    // 4. Generate slug baru
    $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . $product->id;

    // 5. Update data utama produk
    $product->update($validated);

    // 6. Handle Upload Foto Baru (Jika ada file gambar yang dikirim)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Simpan file ke storage/app/public/products/
            $path = $image->store('products', 'public');
            
            // Simpan record ke database
            \App\Models\ProductImage::create([
                'product_id' => $product->id, // PENTING: Gunakan $product->id, BUKAN $id
                'image_path' => $path,
                // Otomatis jadikan foto utama (primary) jika produk ini belum punya foto sama sekali
                'is_primary' => $product->productImages()->count() === 0, 
            ]);
        }
    }

    // 7. Redirect kembali ke dashboard dengan pesan sukses
    return redirect()->route('seller.dashboard')
                     ->with('success', 'Produk dan foto berhasil diperbarui!');
}

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        
        foreach ($product->productImages as $image) {
            if (!str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        
        $product->delete();
        
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        
        // Security: pastikan product milik user
        $product = $image->product;
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus file fisik (jika path lokal, bukan URL external)
        if (!str_starts_with($image->image_path, 'http')) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
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
