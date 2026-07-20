@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-primary rounded-3xl overflow-hidden relative mb-16 shadow-xl shadow-blue-900/20">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-primary"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="relative z-10 px-8 py-16 md:py-24 md:px-16 flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 text-white mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Temukan <span class="text-secondary">Barang Bekas</span> Berkualitas!
                </h1>
                <p class="text-blue-100 text-lg md:text-xl mb-8 max-w-lg leading-relaxed">
                    Marketplace terpercaya untuk jual beli barang preloved. Aman, mudah, dan penuh cuan.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('marketplace') }}" class="bg-secondary text-dark font-semibold px-8 py-3.5 rounded-full text-center hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/30 transform hover:-translate-y-1">
                        Mulai Belanja
                    </a>
                    <a href="javascript:void(0)" onclick="alert('Panduan berjualan segera hadir!')" class="bg-white/10 backdrop-blur-md text-white border border-white/20 font-semibold px-8 py-3.5 rounded-full text-center hover:bg-white/20 transition">
                        Cara Jualan
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 flex items-center justify-center relative">
                <div class="relative w-72 md:w-96 h-auto">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-secondary rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
                    <div class="absolute top-0 left-10 w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
                    <div class="absolute -bottom-8 left-20 w-48 h-48 bg-purple-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-4000"></div>
                    
                    <div class="relative bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary">
                                <i data-lucide="smartphone" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">iPhone 13 Pro</h3>
                                <p class="text-blue-200 text-sm">Like New • 98% Mulus</p>
                            </div>
                        </div>
                        <div class="w-full h-56 bg-white rounded-xl mb-4 overflow-hidden p-4 flex items-center justify-center">
                            <img src="https://jakartaberkamera.com/wp-content/uploads/2022/08/sewa-rental-iphone13-pro-jakarta-1.jpg" alt="iPhone 13 Pro" class="max-w-full max-h-full object-contain">
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary font-bold text-xl">Rp 10.500.000</span>
                            <button class="bg-white text-primary px-4 py-1.5 rounded-full text-sm font-semibold">Beli</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-16">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Kategori Pilihan</h2>
                <p class="text-gray-500">Temukan barang incaranmu dari berbagai kategori</p>
            </div>
            <a href="{{ route('marketplace') }}" class="text-primary font-medium hover:underline hidden sm:block">Lihat Semua</a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('marketplace', ['category' => $cat->id]) }}" class="bg-white border border-border-color rounded-2xl p-6 flex flex-col items-center justify-center gap-3 hover:border-primary hover:shadow-lg hover:shadow-blue-500/10 transition group">
                <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center text-primary group-hover:scale-110 transition">
                    <i data-lucide="{{ $cat->icon ?? 'box' }}" class="w-7 h-7"></i>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-primary transition">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <div class="mb-16">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Rekomendasi Terbaru</h2>
                <p class="text-gray-500">Barang-barang bekas berkualitas yang baru saja diunggah</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($latestProducts as $item)
            <div class="bg-white border border-border-color rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-500/5 transition duration-300 group flex flex-col relative">
                <a href="{{ route('product.show', $item->slug) }}" class="relative aspect-square bg-gray-50 overflow-hidden block">
                    @if($item->primaryImage)
                        @php
                            $imgUrl = str_starts_with($item->primaryImage->image_path, 'http') ? $item->primaryImage->image_path : asset('storage/' . $item->primaryImage->image_path);
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i data-lucide="image" class="w-12 h-12"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-gray-700 flex items-center gap-1 shadow-sm">
                        <i data-lucide="{{ $item->condition == 'Barang Baru' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $item->condition == 'Barang Baru' ? 'text-secondary fill-current' : 'text-success' }}"></i> {{ $item->condition }}
                    </div>
                </a>
                
                <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-3 right-3 z-20">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                    <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full text-gray-400 hover:text-danger hover:bg-red-50 transition shadow-sm" title="Tambah ke Wishlist">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                    </button>
                </form>
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $item->location }}
                    </div>
                    <a href="{{ route('product.show', $item->slug) }}">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 group-hover:text-primary transition leading-snug">{{ $item->title }}</h3>
                    </a>
                    <div class="font-bold text-lg text-primary mt-auto pt-2 mb-3">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    <div class="flex items-center gap-3 border-t border-gray-100 pt-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-primary font-bold text-xs overflow-hidden flex-shrink-0">
                            @if($item->user->avatar)
                                <img src="{{ asset('storage/' . $item->user->avatar) }}" alt="{{ $item->user->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($item->user->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 line-clamp-1">{{ $item->user->name }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('marketplace') }}" class="inline-flex items-center gap-2 bg-white border border-border-color text-gray-700 px-6 py-2.5 rounded-full font-medium hover:bg-gray-50 hover:text-primary transition shadow-sm">
                Lihat Semua Produk <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</div>
@endsection