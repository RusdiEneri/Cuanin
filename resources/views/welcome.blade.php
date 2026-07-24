@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">

    {{-- ================= HERO ================= --}}
    <div id="heroCard" class="bg-primary rounded-3xl overflow-hidden relative mb-12 md:mb-16 shadow-2xl shadow-blue-900/30">
        {{-- Base Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-primary to-blue-950"></div>
        
        {{-- Glowing Orbs (Aurora Mesh Effect) - Mengisi ruang kosong dengan warna dinamis --}}
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[70%] bg-blue-400/30 rounded-full mix-blend-overlay filter blur-[100px] animate-[float_8s_ease-in-out_infinite]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[80%] bg-purple-500/20 rounded-full mix-blend-overlay filter blur-[120px] animate-[float_6s_ease-in-out_infinite_reverse]"></div>
            <div class="absolute top-[20%] left-[30%] w-[40%] h-[40%] bg-yellow-400/15 rounded-full mix-blend-overlay filter blur-[90px] animate-[float_10s_ease-in-out_infinite]"></div>
        </div>

        
        
        {{-- Glass Reflection Overlay (Efek kilapan kaca di ujung) --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent pointer-events-none"></div>

        {{-- Hiasan Corak (Arabesque) — DIAM di tempatnya, hanya sebagai hiasan samar --}}
        <div class="absolute inset-0 pointer-events-none rounded-r-3xl opacity-[0.18]"
            style="background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
                    background-repeat: repeat;
                    -webkit-mask-image: linear-gradient(to right, transparent 30%, black 70%);
                    mask-image: linear-gradient(to right, transparent 30%, black 70%);">
        </div>

        {{-- Pattern interaktif: TIDAK bergeser. Hanya MASK-nya yang mengikuti kursor,
            sehingga pola "agak keliatan" di sekitar kursor (seamless dgn hiasan di atas) --}}
        <div id="heroPatternCursor"
            class="absolute inset-0 pointer-events-none opacity-0"
            style="background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
                    background-repeat: repeat;
                    transition: opacity .35s ease;
                    -webkit-mask-image: radial-gradient(240px circle at var(--mx, -1000px) var(--my, -1000px), rgba(0,0,0,.60) 0%, rgba(0,0,0,.25) 42%, transparent 72%);
                    mask-image: radial-gradient(240px circle at var(--mx, -1000px) var(--my, -1000px), rgba(0,0,0,.60) 0%, rgba(0,0,0,.25) 42%, transparent 72%);">
        </div>

        
        <div class="relative z-10 px-6 sm:px-8 py-10 md:py-12 lg:py-12 md:px-16 flex flex-col md:flex-row items-center justify-between gap-8 md:gap-0">
            <div class="w-full md:w-1/2 text-center md:text-left text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-3 md:mb-4 animate-[fadeInUp_0.8s_ease-out_forwards]">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                    </span>
                    <span class="text-xs font-medium text-blue-50">Pusat Barang Bekas Pilihan #1</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl font-bold leading-[1.1] mb-3 md:mb-4 tracking-tight text-white animate-[fadeInUp_0.8s_ease-out_0.2s_forwards] opacity-0 drop-shadow-lg">
                    Temukan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500 drop-shadow-none">Barang Bekas</span> <br>
                    Berkualitas!
                </h1>
                
                <p class="text-base sm:text-lg md:text-lg lg:text-xl text-blue-100 mb-6 max-w-lg mx-auto md:mx-0 leading-relaxed animate-[fadeInUp_0.8s_ease-out_0.4s_forwards] opacity-0 drop-shadow-md">
                    Marketplace terpercaya untuk jual beli barang preloved. Aman, mudah, dan penuh cuan.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto animate-[fadeInUp_0.8s_ease-out_0.6s_forwards] opacity-0">
                    <a href="{{ route('marketplace') }}" class="group relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-blue-950 font-bold px-8 py-3.5 rounded-full overflow-hidden transition-all hover:scale-105 shadow-[0_0_20px_rgba(250,204,21,0.3)] hover:shadow-[0_0_25px_rgba(250,204,21,0.5)]">
                        <span class="relative z-10 flex items-center gap-2">
                            Mulai Belanja
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                    </a>
                    <a href="{{ route('cara-jualan') }}" class="bg-white/10 backdrop-blur-md text-white border border-white/20 font-semibold px-8 py-3.5 rounded-full text-center hover:bg-white/20 transition hover:scale-105 flex items-center justify-center gap-2">
                        Cara Jualan
                    </a>
                </div>
            </div>

            <div class="hidden md:flex md:w-1/2 items-center justify-center relative">
                <div class="relative w-72 md:w-80 lg:w-96 h-auto origin-center">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-secondary rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
                    <div class="absolute top-0 left-10 w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
                    <div class="absolute -bottom-8 left-20 w-48 h-48 bg-purple-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-4000"></div>

                    @php
                        $heroProducts = isset($latestProducts) ? $latestProducts->take(3) : collect();
                    @endphp

                    @if($heroProducts->isNotEmpty())
                        <div id="hero-slider" class="relative w-full">
                            @foreach($heroProducts as $index => $product)
                                @php
                                    $imgUrl = $product->primaryImage 
                                        ? (str_starts_with($product->primaryImage->image_path, 'http') ? $product->primaryImage->image_path : asset('storage/' . $product->primaryImage->image_path))
                                        : asset('images/placeholder.jpg');
                                    
                                    $icon = 'package';
                                    if(isset($product->category) && $product->category->icon) {
                                        $icon = $product->category->icon;
                                    }
                                @endphp
                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" 
                                   class="hero-slide block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-2xl transition-all duration-1000 ease-in-out {{ $index === 0 ? 'relative opacity-100 z-10 rotate-3 hover:rotate-0' : 'absolute inset-0 opacity-0 z-0 pointer-events-none' }}">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white shrink-0 shadow-inner">
                                            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h3 class="text-white font-bold truncate" title="{{ $product->title }}">{{ $product->title }}</h3>
                                            <p class="text-blue-200 text-sm truncate">{{ $product->category->name ?? 'Lainnya' }} • {{ $product->condition }}</p>
                                        </div>
                                    </div>
                                    <div class="w-full h-44 bg-white/5 border border-white/10 rounded-xl mb-4 overflow-hidden relative group">
                                        <img src="{{ $imgUrl }}" alt="{{ $product->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-out">
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-yellow-400 font-extrabold text-xl truncate pr-2">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-blue-950 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shrink-0 shadow-lg hover:scale-105 transition-transform">Beli</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white shrink-0 shadow-inner">
                                    <i data-lucide="smartphone" class="w-6 h-6"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="text-white font-bold truncate">iPhone 13 Pro</h3>
                                    <p class="text-blue-200 text-sm truncate">Elektronik • Kondisi Bekas</p>
                                </div>
                            </div>
                            <div class="w-full h-44 bg-white/5 border border-white/10 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="https://jakartaberkamera.com/wp-content/uploads/2022/08/sewa-rental-iphone13-pro-jakarta-1.jpg" alt="iPhone 13 Pro" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-out">
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-400 font-extrabold text-xl truncate pr-2">Rp 10.500.000</span>
                                <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-blue-950 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shrink-0 shadow-lg hover:scale-105 transition-transform">Beli</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================= KATEGORI (SWIPE DI MOBILE) ================= --}}
    <div class="mb-12 md:mb-16">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Kategori Pilihan</h2>
                <p class="text-sm sm:text-base text-gray-500">Temukan barang incaranmu dari berbagai kategori</p>
            </div>
            <a href="{{ route('marketplace') }}" class="text-primary font-medium hover:underline hidden sm:block">Lihat Semua</a>
        </div>

        {{-- Mobile: Horizontal Scroll dengan Loop --}}
        <div class="relative md:hidden">
            <div id="categoryCarousel" class="flex gap-3 overflow-x-auto scrollbar-hide scroll-smooth pb-2 -mx-4 px-4" style="scroll-snap-type: x mandatory;">
                @foreach($categories as $cat)
                <a href="{{ route('marketplace', ['category' => $cat->id]) }}" 
                   class="category-card flex-shrink-0 w-[140px] bg-white border border-border-color rounded-2xl p-4 flex flex-col items-center justify-center gap-3 hover:border-primary hover:shadow-lg hover:shadow-blue-500/10 transition group"
                   style="scroll-snap-align: start;">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-primary group-hover:scale-110 transition">
                        <i data-lucide="{{ $cat->icon ?? 'box' }}" class="w-6 h-6"></i>
                    </div>
                    <span class="font-medium text-xs text-gray-700 text-center group-hover:text-primary transition line-clamp-2">{{ $cat->name }}</span>
                </a>
                @endforeach
            </div>
            
            {{-- Navigation Dots --}}
            <div id="carouselDots" class="flex justify-center gap-2 mt-4"></div>
        </div>

        {{-- Desktop: Grid Normal --}}
        <div class="hidden md:grid grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('marketplace', ['category' => $cat->id]) }}" class="bg-white border border-border-color rounded-2xl p-6 flex flex-col items-center justify-center gap-3 hover:border-primary hover:shadow-lg hover:shadow-blue-500/10 transition group">
                <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center text-primary group-hover:scale-110 transition">
                    <i data-lucide="{{ $cat->icon ?? 'box' }}" class="w-7 h-7"></i>
                </div>
                <span class="font-medium text-base text-gray-700 text-center group-hover:text-primary transition line-clamp-1">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ================= REKOMENDASI ================= --}}
    <div class="mb-12 md:mb-16">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Rekomendasi Terbaru</h2>
                <p class="text-sm sm:text-base text-gray-500">Barang-barang bekas berkualitas yang baru saja diunggah</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 md:gap-6">
            @foreach($latestProducts as $item)
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
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-white/90 backdrop-blur-sm px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold text-gray-700 flex items-center gap-1 shadow-sm">
                        <i data-lucide="{{ $item->condition == 'Barang Baru' ? 'star' : 'check-circle-2' }}" class="w-3 h-3 {{ $item->condition == 'Barang Baru' ? 'text-secondary fill-current' : 'text-success' }}"></i>
                        <span class="line-clamp-1">{{ $item->condition }}</span>
                    </div>
                </a>

                <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-2 right-2 sm:top-3 sm:right-3 z-20">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                    <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full text-gray-400 hover:text-primary hover:bg-blue-50 transition shadow-sm" title="Tambah ke Wishlist">
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

        <div class="text-center mt-8 md:mt-10">
            <a href="{{ route('marketplace') }}" class="inline-flex items-center gap-2 bg-white border border-border-color text-gray-700 px-6 py-2.5 rounded-full font-medium hover:bg-gray-50 hover:text-primary transition shadow-sm">
                Lihat Semua Produk <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hero = document.getElementById('heroCard');
    const cursorPattern = document.getElementById('heroPatternCursor');
    if (!hero || !cursorPattern) return;

    // Aktif hanya di perangkat berkursor (desktop). Mobile = tetap bersih & hemat.
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    let rafId = null, lastX = 0, lastY = 0;

    function update() {
        rafId = null;
        const r = hero.getBoundingClientRect();
        // HANYA gerakkan posisi spotlight (mask), JANGAN sentuh background-position pola.
        hero.style.setProperty('--mx', (lastX - r.left) + 'px');
        hero.style.setProperty('--my', (lastY - r.top)  + 'px');
    }

    hero.addEventListener('mousemove', function (e) {
        lastX = e.clientX; lastY = e.clientY;
        cursorPattern.style.opacity = '1';          // pola "agak keliatan" di sekitar kursor
        if (rafId === null) rafId = requestAnimationFrame(update);
    });

    hero.addEventListener('mouseleave', function () {
        cursorPattern.style.opacity = '0';          // fade out spotlight
        hero.style.setProperty('--mx', '-1000px');  // sembunyikan mask
        hero.style.setProperty('--my', '-1000px');
        // TIDAK ada reset --px/--py lagi → pola hiasan tetap diam di tempatnya.
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('categoryCarousel');
    const dotsContainer = document.getElementById('carouselDots');
    
    if (!carousel || !dotsContainer) return;
    
    const cards = carousel.querySelectorAll('.category-card');
    const cardWidth = 140 + 12; // width + gap
    let currentIndex = 0;
    
    // Create dots
    const totalDots = Math.ceil(cards.length / 2); // Show 2 cards per view on mobile
    for (let i = 0; i < totalDots; i++) {
        const dot = document.createElement('button');
        dot.className = 'w-2 h-2 rounded-full bg-gray-300 transition-all duration-300';
        dot.addEventListener('click', () => scrollToIndex(i));
        dotsContainer.appendChild(dot);
    }
    
    const dots = dotsContainer.querySelectorAll('button');
    
    function updateDots() {
        const scrollLeft = carousel.scrollLeft;
        const newIndex = Math.round(scrollLeft / (cardWidth * 2));
        
        if (newIndex !== currentIndex) {
            currentIndex = newIndex;
            dots.forEach((dot, i) => {
                dot.className = i === currentIndex 
                    ? 'w-6 h-2 rounded-full bg-primary transition-all duration-300' 
                    : 'w-2 h-2 rounded-full bg-gray-300 transition-all duration-300';
            });
        }
    }
    
    function scrollToIndex(index) {
        carousel.scrollTo({
            left: index * cardWidth * 2,
            behavior: 'smooth'
        });
    }
    
    carousel.addEventListener('scroll', updateDots);
    updateDots(); // Initial state
    
    // Touch swipe enhancement
    let isDown = false;
    let startX;
    let scrollLeft;
    
    carousel.addEventListener('touchstart', (e) => {
        isDown = true;
        startX = e.touches[0].pageX - carousel.offsetLeft;
        scrollLeft = carousel.scrollLeft;
    });
    
    carousel.addEventListener('touchend', () => {
        isDown = false;
    });
    
    carousel.addEventListener('touchmove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.touches[0].pageX - carousel.offsetLeft;
        const walk = (x - startX) * 1.5;
        carousel.scrollLeft = scrollLeft - walk;
    });
});
</script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.hero-slide');
        if (slides.length <= 1) return;
        
        let currentIndex = 0;
        
        setInterval(() => {
            // Hide current
            slides[currentIndex].classList.remove('opacity-100', 'z-10', 'rotate-3', 'hover:rotate-0');
            slides[currentIndex].classList.add('opacity-0', 'z-0', 'pointer-events-none');
            
            // Move to next
            currentIndex = (currentIndex + 1) % slides.length;
            
            // Show next
            slides[currentIndex].classList.remove('opacity-0', 'z-0', 'pointer-events-none');
            slides[currentIndex].classList.add('opacity-100', 'z-10', 'rotate-3', 'hover:rotate-0');
        }, 4000);
    });
</script>
@endpush

@endsection