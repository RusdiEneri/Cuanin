@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
        <p class="text-gray-500">Periksa kembali barang yang akan Anda beli.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl flex items-center gap-2 border border-red-100">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($carts->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($carts as $item)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pb-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                    
                                    <!-- Image -->
                                    <div class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                                        @if($item->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i data-lucide="image" class="w-8 h-8"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-grow">
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            <h3 class="font-semibold text-gray-900 hover:text-primary transition line-clamp-1 mb-1">{{ $item->product->title }}</h3>
                                        </a>
                                        <div class="text-sm text-gray-500 mb-2">Penjual: {{ $item->product->user->name }}</div>
                                        <div class="font-bold text-primary">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end mt-4 sm:mt-0">
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-danger hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Belanja</h3>
                    
                    <div class="space-y-4 mb-6 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Harga ({{ $carts->count() }} barang)</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total Belanja</span>
                            <span class="font-bold text-xl text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-primary text-white py-3.5 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>

        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center justify-center p-16 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-primary mb-4">
                <i data-lucide="shopping-cart" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">Belum ada produk yang Anda tambahkan ke keranjang. Yuk temukan barang bekas berkualitas di marketplace kami!</p>
            <a href="{{ route('marketplace') }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
