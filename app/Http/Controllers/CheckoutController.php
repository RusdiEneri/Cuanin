<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product.user')->where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = $carts->sum(function($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // Group carts by seller for display
        $cartsBySeller = $carts->groupBy('product.user_id');

        return view('checkout.index', compact('carts', 'total', 'cartsBySeller'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();
        try {
            $totalAmount = $carts->sum(function($cart) {
                return $cart->product->price * $cart->quantity;
            });

            // For simplicity, we create one order for the entire checkout, 
            // even if there are multiple sellers.
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_address' => $request->address,
                // payment_method can be saved in order_notes or we can just assume it for now
                // since there's no payment_method column in orders table
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);

                // Change product status to sold
                $cart->product->update(['status' => 'sold']);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // WhatsApp Redirect Logic
            $seller = $carts->first()->product->user;
            $phoneNumber = $seller->phone_number;
            
            // Format phone number to start with 62
            if (substr($phoneNumber, 0, 1) == '0') {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            }

            $message = "Halo *" . $seller->name . "*, saya ingin memesan barang berikut dari Cuanin (Pesanan #" . $order->id . "):\n\n";
            foreach ($carts as $cart) {
                $message .= "- " . $cart->product->title . " (" . $cart->quantity . "x) - Rp " . number_format($cart->product->price, 0, ',', '.') . "\n";
            }
            $message .= "\n*Total:* Rp " . number_format($totalAmount, 0, ',', '.') . "\n";
            $message .= "\n*Alamat Pengiriman:*\n" . $request->address . "\n\n";
            $message .= "Mohon informasi untuk pembayarannya. Terima kasih.";

            $whatsappUrl = "https://wa.me/" . $phoneNumber . "?text=" . urlencode($message);

            return redirect()->away($whatsappUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }
}
