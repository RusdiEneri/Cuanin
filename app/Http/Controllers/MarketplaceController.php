<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
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
        $products = $query->latest()->paginate(12)->withQueryString();
        return view('marketplace.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['primaryImage','productImages', 'category', 'user'])->where('slug', $slug)->firstOrFail();

        if( $product->status !== 'active' && $product->user_id !== auth()->id()) {
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

    /**
     * Halaman Profil Penjual (Shopee-style Seller Store Profile)
     */
    public function sellerProfile(Request $request, $id)
    {
        $seller = User::findOrFail($id);

        $query = Product::with(['primaryImage', 'category', 'user'])
            ->where('user_id', $seller->id)
            ->where('status', 'active');

        // Filter Pencarian Toko
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Filter Kategori Toko
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter Kondisi
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
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

        $products = $query->paginate(12)->withQueryString();

        // Statistik Toko Penjual
        $totalActiveProducts = Product::where('user_id', $seller->id)->where('status', 'active')->count();
        $totalSoldProducts = Product::where('user_id', $seller->id)->where('status', 'sold')->count();
        
        // Lokasi Toko Penjual (mengambil dari user address atau produk terbaru)
        $sellerLocation = $seller->address;
        if (!$sellerLocation) {
            $latestLocationProduct = Product::where('user_id', $seller->id)->whereNotNull('location')->latest()->first();
            $sellerLocation = $latestLocationProduct ? $latestLocationProduct->location : 'Indonesia';
        }
        
        // Kategori yang ada di toko penjual ini
        $sellerCategories = Category::whereHas('products', function ($q) use ($seller) {
            $q->where('user_id', $seller->id)->where('status', 'active');
        })->get();

        return view('seller.profile', compact(
            'seller',
            'products',
            'totalActiveProducts',
            'totalSoldProducts',
            'sellerLocation',
            'sellerCategories'
        ));
    }
}
