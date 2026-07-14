<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return view('admin.dashboard', compact('totalUsers', 'totalCategories', 'totalProducts', 'totalOrders'));
    }

    public function users()
    {
        $users = User::latest()->get();
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
        $categories = Category::withCount('products')->latest()->get();
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
