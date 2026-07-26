@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-primary transition flex items-center gap-1">
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
                    <span class="text-gray-900 font-medium line-clamp-1">Profil Penjual</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- ==================== BANNER HEADER TOKO (SHOPEE STYLE) ==================== -->
    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
            
            <!-- Left Box: Info Penjual (Dark Gradient Banner) -->
            <div class="lg:col-span-5 bg-gradient-to-br from-primary to-blue-600 p-6 sm:p-8 text-white flex flex-col justify-between relative overflow-hidden">
                <!-- Background Accent Glow -->
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-white opacity-10 rounded-full blur-xl pointer-events-none"></div>

                <div class="relative z-10 flex items-start gap-4 mb-6">
                    <!-- Avatar Penjual -->
                    <div class="relative flex-shrink-0">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden border-2 border-white/20 bg-white/10 backdrop-blur-md flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                            @if($seller->avatar)
                                <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $seller->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($seller->name, 0, 1)) }}
                            @endif
                        </div>
                        <span class="absolute -bottom-2 -right-1 bg-secondary text-dark text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow border border-white uppercase tracking-wider flex items-center gap-0.5">
                            <i data-lucide="shield-check" class="w-3 h-3 text-dark"></i> Star
                        </span>
                    </div>

                    <!-- Info Penjual -->
                    <div class="flex-grow pt-1">
                        <h1 class="text-xl sm:text-2xl font-bold text-white leading-snug line-clamp-1">{{ $seller->name }}</h1>
                        <p class="text-xs text-blue-200 mt-1 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Aktif Baru Saja
                        </p>
                        <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 backdrop-blur-md text-xs text-gray-200 border border-white/10">
                            <i data-lucide="store" class="w-3.5 h-3.5 text-secondary"></i> Penjual Terverifikasi
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Penjual -->
                <div class="relative z-10 grid grid-cols-2 gap-3 pt-2">
                    @php
                        $waNum = $seller->phone_number;
                        if ($waNum && substr($waNum, 0, 1) == '0') {
                            $waNum = '62' . substr($waNum, 1);
                        }
                        $waMsg = "Halo *" . $seller->name . "*, saya tertarik dengan barang yang Anda jual di Cuanin.";
                    @endphp
                    
                    @if($waNum)
                        <a href="https://wa.me/{{ $waNum }}?text={{ urlencode($waMsg) }}" target="_blank"
                           class="w-full py-2.5 px-3 bg-secondary hover:bg-yellow-400 text-dark font-semibold text-xs sm:text-sm rounded-xl transition flex items-center justify-center gap-1.5 shadow-md">
                            <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi Penjual
                        </a>
                    @else
                        <button onclick="alert('Penjual belum menambahkan nomor WhatsApp.')" 
                                class="w-full py-2.5 px-3 bg-white/20 hover:bg-white/30 text-white font-semibold text-xs sm:text-sm rounded-xl transition flex items-center justify-center gap-1.5 backdrop-blur-md">
                            <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi Penjual
                        </button>
                    @endif

                    <button onclick="copyStoreLink()" 
                            class="w-full py-2.5 px-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold text-xs sm:text-sm rounded-xl transition flex items-center justify-center gap-1.5 backdrop-blur-md">
                        <i data-lucide="share-2" class="w-4 h-4"></i> Bagikan Toko
                    </button>
                </div>
            </div>

            <!-- Right Box: Statistik & Detail Toko -->
            <div class="lg:col-span-7 p-6 sm:p-8 bg-white flex flex-col justify-center">
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-6">
                    
                    <!-- Stat 1: Total Produk -->
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-primary flex items-center justify-center flex-shrink-0">
                            <i data-lucide="package" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Produk Aktif</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">{{ number_format($totalActiveProducts) }}</p>
                        </div>
                    </div>

                    <!-- Stat 2: Total Terjual -->
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-green-50 text-success flex items-center justify-center flex-shrink-0">
                            <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Produk Terjual</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">{{ number_format($totalSoldProducts) }}</p>
                        </div>
                    </div>

                    <!-- Stat 3: Lokasi Toko -->
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Lokasi Toko</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5 line-clamp-1">{{ $sellerLocation }}</p>
                        </div>
                    </div>

                    <!-- Stat 4: Tanggal Bergabung -->
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Bergabung Sejak</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $seller->created_at ? $seller->created_at->format('M Y') : 'Baru' }}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- ==================== FILTER & PENCAARIAN KATALOG TOKO ==================== -->
    <div class="bg-white rounded-3xl border border-border-color p-5 sm:p-6 shadow-sm mb-8">
        <form action="{{ route('seller.profile', $seller->id) }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-between items-center">
            
            <!-- Searching dalam toko -->
            <div class="relative w-full md:w-80">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk di toko ini..." 
                       class="w-full bg-gray-50 border border-border-color rounded-2xl pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary focus:bg-white transition">
            </div>

            <!-- Filter Kategori & Sorting -->
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-end">
                
                @if(isset($sellerCategories) && $sellerCategories->count() > 0)
                <select name="category" onchange="this.form.submit()" class="bg-gray-50 border border-border-color rounded-2xl px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-primary transition">
                    <option value="">Semua Kategori</option>
                    @foreach($sellerCategories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @endif

                <select name="sort" onchange="this.form.submit()" class="bg-gray-50 border border-border-color rounded-2xl px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-primary transition">
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Terendah</option>
                    <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Tertinggi</option>
                </select>

                @if(request('q') || request('category') || request('sort'))
                    <a href="{{ route('seller.profile', $seller->id) }}" class="px-3 py-2.5 bg-red-50 text-danger rounded-2xl text-xs font-semibold hover:bg-red-100 transition flex items-center gap-1">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- ==================== KATALOG PRODUK PENJUAL ==================== -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Produk yang Dijual</h2>
            <p class="text-xs text-gray-500">Menampilkan {{ $products->total() }} barang tersedia dari {{ $seller->name }}</p>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 md:gap-6 mb-10">
            @foreach($products as $item)
                <div class="bg-white border border-border-color rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-500/20 hover:border-primary transition duration-300 group flex flex-col relative">
                    <a href="{{ route('product.show', $item->slug) }}" class="relative aspect-square bg-gray-50 overflow-hidden block">
                        @if($item->displayImageUrl())
                            <img src="{{ $item->displayImageUrl() }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i data-lucide="image" class="w-12 h-12"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-white/90 backdrop-blur-sm px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold text-gray-700 flex items-center gap-1 shadow-sm">
                            <i data-lucide="{{ $item->condition == 'BNOB' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $item->condition == 'BNOB' ? 'text-secondary fill-current' : 'text-success' }}"></i>
                            <span class="line-clamp-1">{{ $item->condition }}</span>
                        </div>
                    </a>

                    <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-2 right-2 sm:top-3 sm:right-3 z-20">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full text-gray-400 hover:text-danger hover:bg-red-50 transition shadow-sm" title="Tambah ke Wishlist">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                    </form>
                    
                    <div class="p-3 sm:p-4 flex flex-col flex-grow">
                        <a href="{{ route('product.show', $item->slug) }}" class="mb-1">
                            <h3 class="text-[13px] sm:text-sm text-gray-700 line-clamp-2 group-hover:text-primary transition leading-relaxed">{{ $item->title }}</h3>
                        </a>
                        <div class="mt-auto">
                            <div class="font-bold text-[15px] sm:text-[17px] text-primary mb-1 truncate">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                                <i data-lucide="map-pin" class="w-3 h-3 text-primary flex-shrink-0"></i>
                                <span class="truncate">{{ $item->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty State jika penjual tidak punya produk -->
        <div class="bg-white rounded-3xl border border-border-color p-12 text-center my-8">
            <div class="w-20 h-20 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-search" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Produk Dijual</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">
                Penjual ini belum memiliki barang aktif yang sedang diperjualbelikan. Silakan lihat penjual atau produk lainnya di Marketplace Cuanin.
            </p>
            <a href="{{ route('marketplace') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white text-sm font-semibold rounded-2xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Marketplace
            </a>
        </div>
    @endif

</div>

<!-- Notification Toast for Copying Store Link -->
<div id="copy-toast" class="fixed bottom-6 right-6 z-50 hidden bg-dark text-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 transition">
    <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
    <span class="text-sm font-medium">Link toko berhasil disalin!</span>
</div>

@push('scripts')
<script>
    function copyStoreLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('hidden');
            if (window.lucide) lucide.createIcons();
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }).catch(err => {
            console.error('Gagal menyalin link: ', err);
        });
    }
</script>
@endpush
@endsection
