@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Checkout</h1>
        <p class="text-gray-500">Selesaikan pesanan Anda dengan mengisi detail pengiriman dan pembayaran.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl border border-red-100">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Details Form -->
            <div class="w-full lg:w-2/3 space-y-6">
                
                <!-- Alamat Pengiriman -->
                <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i> Alamat Pengiriman
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="address" rows="3" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">{{ Auth::user()->address }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i> Metode Pembayaran
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border border-border-color rounded-xl cursor-pointer hover:border-primary transition group">
                            <input type="radio" name="payment_method" value="bank_transfer" class="w-4 h-4 text-primary focus:ring-primary border-gray-300" checked>
                            <span class="ml-3 font-medium text-gray-900 group-hover:text-primary">Transfer Bank</span>
                            <i data-lucide="building" class="absolute right-4 w-5 h-5 text-gray-400 group-hover:text-primary"></i>
                        </label>
                        <label class="relative flex items-center p-4 border border-border-color rounded-xl cursor-pointer hover:border-primary transition group">
                            <input type="radio" name="payment_method" value="cod" class="w-4 h-4 text-primary focus:ring-primary border-gray-300">
                            <span class="ml-3 font-medium text-gray-900 group-hover:text-primary">COD (Bayar di Tempat)</span>
                            <i data-lucide="banknote" class="absolute right-4 w-5 h-5 text-gray-400 group-hover:text-primary"></i>
                        </label>
                    </div>
                </div>

                <!-- Produk Dipesan -->
                <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-primary"></i> Pesanan Anda
                    </h2>
                    
                    <div class="space-y-6">
                        @foreach($cartsBySeller as $sellerId => $sellerCarts)
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <div class="font-semibold text-gray-900 mb-3 pb-3 border-b border-gray-200">
                                    Penjual: {{ $sellerCarts->first()->product->user->name }}
                                </div>
                                <div class="space-y-4">
                                    @foreach($sellerCarts as $item)
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-white rounded-lg overflow-hidden border border-gray-100 flex-shrink-0">
                                            @if($item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <i data-lucide="image" class="w-6 h-6"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-medium text-gray-900 line-clamp-1">{{ $item->product->title }}</h4>
                                            <div class="text-sm text-gray-500">{{ $item->product->condition }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-primary">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500">Qty: {{ $item->quantity }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-4 mb-6 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Harga ({{ $carts->count() }} barang)</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Pengiriman</span>
                            <span>Gratis</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Layanan</span>
                            <span>Rp 0</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total Tagihan</span>
                            <span class="font-bold text-xl text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Buat Pesanan
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-4">
                        Dengan membuat pesanan, Anda menyetujui Syarat & Ketentuan yang berlaku.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
