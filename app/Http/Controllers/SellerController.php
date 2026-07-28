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
    public function index(Request $request)
    {
        $totalProducts = Product::where('user_id', Auth::id())->count();
        $activeProducts = Product::where('user_id', Auth::id())->where('status', 'active')->count();

        $query = Product::where('user_id', Auth::id())->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', '%' . $search . '%');
        }

        $products = $query->get();
        
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
        $action = $request->input('action', 'publish'); // 'draft' or 'publish'

        if ($action === 'draft') {
            // Draft: relaxed validation — hanya nama produk wajib
            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'condition' => 'nullable|string',
                'location' => 'nullable|string',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $product = Product::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . time(),
                'description' => $request->description ?? '',
                'price' => $request->price ?? 0,
                'condition' => $request->condition ?? 'Baik',
                'location' => $request->location ?? '',
                'status' => 'draft',
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            return redirect()->route('seller.dashboard')->with('success', 'Produk disimpan sebagai draft. Anda bisa melanjutkan kapan saja.');
        }

        if ($request->has('price') && is_string($request->price)) {
            $request->merge(['price' => preg_replace('/[^0-9]/', '', $request->price)]);
        }

        // Publish: full validation
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:9999999999999.99',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|string',
            'location' => 'required|string',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        $product = Product::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 1,
            'condition' => $request->condition,
            'location' => $request->location,
            'status' => 'pending',
            'payment_proof' => $paymentProofPath,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('seller.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah! Produk Anda sedang menunggu verifikasi oleh admin.');
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
        $product = Product::findOrFail($id);

        // 2. Security: Pastikan yang mengedit adalah pemilik produk
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah produk ini.');
        }

        if ($request->has('price') && is_string($request->price)) {
            $request->merge(['price' => preg_replace('/[^0-9]/', '', $request->price)]);
        }

        // 3. Validasi input dari form
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'condition'     => 'required|string',
            'price'         => 'required|numeric|min:0|max:9999999999999.99',
            'stock'         => 'nullable|integer|min:0',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string',
            'status'        => 'required|in:active,sold,archived,draft,pending,rejected',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // 4. Generate slug baru
        $validated['slug'] = Str::slug($validated['title']) . '-' . $product->id;

        // Handle Upload Bukti Pembayaran Baru (jika ada)
        if ($request->hasFile('payment_proof')) {
            if ($product->payment_proof && !str_starts_with($product->payment_proof, 'http')) {
                Storage::disk('public')->delete($product->payment_proof);
            }
            $validated['payment_proof'] = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // 5. Update data utama produk
        $product->update($validated);

        // 6. Handle Upload Foto Baru (Jika ada file gambar yang dikirim)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $product->productImages()->count() === 0, 
                ]);
            }
        }

        $message = ($product->status === 'pending')
            ? 'Bukti pembayaran berhasil dikirim! Produk Anda sedang menunggu verifikasi oleh admin.'
            : 'Produk dan foto berhasil diperbarui!';

        // 7. Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->route('seller.dashboard')
                         ->with('success', $message);
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
