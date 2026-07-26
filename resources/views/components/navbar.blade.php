<nav class="bg-white sticky top-0 z-50 border-b border-border-color shadow-sm">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- BARIS 1: Header utama -->
        <div class="flex justify-between items-center h-16 md:h-[72px] gap-4 md:gap-6">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="flex items-center" aria-label="Cuanin - Beranda">
                    <img src="{{ asset('logo.png') }}?v2"
                         alt="Cuanin"
                         draggable="false"
                         class="h-8 md:h-9 w-auto max-w-[120px] md:max-w-[140px] object-contain select-none translate-y-0.5 md:translate-y-1">
                </a>
            </div>

            <!-- Search Bar (DESKTOP ONLY) -->
            @if(request()->routeIs('home', 'marketplace'))
            <div class="hidden md:flex flex-1 max-w-2xl">
                <form action="{{ route('marketplace') }}" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5 text-gray-500"></i>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-full leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 sm:text-sm transition duration-150 ease-in-out hover:border-gray-400 shadow-sm" placeholder="Cari barang bekas incaranmu...">
                </form>
            </div>
            @endif

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <a href="{{ Auth::check() && Auth::user()->role == 'penjual' ? route('seller.dashboard') : route('profile.index') }}" class="hidden lg:flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-primary hover:bg-gray-50 rounded-lg transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span>Mulai Jualan</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary transition font-medium px-3 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/30">Daftar</a>
                @else
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-danger transition p-2 hover:bg-gray-50 rounded-lg" title="Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary transition p-2 hover:bg-gray-50 rounded-lg" title="Keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp
                        @if($cartCount > 0)
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold leading-none text-white bg-danger rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <!-- Profile Dropdown -->
                    <div id="profileDropdown" class="relative cursor-pointer">
                        <button type="button" id="profileBtn" class="w-10 h-10 bg-blue-100 rounded-full border-2 border-transparent hover:border-primary transition overflow-hidden focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            @endif
                        </button>
                        <div id="profileMenu" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 opacity-0 invisible translate-y-2 transition-all duration-200 z-[60]">
                            <div class="px-4 py-3 border-b border-gray-100 mb-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="user" class="w-4 h-4 mr-3"></i> Profil Saya</a>
                            {{-- ✅ Link "Pesanan Saya" (order.index) DIHAPUS — fitur order sudah tidak dipakai --}}
                            <a href="{{ route('negotiations.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="handshake" class="w-4 h-4 mr-3"></i> Nego Harga</a>
                            @if(Auth::user()->role === 'penjual')
                            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="layout-dashboard" class="w-4 h-4 mr-3"></i> Dashboard Penjual</a>
                            @endif
                            @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="shield" class="w-4 h-4 mr-3"></i> Dashboard Admin</a>
                            <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="check-square" class="w-4 h-4 mr-3"></i> Verifikasi Produk</a>
                            @endif
                            <div class="h-px bg-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-danger hover:bg-red-50 transition"><i data-lucide="log-out" class="w-4 h-4 mr-3"></i> Keluar</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- MOBILE HEADER dengan Quick Actions -->
            <div class="md:hidden flex items-center gap-0.5">
                @auth
                    @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-danger transition p-2 -mr-1">
                        <i data-lucide="heart" class="h-5 w-5"></i>
                    </a>

                    <!-- Cart with badge -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary transition p-2">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        @if($cartCount > 0)
                        <span class="absolute top-0.5 right-0.5 inline-flex items-center justify-center min-w-[1rem] h-4 px-1 text-[9px] font-bold leading-none text-white bg-danger rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <!-- Avatar -->
                    <a href="{{ route('profile.index') }}" class="w-9 h-9 bg-blue-100 rounded-full overflow-hidden border-2 border-transparent hover:border-primary transition flex-shrink-0 mx-1">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-primary font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                    </a>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-primary transition px-2 py-1.5 font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="text-xs bg-primary text-white px-3 py-1.5 rounded-full font-medium hover:bg-blue-700 transition whitespace-nowrap">Daftar</a>
                @endguest

                <!-- Hamburger Button -->
                <button type="button" id="mobileMenuBtn" class="text-gray-500 hover:text-gray-600 focus:outline-none p-2 relative w-10 h-10 flex items-center justify-center">
                    <span id="iconOpen" class="flex items-center justify-center">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </span>
                    <span id="iconClose" class="hidden absolute inset-0 flex items-center justify-center">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </span>
                </button>
            </div>
        </div>

        <!-- BARIS 2: Search Bar (MOBILE ONLY) -->
        @if(request()->routeIs('home', 'marketplace'))
            <div class="md:hidden pb-3">
                <form action="{{ route('marketplace') }}" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-10 pr-3 py-2.5 border border-border-color leading-5 rounded-full bg-background placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary text-sm" placeholder="Cari barang bekas incaranmu...">
                </form>
            </div>
        @endif
    </div> {{-- ✅ penutup container max-w-[1600px] dipindah ke SINI (di luar @unless) supaya selalu tertutup --}}

    <!-- MOBILE MENU (toggle) -->
    <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-4 space-y-3">
            <!-- Mulai Jualan -->
            <a href="{{ Auth::check() && Auth::user()->role == 'penjual' ? route('seller.dashboard') : route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:text-primary hover:bg-gray-50 rounded-lg transition font-medium mobile-link">
                <i data-lucide="shopping-bag" class="h-5 w-5 flex-shrink-0"></i>
                <span>Mulai Jualan</span>
            </a>

            @guest
                {{-- opsional: tombol Masuk/Daftar di menu --}}
            @else
                <!-- Menu Links -->
                <div class="pt-3 border-t border-gray-100 space-y-1">
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary rounded-lg transition mobile-link">
                        <i data-lucide="user" class="w-5 h-5 flex-shrink-0"></i>
                        <span>Profil Saya</span>
                    </a>
                    {{-- ✅ Link "Pesanan Saya" (order.index) DIHAPUS — fitur order sudah tidak dipakai --}}
                    <a href="{{ route('negotiations.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary rounded-lg transition mobile-link">
                        <i data-lucide="handshake" class="w-5 h-5 flex-shrink-0"></i>
                        <span>Nego Harga</span>
                    </a>

                    @if(Auth::user()->role === 'penjual')
                    <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary rounded-lg transition mobile-link">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                        <span>Dashboard Penjual</span>
                    </a>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary rounded-lg transition mobile-link">
                        <i data-lucide="shield" class="w-5 h-5 flex-shrink-0"></i>
                        <span>Dashboard Admin</span>
                    </a>
                    @endif

                    <div class="h-px bg-gray-100 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-danger hover:bg-red-50 rounded-lg transition mobile-link">
                            <i data-lucide="log-out" class="w-5 h-5 flex-shrink-0"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const iconOpen = document.getElementById('iconOpen');
        const iconClose = document.getElementById('iconClose');

        if (!mobileMenuBtn || !mobileMenu) return;

        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });

        const mobileLinks = mobileMenu.querySelectorAll('.mobile-link');
        mobileLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });
    });

    // Desktop Profile Dropdown
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownContainer = document.getElementById('profileDropdown');
        const btn = document.getElementById('profileBtn');
        const menu = document.getElementById('profileMenu');

        if (!dropdownContainer || !btn || !menu) return;

        let hideTimeout;
        let isHovering = false;

        function showMenu() {
            clearTimeout(hideTimeout);
            menu.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            menu.classList.add('opacity-100', 'visible', 'translate-y-0');
        }

        function hideMenu() {
            hideTimeout = setTimeout(() => {
                if (!isHovering && !dropdownContainer.contains(document.activeElement)) {
                    menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                }
            }, 200);
        }

        dropdownContainer.addEventListener('mouseenter', function () {
            isHovering = true;
            showMenu();
        });

        dropdownContainer.addEventListener('mouseleave', function () {
            isHovering = false;
            hideMenu();
        });

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (menu.classList.contains('invisible')) {
                showMenu();
            } else {
                menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            }
        });

        document.addEventListener('click', function (e) {
            if (!dropdownContainer.contains(e.target)) {
                menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            }
        });
    });
</script>