<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product' => function ($query) {
            $query->with(['primaryImage', 'productImages', 'user']);
        }])->where('user_id', Auth::id())->get();

        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        return view('cart.index', compact('carts', 'total'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'unauthenticated',
                    'message' => 'Silakan masuk terlebih dahulu untuk menambahkan barang ke keranjang.',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->status !== 'active') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Produk ini sudah tidak tersedia (Terjual/Diarsipkan).'], 422);
            }
            return back()->with('error', 'Produk ini sudah tidak tersedia (Terjual/Diarsipkan).');
        }

        if ($product->user_id == Auth::id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak bisa membeli produk Anda sendiri.'], 422);
            }
            return back()->with('error', 'Anda tidak bisa membeli produk Anda sendiri.');
        }

        if ($product->stock < 1) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Stok produk ini sudah habis.'], 422);
            }
            return back()->with('error', 'Stok produk ini sudah habis.');
        }

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->first();

        $newQuantity = $cart ? $cart->quantity + $request->quantity : $request->quantity;
        
        if ($newQuantity > $product->stock) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Jumlah melebihi sisa stok yang tersedia (' . $product->stock . ').'], 422);
            }
            return back()->with('error', 'Jumlah melebihi sisa stok yang tersedia (' . $product->stock . ').');
        }

        if ($cart) {
            $cart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            $cartCount = Cart::where('user_id', Auth::id())->count();
            return response()->json([
                'status'     => 'success',
                'message'    => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        if ($request->quantity > $cart->product->stock) {
            return back()->with('error', 'Jumlah melebihi sisa stok yang tersedia (' . $cart->product->stock . ').');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request)
    {
        Cart::where('user_id', $request->user()->id)->delete();
        return redirect()->route('cart.index')->with('success', 'Semua item di keranjang berhasil dihapus.');
    }
}