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
                    <span class="text-gray-900 font-medium">Marketplace</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Filter -->
        <aside class="w-full lg:w-1/4">
            <div class="bg-white rounded-3xl p-6 border border-border-color shadow-lg shadow-blue-900/5 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="filter" class="w-5 h-5 text-primary"></i> Filter
                    </h3>
                    <a href="{{ route('marketplace') }}" class="text-sm text-danger hover:underline">Reset</a>
                </div>

                <form action="{{ route('marketplace') }}" method="GET" class="space-y-6">
                    
                    <!-- Search inside filter (optional for mobile) -->
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Kategori -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Kategori</h4>
                        <div class="space-y-2">
                            @foreach($categories as $cat)
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="category" value="{{ $cat->id }}" class="w-4 h-4 text-primary focus:ring-primary border-gray-300" {{ request('category') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="ml-2 text-gray-600 group-hover:text-primary transition">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Harga -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Harga</h4>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="block w-full pl-9 pr-3 py-2 border border-border-color rounded-xl text-sm focus:ring-primary focus:border-primary transition">
                            </div>
                            <span class="text-gray-400">-</span>
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="block w-full pl-9 pr-3 py-2 border border-border-color rounded-xl text-sm focus:ring-primary focus:border-primary transition">
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Kondisi -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Kondisi</h4>
                        <div class="space-y-2 text-sm">
                            @php $conditions = ['Barang Baru', 'Like New', 'Sangat Baik', 'Baik', 'Cukup', 'Rusak Ringan']; @endphp
                            @foreach($conditions as $cond)
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="condition" value="{{ $cond }}" class="w-4 h-4 text-primary focus:ring-primary border-gray-300" {{ request('condition') == $cond ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="ml-2 text-gray-600 group-hover:text-primary transition">{{ $cond }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Lokasi -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Lokasi</h4>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" name="location" placeholder="Masukkan Kota" value="{{ request('location') }}" class="block w-full pl-9 pr-3 py-2 border border-border-color rounded-xl text-sm focus:ring-primary focus:border-primary transition">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-semibold shadow-md shadow-blue-500/20 hover:bg-blue-700 transition">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="w-full lg:w-3/4">
            
            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">Eksplorasi Barang Bekas</h1>
                    <p class="text-sm text-gray-500">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <form action="{{ route('marketplace') }}" method="GET" class="w-full">
                        <!-- Keep existing filters -->
                        @foreach(request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="relative w-full sm:w-48">
                            <select name="sort" onchange="this.form.submit()" class="block w-full pl-4 pr-10 py-2.5 border border-border-color rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none bg-white font-medium cursor-pointer transition">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $item)
                    <div class="bg-white border border-border-color rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-500/10 transition duration-300 group flex flex-col relative">
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
                        <div class="p-5 flex-grow flex flex-col">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-xs font-medium text-primary bg-blue-50 px-2 py-1 rounded-md">{{ $item->category->name }}</span>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $item->location }}
                                </div>
                            </div>
                            <a href="{{ route('product.show', $item->slug) }}">
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 group-hover:text-primary transition leading-snug">{{ $item->title }}</h3>
                            </a>
                            <div class="font-bold text-lg text-gray-900 mt-auto pt-3">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center justify-center p-16 text-center">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-primary mb-4">
                        <i data-lucide="search-x" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">Maaf, kami tidak menemukan produk yang sesuai dengan filter Anda. Coba ubah kata kunci atau pengaturan filter.</p>
                    <a href="{{ route('marketplace') }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Hapus Semua Filter
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
