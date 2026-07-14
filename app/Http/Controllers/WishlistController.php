<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product.primaryImage')->where('user_id', Auth::id())->latest()->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return back()->with('success', 'Produk ditambahkan ke wishlist.');
        }
    }
}
