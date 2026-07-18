@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/" class="hover:text-primary transition flex items-center gap-1">
                    <i data-lucide="home" class="w-4 h-4"></i> Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-1"></i>
                    <a href="{{ route('marketplace') }}" class="hover:text-primary transition">Marketplace</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-1"></i>
                    <a href="{{ route('marketplace', ['category' => $product->category_id]) }}" class="hover:text-primary transition">{{ $product->category->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-1"></i>
                    <span class="text-gray-900 font-medium line-clamp-1">{{ $product->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

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

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden mb-12">
        <div class="flex flex-col md:flex-row">
            
            <!-- Product Images Gallery (Swipeable) -->
            <div class="w-full md:w-1/2 border-b md:border-b-0 md:border-r border-border-color bg-gray-50/50">
                @if($product->productImages->count() > 0)
                    <div class="relative w-full aspect-square overflow-hidden group">
                        <!-- Scrollable container -->
                        <div id="image-slider" class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar w-full h-full" style="scrollbar-width: none; -ms-overflow-style: none;">
                            <!-- Reorder so primary image is first -->
                            @php
                                $sortedImages = $product->productImages->sortByDesc('is_primary');
                            @endphp
                            
                            @foreach($sortedImages as $index => $image)
                                <div class="w-full h-full flex-shrink-0 snap-center relative">
                                    @php
                                        $imgUrl = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path);
                                    @endphp
                                    <img src="{{ $imgUrl }}" alt="{{ $product->title }} - Image {{ $index + 1 }}" class="w-full h-full object-cover">
                                    
                                    <!-- Pagination Indicator -->
                                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5 z-10">
                                        @foreach($sortedImages as $dotIndex => $dotImage)
                                            <div class="w-2 h-2 rounded-full {{ $index === $dotIndex ? 'bg-white' : 'bg-white/50' }}"></div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Navigation Arrows (Optional, hidden on small screens) -->
                        @if($product->productImages->count() > 1)
                        <button onclick="document.getElementById('image-slider').scrollBy({left: -document.getElementById('image-slider').offsetWidth, behavior: 'smooth'})" class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center text-gray-800 shadow-md opacity-0 group-hover:opacity-100 transition hidden md:flex">
                            <i data-lucide="chevron-left" class="w-6 h-6"></i>
                        </button>
                        <button onclick="document.getElementById('image-slider').scrollBy({left: document.getElementById('image-slider').offsetWidth, behavior: 'smooth'})" class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center text-gray-800 shadow-md opacity-0 group-hover:opacity-100 transition hidden md:flex">
                            <i data-lucide="chevron-right" class="w-6 h-6"></i>
                        </button>
                        @endif
                    </div>
                    
                    <style>
                        .hide-scrollbar::-webkit-scrollbar {
                            display: none;
                        }
                    </style>
                @else
                    <div class="w-full aspect-square flex flex-col items-center justify-center text-gray-300 bg-white">
                        <i data-lucide="image" class="w-16 h-16 mb-2"></i>
                        <span class="text-sm">Tidak ada foto</span>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-medium text-primary bg-blue-50 px-2.5 py-1 rounded-md">{{ $product->category->name }}</span>
                            <span class="text-xs font-medium flex items-center gap-1 {{ $product->condition == 'Barang Baru' ? 'text-secondary bg-yellow-50' : 'text-success bg-green-50' }} px-2.5 py-1 rounded-md">
                                <i data-lucide="{{ $product->condition == 'Barang Baru' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $product->condition == 'Barang Baru' ? 'fill-current' : '' }}"></i> {{ $product->condition }}
                            </span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-2">{{ $product->title }}</h1>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <div class="flex items-center gap-1">
                                <i data-lucide="eye" class="w-4 h-4"></i> Dilihat {{ number_format($product->views) }} kali
                            </div>
                            <div class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-4 h-4"></i> {{ $product->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('wishlist.toggle') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:text-danger hover:bg-red-50 hover:border-red-100 transition shadow-sm" title="Tambah ke Wishlist">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>

                <div class="font-bold text-3xl text-primary mb-8 pb-8 border-b border-gray-100">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <div class="mb-8 flex-grow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Produk</h3>
                    <div class="prose prose-sm md:prose-base text-gray-600 max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5 mb-8 flex items-center gap-4 border border-gray-100">
                    <div class="w-14 h-14 bg-gray-200 rounded-full overflow-hidden flex-shrink-0">
                        @if($product->user->avatar)
                            <img src="{{ asset('storage/' . $product->user->avatar) }}" alt="{{ $product->user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-100 flex items-center justify-center text-primary font-bold text-xl">
                                {{ substr($product->user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h4 class="font-semibold text-gray-900">{{ $product->user->name }}</h4>
                        <div class="flex items-center gap-1 text-sm text-gray-500 mt-1">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i> {{ $product->location }}
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="alert('Fitur Profil Penjual belum diimplementasi')" class="px-4 py-2 bg-white border border-border-color text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                        Kunjungi Profil
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4 mt-auto">
                    @php
                        $waNumber = $product->user->phone_number;
                        if (substr($waNumber, 0, 1) == '0') {
                            $waNumber = '62' . substr($waNumber, 1);
                        }
                        $waText = "Halo *" . $product->user->name . "*, saya tertarik dengan barang *" . $product->title . "* yang Anda jual di Cuanin seharga Rp " . number_format($product->price, 0, ',', '.') . ". Apakah masih tersedia?";
                    @endphp
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank" class="w-full py-3.5 px-4 bg-white border border-primary text-primary font-semibold rounded-xl hover:bg-blue-50 transition flex items-center justify-center gap-2">
                        <i data-lucide="message-circle" class="w-5 h-5"></i> Chat Penjual
                    </a>
                    <button type="button" onclick="document.getElementById('nego-modal').classList.remove('hidden')" class="w-full py-3.5 px-4 bg-yellow-400 text-dark font-semibold rounded-xl hover:bg-yellow-500 transition shadow-md shadow-yellow-500/20 flex items-center justify-center gap-2">
                        <i data-lucide="handshake" class="w-5 h-5"></i> Nego Harga
                    </button>
                    <form action="{{ route('cart.store') }}" method="POST" class="w-full col-span-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full py-3.5 px-4 bg-primary text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i> Masukkan Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Nego Modal -->
    <div id="nego-modal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Ajukan Penawaran</h3>
                <button type="button" onclick="document.getElementById('nego-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('negotiations.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Barang</label>
                    <div class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Penawaran Anda</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                        <input type="number" name="offered_price" min="1" required class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none" placeholder="Masukkan harga nego">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('nego-modal').classList.add('hidden')" class="w-1/2 py-3 px-4 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-3 px-4 bg-primary text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Kirim Tawaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div>
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Produk Serupa</h2>
                <p class="text-gray-500">Mungkin Anda juga tertarik dengan barang ini</p>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($relatedProducts as $item)
            <div class="bg-white border border-border-color rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-500/5 transition duration-300 group flex flex-col">
                <a href="{{ route('product.show', $item->slug) }}" class="relative aspect-square bg-gray-50 overflow-hidden block">
                    @if($item->primaryImage)
                        @php
                            $imgUrl = str_starts_with($item->primaryImage->image_path, 'http') ? $item->primaryImage->image_path : asset('storage/' . $item->primaryImage->image_path);
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i data-lucide="image" class="w-10 h-10"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-xs font-semibold text-gray-700 flex items-center gap-1 shadow-sm">
                        <i data-lucide="{{ $item->condition == 'Barang Baru' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $item->condition == 'Barang Baru' ? 'text-secondary fill-current' : 'text-success' }}"></i> {{ $item->condition }}
                    </div>
                </a>
                <div class="p-4 flex-grow flex flex-col">
                    <a href="{{ route('product.show', $item->slug) }}">
                        <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1 line-clamp-2 group-hover:text-primary transition">{{ $item->title }}</h3>
                    </a>
                    <div class="font-bold text-primary mt-auto pt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
