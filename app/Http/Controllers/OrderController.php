<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Order History for Buyer
    public function index()
    {
        $orders = Order::with('items.product.primaryImage')->where('user_id', Auth::id())->latest()->get();
        return view('order.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product.primaryImage', 'items.product.user')->where('user_id', Auth::id())->findOrFail($id);
        return view('order.show', compact('order'));
    }
}
