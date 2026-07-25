@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <!-- <a href="{{ route('order.index') }}" class="hover:text-primary transition flex items-center gap-1">
                    <i data-lucide="package" class="w-4 h-4"></i> Riwayat Pesanan
                </a> -->
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-1"></i>
                    <span class="text-gray-900 font-medium">Detail #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden mb-8">
        
        <!-- Status Header -->
        <div class="bg-gray-50 p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-bold {{ $statusColors[$order->status] }}">
                    {{ $statusLabels[$order->status] }}
                </span>
            </div>
        </div>

        <div class="p-6">
            
            <!-- Items -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Produk</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-20 h-20 bg-white rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                            @if($item->product->primaryImage)
                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i data-lucide="image" class="w-8 h-8"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-semibold text-gray-900 mb-1">
                                <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-primary transition">{{ $item->product->title }}</a>
                            </h4>
                            <div class="text-sm text-gray-500 mb-1">Penjual: {{ $item->product->user->name }}</div>
                            <div class="font-medium text-gray-700">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="sm:text-right mt-2 sm:mt-0">
                            <div class="text-sm text-gray-500 mb-1">Total Harga</div>
                            <div class="font-bold text-primary">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Shipping Info -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Info Pengiriman</h3>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="mb-3">
                            <div class="text-xs text-gray-500 mb-1">Penerima</div>
                            <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-600">{{ Auth::user()->phone_number }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Alamat Pengiriman</div>
                            <div class="text-sm text-gray-700">{{ $order->shipping_address }}</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Pembayaran</h3>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Harga Barang</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Pengiriman</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900">Total Tagihan</span>
                                <span class="font-bold text-xl text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->status == 'pending')
                    <div class="mt-4">
                        <button class="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                            Cara Pembayaran
                        </button>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
