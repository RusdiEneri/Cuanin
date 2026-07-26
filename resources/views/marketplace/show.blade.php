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
                        <div id="image-slider" class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar w-full h-full" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @php
                                $sortedImages = $product->productImages->sortByDesc('is_primary');
                            @endphp
                            
                            @foreach($sortedImages as $index => $image)
                                <div class="w-full h-full flex-shrink-0 snap-center relative">
                                    @php
                                        $imgUrl = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path);
                                    @endphp
                                    <img src="{{ $imgUrl }}" alt="{{ $product->title }} - Image {{ $index + 1 }}" class="w-full h-full object-cover">
                                    
                                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5 z-10">
                                        @foreach($sortedImages as $dotIndex => $dotImage)
                                            <div class="w-2 h-2 rounded-full {{ $index === $dotIndex ? 'bg-white' : 'bg-white/50' }}"></div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
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
                
                <!-- 🚨 BANNER STATUS PRODUK 🚨 -->
                @if($product->status === 'sold')
                    <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl flex items-center gap-2">
                        <i data-lucide="package-check" class="w-5 h-5"></i>
                        <span class="font-semibold">Maaf, produk ini sudah terjual.</span>
                    </div>
                @elseif($product->status === 'archived')
                    <div class="mb-4 bg-gray-100 border border-gray-200 text-gray-800 px-4 py-3 rounded-xl flex items-center gap-2">
                        <i data-lucide="archive" class="w-5 h-5"></i>
                        <span class="font-semibold">Produk ini sedang diarsipkan oleh penjual.</span>
                    </div>
                @endif

                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-medium text-primary bg-blue-50 px-2.5 py-1 rounded-md">{{ $product->category->name }}</span>
                            <span class="text-xs font-medium flex items-center gap-1 {{ $product->condition == 'BNOB' ? 'text-secondary bg-yellow-50' : 'text-success bg-green-50' }} px-2.5 py-1 rounded-md">
                                <i data-lucide="{{ $product->condition == 'BNOB' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $product->condition == 'BNOB' ? 'fill-current' : '' }}"></i> {{ $product->condition }}
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
                    
                    <!-- Tombol Wishlist hanya muncul jika produk active -->
                    @if($product->status === 'active')
                        <form action="{{ route('wishlist.toggle') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:text-danger hover:bg-red-50 hover:border-red-100 transition shadow-sm" title="Tambah ke Wishlist">
                                <i data-lucide="heart" class="w-5 h-5"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="flex items-end gap-3 mb-8 pb-8 border-b border-gray-100">
                    <div class="font-bold text-3xl text-primary">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    @if($product->stock > 0)
                        <div class="text-sm font-medium text-gray-500 mb-1">Sisa Stok: <span class="text-gray-900">{{ $product->stock }}</span></div>
                    @else
                        <div class="text-sm font-semibold text-danger bg-red-50 px-2 py-0.5 rounded-md mb-1">Stok Habis</div>
                    @endif
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

                <!-- 🚨 ACTION BUTTONS (Dinamis berdasarkan Status) 🚨 -->
<div class="mt-auto">
    @if($product->status === 'active' && $product->stock > 0)
        @php
            $waNumber = $product->user->phone_number;
            if (substr($waNumber, 0, 1) == '0') {
                $waNumber = '62' . substr($waNumber, 1);
            }
            $waText = "Halo *" . $product->user->name . "*, saya tertarik dengan barang *" . $product->title . "* yang Anda jual di Cuanin seharga Rp " . number_format($product->price, 0, ',', '.') . ". Apakah masih tersedia?";
        @endphp

        <!-- Pengatur Jumlah & Action Buttons di-wrap form agar bisa submit quantity -->
        <form id="add-to-cart-form" action="{{ route('cart.store') }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="flex items-center gap-4 mb-5 p-3 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-sm font-medium text-gray-700">Atur Jumlah:</span>
                <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm">
                    <button type="button" onclick="const q = document.getElementById('qty'); if(q.value > 1) q.value--;" class="px-3 py-1.5 text-gray-500 hover:text-primary hover:bg-blue-50 rounded-l-lg transition">-</button>
                    <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center border-none focus:ring-0 text-sm font-bold text-gray-900 p-0" readonly>
                    <button type="button" onclick="const q = document.getElementById('qty'); if(q.value < {{ $product->stock }}) q.value++;" class="px-3 py-1.5 text-gray-500 hover:text-primary hover:bg-blue-50 rounded-r-lg transition">+</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                {{-- Baris 1: Nego Harga (kiri) | Masukkan Keranjang (kanan) --}}
                <button type="button"
                        onclick="document.getElementById('nego-modal').classList.remove('hidden')"
                        class="w-full py-3.5 px-4 bg-yellow-400 text-dark font-semibold rounded-xl hover:bg-yellow-500 transition shadow-md shadow-yellow-500/20 flex items-center justify-center gap-2 text-sm sm:text-base">
                    <i data-lucide="handshake" class="w-5 h-5"></i> Nego Harga
                </button>

                <button type="submit"
                        class="w-full py-3.5 px-4 bg-primary text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 text-sm sm:text-base">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i> Masukkan Keranjang
                </button>
            
                {{-- Baris 2: Beli Sekarang full width (col-span-2) --}}
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank"
                   onclick="this.href = 'https://wa.me/{{ $waNumber }}?text=' + encodeURIComponent('Halo *{{ $product->user->name }}*, saya tertarik membeli *' + document.getElementById('qty').value + 'x {{ $product->title }}* seharga Rp {{ number_format($product->price, 0, ',', '.') }}/pcs. Apakah masih tersedia?')"
                   class="col-span-2 w-full py-3.5 px-4 bg-white border border-primary text-primary font-semibold rounded-xl hover:bg-blue-50 transition flex items-center justify-center gap-2">
                    <i data-lucide="message-circle" class="w-5 h-5"></i> Beli Sekarang
                </a>
            </div>
        </form>
    @else
        <!-- Tampilan jika produk Terjual / Diarsipkan / Stok Habis -->
        <button disabled class="w-full py-3.5 px-4 bg-gray-200 text-gray-500 font-semibold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
            <i data-lucide="ban" class="w-5 h-5"></i> {{ $product->stock <= 0 ? 'Stok Habis' : 'Produk Tidak Tersedia' }}
        </button>
    @endif
</div>
            </div>
        </div>
    </div>

    <!-- Nego Modal -->
    <div id="nego-modal" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="p-6 sm:p-8">
                <button type="button" onclick="document.getElementById('nego-modal').classList.add('hidden')" class="absolute right-4 top-4 w-8 h-8 bg-gray-50 hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:text-gray-700 transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
                <div class="flex items-start gap-4 mb-6 pr-8">
                    <div class="w-12 h-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600 flex-shrink-0">
                        <i data-lucide="handshake" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Ajukan Nego Harga</h2>
                        <p class="mt-1 text-sm text-gray-500">Masukkan harga yang ingin Anda tawarkan ke penjual.</p>
                    </div>
                </div>

                <form action="{{ route('negotiations.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <label for="offered_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Nego</label>
                        <div class="relative mb-3">
                            <input id="offered_price" name="offered_price" type="number" min="1000" required value="{{ old('offered_price') }}" placeholder="Contoh: 150000" class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-4 pr-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        
                        <!-- Quick Nego Percentage -->
                        <div class="mb-5">
                            <p class="text-xs text-gray-500 mb-2">Penawaran Cepat (otomatis hitung):</p>
                            <div class="flex gap-2">
                                @php
                                    $price = $product->price;
                                    $percentages = [10, 15, 20, 25];
                                @endphp
                                @foreach($percentages as $percent)
                                    @php
                                        $negoPrice = $price - ($price * ($percent / 100));
                                    @endphp
                                    <button type="button" 
                                            onclick="document.getElementById('offered_price').value = '{{ $negoPrice }}'"
                                            class="flex-1 py-2.5 px-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:border-primary hover:text-primary hover:bg-blue-50 transition text-center">
                                        -{{ $percent }}%
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @error('offered_price')
                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="button" onclick="document.getElementById('nego-modal').classList.add('hidden')" class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" class="w-full px-4 py-3 rounded-2xl bg-primary text-white hover:bg-blue-700 font-semibold transition">
                            Kirim Nego
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
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
                        <i data-lucide="{{ $item->condition == 'BNOB' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $item->condition == 'BNOB' ? 'text-secondary fill-current' : 'text-success' }}"></i> {{ $item->condition }}
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

<!-- ==================== MODAL SUKSES TAMBAH KERANJANG ==================== -->
<div id="cart-success-modal" onclick="if(event.target === this) closeCartSuccessModal()" class="fixed inset-0 z-50 hidden bg-dark/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden p-6 sm:p-8 transform scale-95 transition-transform duration-300">
        <button type="button" onclick="closeCartSuccessModal()" class="absolute right-4 top-4 w-8 h-8 bg-gray-50 hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>

        <div class="text-center">
            <div class="w-16 h-16 bg-green-50 text-success rounded-full flex items-center justify-center mx-auto mb-4 border border-green-100 shadow-sm">
                <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 mb-1">Berhasil Ditambahkan!</h3>
            <p class="text-sm text-gray-500 mb-6">Produk pilihan Anda telah dimasukkan ke dalam keranjang belanja.</p>

            <!-- Card Summary Produk -->
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-3 text-left mb-6">
                <div class="w-14 h-14 rounded-xl bg-white border border-gray-200 overflow-hidden flex-shrink-0">
                    @if($product->displayImageUrl())
                        <img src="{{ $product->displayImageUrl() }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i data-lucide="image" class="w-5 h-5"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $product->title }}</h4>
                    <div class="text-xs text-gray-500 mt-0.5">Jumlah: <span id="cart-added-qty" class="font-bold text-gray-900">1</span> pcs</div>
                    <div class="text-xs font-bold text-primary mt-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Tombol Opsi -->
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="closeCartSuccessModal()" class="w-full py-3 px-4 rounded-xl border border-border-color text-gray-700 font-semibold bg-white hover:bg-gray-50 transition text-sm">
                    Lanjut Belanja
                </button>
                <a href="{{ route('cart.index') }}" class="w-full py-3 px-4 rounded-xl bg-primary text-white font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 text-sm flex items-center justify-center gap-1.5">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> Lihat Keranjang
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ==================== TOAST ERROR KERANJANG ==================== -->
<div id="cart-error-toast" class="fixed top-10 left-1/2 -translate-x-1/2 z-[100] max-w-md w-full px-4 transform -translate-y-[150%] opacity-0 transition-all duration-500 ease-out pointer-events-none">
    <div class="bg-white border border-red-200 rounded-2xl shadow-2xl p-4 flex items-center gap-3 pointer-events-auto">
        <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0 text-danger">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
        </div>
        <div class="flex-1 text-sm font-medium text-gray-800" id="cart-error-toast-msg">
            Gagal menambahkan ke keranjang.
        </div>
        <button onclick="hideCartErrorToast()" class="text-gray-400 hover:text-gray-600">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cartForm = document.getElementById('add-to-cart-form');
        if (!cartForm) return;

        cartForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            
            const submitBtn = cartForm.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Memproses...`;
            if (window.lucide) lucide.createIcons();

            try {
                const formData = new FormData(cartForm);
                const response = await fetch(cartForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.status === 401) {
                    window.location.href = data.redirect || '{{ route("login") }}';
                    return;
                }

                if (response.ok && data.status === 'success') {
                    // Update header cart badge
                    document.querySelectorAll('.cart-badge-count').forEach(el => {
                        el.textContent = data.cart_count;
                        el.classList.remove('hidden');
                    });

                    // Set quantity in modal
                    const qtyVal = document.getElementById('qty') ? document.getElementById('qty').value : 1;
                    document.getElementById('cart-added-qty').textContent = qtyVal;

                    // Open success popup modal
                    openCartSuccessModal();
                } else {
                    showCartErrorToast(data.message || 'Gagal menambahkan ke keranjang.');
                }
            } catch (err) {
                console.error(err);
                showCartErrorToast('Terjadi kesalahan koneksi. Silakan coba lagi.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                if (window.lucide) lucide.createIcons();
            }
        });
    });

    function openCartSuccessModal() {
        const modal = document.getElementById('cart-success-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
        if (window.lucide) lucide.createIcons();
    }

    function closeCartSuccessModal() {
        const modal = document.getElementById('cart-success-modal');
        if (!modal) return;
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    let toastErrorTimeout = null;
    function showCartErrorToast(msg) {
        const toast = document.getElementById('cart-error-toast');
        const msgEl = document.getElementById('cart-error-toast-msg');
        if (!toast || !msgEl) return;

        msgEl.textContent = msg;
        toast.classList.remove('-translate-y-[150%]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        if (window.lucide) lucide.createIcons();

        clearTimeout(toastErrorTimeout);
        toastErrorTimeout = setTimeout(() => hideCartErrorToast(), 5000);
    }

    function hideCartErrorToast() {
        const toast = document.getElementById('cart-error-toast');
        if (!toast) return;
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('-translate-y-[150%]', 'opacity-0');
    }
</script>
@endpush
@endsection