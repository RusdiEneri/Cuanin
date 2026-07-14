<?php

namespace App\Http\Controllers;

use App\Models\Negotiation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegotiationController extends Controller
{
    public function index()
    {
        $myOffers = Negotiation::with('product.primaryImage', 'seller')
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();
            
        $incomingOffers = Negotiation::with('product.primaryImage', 'buyer')
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();

        return view('negotiations.index', compact('myOffers', 'incomingOffers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offered_price' => 'required|numeric|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->user_id == Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menawar produk sendiri.');
        }

        // Cek apakah sudah ada tawaran pending
        $existing = Negotiation::where('product_id', $product->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki tawaran yang sedang menunggu konfirmasi.');
        }

        Negotiation::create([
            'product_id' => $product->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $product->user_id,
            'offered_price' => $request->offered_price,
            'status' => 'pending'
        ]);

        return redirect()->route('negotiations.index')->with('success', 'Tawaran berhasil dikirim ke penjual!');
    }

    public function accept($id)
    {
        $negotiation = Negotiation::findOrFail($id);
        
        if ($negotiation->seller_id != Auth::id()) {
            abort(403);
        }

        $negotiation->update(['status' => 'accepted']);

        // Auto reject other pending offers for this product
        Negotiation::where('product_id', $negotiation->product_id)
            ->where('id', '!=', $negotiation->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return back()->with('success', 'Tawaran diterima!');
    }

    public function reject($id)
    {
        $negotiation = Negotiation::findOrFail($id);
        
        if ($negotiation->seller_id != Auth::id()) {
            abort(403);
        }

        $negotiation->update(['status' => 'rejected']);

        return back()->with('success', 'Tawaran ditolak.');
    }
}
