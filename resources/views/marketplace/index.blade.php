@extends('layouts.app')

@push('styles')
<style>
    @media (min-width: 1024px) {
        .custom-filter-width {
            width: 240px !important;
        }
    }
</style>
@endpush

@section('content')
@php
    // hitung jumlah filter aktif untuk badge di tombol "Filter" (mobile)
    $activeFilters = collect(['category', 'condition', 'location', 'min_price', 'max_price'])
        ->filter(fn($k) => request()->filled($k))->count();

    // url reset: buang semua filter, tapi pertahankan pencarian (q) jika ada
    $resetUrl = route('marketplace', request('q') ? ['q' => request('q')] : []);

    $conditions = ['Barang Baru', 'Like New', 'Sangat Baik', 'Baik', 'Cukup', 'Rusak Ringan'];
@endphp

<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 py-8">

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

        <!-- ================= OVERLAY (mobile only) ================= -->
        <div id="filterOverlay"
             onclick="closeFilter()"
             class="fixed inset-0 bg-black/50 z-[55] lg:hidden hidden transition-opacity"></div>

        <!-- ================= FILTER PANEL =================
             mobile : bottom-sheet popup (fixed + translate-y-full)
             desktop: sidebar biasa (lg:static + lg:translate-y-0)
        -->
        <aside id="filterPanel"
               class="w-full lg:w-80 custom-filter-width lg:flex-shrink-0
                      fixed lg:static inset-x-0 bottom-0 lg:inset-auto
                      z-[60] lg:z-auto lg:sticky lg:top-24
                      transform translate-y-full lg:translate-y-0
                      transition-transform duration-300 ease-out">

            <form action="{{ route('marketplace') }}" method="GET"
                  class="bg-white rounded-t-3xl lg:rounded-3xl border border-border-color
                         shadow-2xl lg:shadow-lg shadow-blue-900/10
                         max-h-[85vh] lg:max-h-none flex flex-col">

                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <!-- ===== Mobile Header (drag handle + judul + close) ===== -->
                <div class="lg:hidden flex-shrink-0">
                    <div class="flex justify-center pt-3 pb-1">
                        <span class="w-10 h-1 rounded-full bg-gray-200"></span>
                    </div>
                    <div class="flex items-center justify-between px-5 pb-4 border-b border-gray-100">
                        <button type="button" onclick="closeFilter()"
                                class="p-1 -ml-1 text-gray-500 hover:text-gray-900 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-primary"></i> Filter
                        </h3>
                        <a href="{{ $resetUrl }}" class="text-sm text-danger font-medium">Reset</a>
                    </div>
                </div>

                <!-- ===== Body (scrollable di mobile, natural di desktop) ===== -->
                <div class="flex-1 overflow-y-auto lg:overflow-visible p-6 space-y-6">

                    <!-- Desktop Header -->
                    <div class="hidden lg:flex items-center justify-between pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="filter" class="w-5 h-5 text-primary"></i> Filter
                        </h3>
                        <a href="{{ $resetUrl }}" class="text-sm text-danger hover:underline">Reset</a>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Kategori</h4>
                        <div class="flex flex-col space-y-3 text-sm">
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-3 min-w-0 group cursor-pointer">
                                <input type="radio" name="category" value="{{ $cat->id }}"
                                       class="w-4 h-4 flex-shrink-0 text-primary focus:ring-primary border-gray-300"
                                       {{ request('category') == $cat->id ? 'checked' : '' }}
                                       onchange="if(window.innerWidth>=1024)this.form.submit()">
                                <span class="text-gray-600 group-hover:text-primary transition leading-snug truncate">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Harga -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Harga</h4>
                        <div class="flex items-center gap-1">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs font-medium">Rp</span>
                                </div>
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                                       style="padding-left: 32px; padding-right: 8px;"
                                       class="block w-full py-2 border border-border-color rounded-xl text-xs focus:ring-primary focus:border-primary transition">
                            </div>
                            <span class="text-gray-400 text-sm">-</span>
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs font-medium">Rp</span>
                                </div>
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                                       style="padding-left: 32px; padding-right: 8px;"
                                       class="block w-full py-2 border border-border-color rounded-xl text-xs focus:ring-primary focus:border-primary transition">
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Kondisi -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Kondisi</h4>
                        <div class="flex flex-col space-y-3 text-sm">
                            @foreach($conditions as $cond)
                            <label class="flex items-center gap-3 min-w-0 group cursor-pointer">
                                <input type="radio" name="condition" value="{{ $cond }}"
                                       class="w-4 h-4 flex-shrink-0 text-primary focus:ring-primary border-gray-300"
                                       {{ request('condition') == $cond ? 'checked' : '' }}
                                       onchange="if(window.innerWidth>=1024)this.form.submit()">
                                <span class="text-gray-600 group-hover:text-primary transition leading-snug truncate">{{ $cond }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    <!-- Lokasi -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Lokasi</h4>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" name="location" placeholder="Masukkan Kota" value="{{ request('location') }}"
                                   class="block w-full pl-9 pr-3 py-2 border border-border-color rounded-xl text-sm focus:ring-primary focus:border-primary transition">
                        </div>
                    </div>

                    <!-- Tombol Terapkan: hanya tampil di DESKTOP (di dalam body) -->
                    <button type="submit"
                            class="hidden lg:block w-full bg-primary text-white py-3 rounded-xl font-semibold shadow-md shadow-blue-500/20 hover:bg-blue-700 transition">
                        Terapkan Filter
                    </button>
                </div>

                <!-- ===== Mobile Footer sticky (Reset + Terapkan) ===== -->
                <div class="lg:hidden flex-shrink-0 flex items-center gap-3 p-4 border-t border-gray-100 bg-white rounded-b-none">
                    <a href="{{ $resetUrl }}"
                       class="flex-1 text-center py-3 rounded-xl font-semibold border border-border-color text-gray-700 hover:bg-gray-50 transition">
                        Reset
                    </a>
                    <button type="submit"
                            class="flex-1 bg-primary text-white py-3 rounded-xl font-semibold shadow-md shadow-blue-500/20 hover:bg-blue-700 transition">
                        Terapkan
                    </button>
                </div>

            </form>
        </aside>

        <!-- ================= PRODUCT GRID ================= -->
        <div class="flex-1 w-full min-w-0">

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">Eksplorasi Barang Bekas</h1>
                    <p class="text-sm text-gray-500">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
                </div>

                <div class="flex items-center justify-between w-full sm:justify-start sm:w-auto gap-3">

                    {{-- Tombol buka popup filter: hanya MOBILE --}}
                    <button type="button" onclick="openFilter()"
                            class="lg:hidden relative flex items-center gap-2 px-4 py-2.5 border border-border-color rounded-xl text-sm font-medium text-gray-700 bg-white hover:border-primary hover:text-primary transition flex-shrink-0">
                        <i data-lucide="filter" class="w-4 h-4 text-primary"></i>
                        Filter
                        @if($activeFilters > 0)
                            <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 bg-primary text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $activeFilters }}</span>
                        @endif
                    </button>

                    <form action="{{ route('marketplace') }}" method="GET" class="sm:w-auto" id="sortForm">
                        @foreach(request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'terbaru') }}">
                        
                        <!-- Mobile Sort Icon -->
                        <div class="relative sm:hidden flex items-center justify-center border border-border-color rounded-xl text-gray-700 bg-white hover:text-primary hover:border-primary transition" style="width: 42px; height: 42px;">
                            <i data-lucide="arrow-up-down" class="w-4 h-4 pointer-events-none text-primary"></i>
                            <select onchange="document.getElementById('sortInput').value = this.value; document.getElementById('sortForm').submit();" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </div>

                        <!-- Desktop Sort Select -->
                        <div class="relative hidden sm:block w-48">
                            <select onchange="document.getElementById('sortInput').value = this.value; document.getElementById('sortForm').submit();"
                                    class="block w-full pl-4 pr-10 py-2.5 border border-border-color rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none bg-white font-medium cursor-pointer transition">
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
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 min-[1920px]:grid-cols-6 gap-4 sm:gap-6">
                    @foreach($products as $item)
                    <div class="bg-white border border-border-color rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-500/20 hover:border-primary transition duration-300 group flex flex-col relative">
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
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center justify-center p-10 sm:p-16 text-center">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-primary mb-4">
                        <i data-lucide="search-x" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">Maaf, kami tidak menemukan produk yang sesuai dengan filter Anda. Coba ubah kata kunci atau pengaturan filter.</p>
                    <a href="{{ $resetUrl }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Hapus Semua Filter
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- ===== Script popup filter (mobile) ===== --}}
<script>
    function openFilter() {
        const panel = document.getElementById('filterPanel');
        const overlay = document.getElementById('filterOverlay');
        panel.classList.remove('translate-y-full');
        panel.classList.add('translate-y-0');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // kunci scroll body
    }
    function closeFilter() {
        const panel = document.getElementById('filterPanel');
        const overlay = document.getElementById('filterOverlay');
        panel.classList.add('translate-y-full');
        panel.classList.remove('translate-y-0');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
    // tutup dengan tombol ESC
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeFilter(); });
</script>
@endsection