<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ... method index, create, store, show, edit ...

    public function edit($id)
    {
        $product = Product::with('productImages')->findOrFail($id);
        
        // Pastikan user hanya bisa edit produk miliknya sendiri
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validasi Data
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'condition'     => 'required|in:BNOB,Like New,Normal,Rusak Ringan,Rusak Parah',
            'price'         => 'required|numeric|min:0',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string',
            'status'        => 'required|in:active,sold,archived',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        // 2. Generate Slug (karena di form tidak ada input slug)
        $validated['slug'] = Str::slug($validated['title']) . '-' . $product->id;

        // 3. Update Data Produk
        $product->update($validated);

        // 4. Handle Upload Foto Baru (Jika ada)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $product->productImages()->create([
                    'image_path' => $path,
                    'is_primary' => false, // Set false dulu, atau logic khusus
                ]);
            }
        }

        // 5. Redirect dengan pesan sukses
        return redirect()->route('seller.products.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    // Method untuk hapus gambar (dipanggil oleh JS di View)
    public function destroyImage($imageId)
    {
        $image = \App\Models\ProductImage::findOrFail($imageId);
        
        // Hapus file fisik
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Hapus record DB
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}