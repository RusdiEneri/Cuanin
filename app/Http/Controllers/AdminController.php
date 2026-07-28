<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        $pendingProductsCount = Product::where('status', 'pending')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalCategories', 'totalProducts', 'totalOrders', 'pendingProductsCount'));
    }

    public function products(Request $request)
    {
        $status = $request->query('status', 'pending'); // Default tab: pending verification

        $query = Product::with(['user', 'category', 'primaryImage', 'productImages'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->paginate(10)->withQueryString();

        $counts = [
            'pending'  => Product::where('status', 'pending')->count(),
            'active'   => Product::where('status', 'active')->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
            'draft'    => Product::where('status', 'draft')->count(),
            'all'      => Product::count(),
        ];

        return view('admin.products.index', compact('products', 'status', 'counts'));
    }

    public function updateProductStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,rejected,pending,archived',
        ]);

        $product = Product::findOrFail($id);
        $product->update(['status' => $request->status]);

        $statusLabels = [
            'active'   => 'disetujui dan dipublikasikan',
            'rejected' => 'ditolak',
            'pending'  => 'diubah menjadi pending',
            'archived' => 'diarsipkan',
        ];

        $message = "Produk \"{$product->title}\" berhasil " . ($statusLabels[$request->status] ?? 'diperbarui') . '.';

        return back()->with('success', $message);
    }

    public function destroyProduct($id)
    {
        $product = Product::with('productImages')->findOrFail($id);

        foreach ($product->productImages as $image) {
            if (!str_starts_with($image->image_path, 'http')) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            $image->delete();
        }

        $productTitle = $product->title;
        $product->delete();

        return back()->with('success', "Produk \"{$productTitle}\" berhasil dihapus dari sistem.");
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:pembeli,penjual,admin']);
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function categories()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'icon' => $request->icon ?? 'box',
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
