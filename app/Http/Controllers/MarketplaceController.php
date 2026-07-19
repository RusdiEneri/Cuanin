<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        
        $query = Product::with(['primaryImage', 'category', 'user'])->where('status', 'active');
        
        // Filter Pencarian
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter Kondisi
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        // Filter Lokasi
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        // Filter Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Urutkan
        if ($request->filled('sort')) {
            if ($request->sort == 'terbaru') {
                $query->latest();
            } elseif ($request->sort == 'termurah') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'termahal') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->latest();
        }
//latest()
        $products = $query->latest()->paginate(12)->withQueryString();
    //  return view('marketplace.index', compact('products'));
        return view('marketplace.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['primaryImage','productImages', 'category', 'user'])->where('slug', $slug)->firstOrFail();

        if( $product->status !== 'active' && $product->user_id !== auth()->id()) {
            // abort(404, 'Produk tidak ditemukan atau tidak aktif.');
            return redirect()->route('marketplace')->with('error', 'Produk yang Anda cari sudah tidak tersedia atau tidak aktif. Silakan cari produk lain yang menarik!');
        }
        
        // Tambah views
        $product->increment('views');
        
        // Produk Serupa
        $relatedProducts = Product::with('primaryImage')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('product', 'relatedProducts'));
    }
}
