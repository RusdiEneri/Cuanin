@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Wishlist Saya</h1>
        <p class="text-gray-500">Barang-barang impian yang Anda simpan.</p>
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($wishlists as $item)
            <div class="bg-white border border-border-color rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-500/20 hover:border-primary transition duration-300 group flex flex-col relative">
                
                <!-- Remove Wishlist Form -->
                <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-3 right-3 z-20">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                    <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full text-danger hover:bg-red-50 hover:text-red-700 transition shadow-sm border border-red-100">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>

                <a href="{{ route('product.show', $item->product->slug) }}" class="relative aspect-square bg-gray-50 overflow-hidden block">
                    @if($item->product->primaryImage)
                        @php
                            $imgUrl = str_starts_with($item->product->primaryImage->image_path, 'http') ? $item->product->primaryImage->image_path : asset('storage/' . $item->product->primaryImage->image_path);
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i data-lucide="image" class="w-12 h-12"></i>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-gray-700 flex items-center gap-1 shadow-sm">
                        <i data-lucide="{{ $item->product->condition == 'BNOB' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $item->product->condition == 'BNOB' ? 'text-secondary fill-current' : 'text-success' }}"></i> {{ $item->product->condition }}
                    </div>
                </a>
                
                <div class="p-3 sm:p-4 flex flex-col flex-grow">
                    <a href="{{ route('product.show', $item->product->slug) }}" class="mb-1">
                        <h3 class="text-[13px] sm:text-sm text-gray-700 line-clamp-2 group-hover:text-primary transition leading-relaxed">{{ $item->product->title }}</h3>
                    </a>
                    
                    <div class="mt-auto">
                        <div class="font-bold text-[15px] sm:text-[17px] text-primary mb-1 truncate">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                        
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500 mb-3">
                            <i data-lucide="map-pin" class="w-3 h-3 text-primary flex-shrink-0"></i>
                            <span class="truncate">{{ $item->product->location }}</span>
                        </div>
                        
                        <!-- Lihat Detail Button -->
                        <a href="{{ route('product.show', $item->product->slug) }}" class="w-full bg-blue-50 text-primary py-2 rounded-xl text-[13px] sm:text-sm font-medium hover:bg-primary hover:text-white transition flex items-center justify-center gap-2">
                            <i data-lucide="eye" class="w-4 h-4"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center justify-center p-16 text-center">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-danger mb-4">
                <i data-lucide="heart" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Wishlist Masih Kosong</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">Anda belum menambahkan produk apapun ke wishlist. Jelajahi marketplace untuk menemukan barang impian Anda.</p>
            <a href="{{ route('marketplace') }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
