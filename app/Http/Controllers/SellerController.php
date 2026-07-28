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
        if ($request->has('price') && is_string($request->price)) {
            $request->merge(['price' => preg_replace('/[^0-9]/', '', $request->price)]);
        }

        $action = $request->input('action', 'publish'); // 'draft' or 'publish'

        if ($action === 'draft') {
            // Draft: relaxed validation — hanya nama produk wajib
            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
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
                'stock' => $request->stock ?? 1,
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

        // Publish: full validation
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|string',
            'location' => 'required|string',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'payment_proof.required' => 'Foto bukti pembayaran wajib diunggah sebelum publikasi.',
            'images.required' => 'Minimal 1 foto produk wajib diunggah.',
        ]);

        $productData = [
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'condition' => $request->condition,
            'location' => $request->location,
            'status' => 'pending',
        ];

        if ($request->hasFile('payment_proof')) {
            $productData['payment_proof'] = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        $product = Product::create($productData);

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

        return redirect()->route('seller.dashboard')->with('success', 'Pembayaran dikonfirmasi! Produk Anda berhasil dikirim dan sedang menunggu verifikasi dari Admin.');
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
    if ($request->has('price') && is_string($request->price)) {
        $request->merge(['price' => preg_replace('/[^0-9]/', '', $request->price)]);
    }

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
        'condition'     => 'required|in:BNOB,Like New,Normal,Rusak Ringan,Rusak Parah',
        'price'         => 'required|numeric|min:0|max:9999999999999.99',
        'stock'         => 'required|integer|min:0',
        'location'      => 'required|string|max:255',
        'description'   => 'required|string',
        'status'        => 'required|in:pending,active,rejected,sold,archived,draft',
        'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Maksimal 5MB per foto
        'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    // Validasi tambahan: Jika produk dikirim untuk verifikasi (pending), wajib ada bukti pembayaran
    if ($validated['status'] === 'pending' && !$product->payment_proof && !$request->hasFile('payment_proof')) {
        return back()->withErrors(['payment_proof' => 'Foto bukti pembayaran wajib diunggah untuk mengirimkan produk ke admin.'])->withInput();
    }

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

    // 7. Handle Upload Bukti Pembayaran
    if ($request->hasFile('payment_proof')) {
        // Hapus file bukti lama jika ada
        if ($product->payment_proof && !str_starts_with($product->payment_proof, 'http')) {
            Storage::disk('public')->delete($product->payment_proof);
        }
        $product->update([
            'payment_proof' => $request->file('payment_proof')->store('payment_proofs', 'public'),
        ]);
    }

    // 8. Redirect kembali ke dashboard dengan pesan sukses
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
