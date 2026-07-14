<nav class="bg-white sticky top-0 z-50 border-b border-border-color shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-2xl font-bold text-primary tracking-tight">
                    Cuanin<span class="text-secondary">.</span>
                </a>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-lg mx-8">
                <form action="{{ route('marketplace') }}" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-10 pr-3 py-2.5 border border-border-color rounded-full leading-5 bg-background placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary sm:text-sm transition duration-150 ease-in-out" placeholder="Cari barang bekas incaranmu...">
                </form>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ Auth::check() && Auth::user()->role == 'penjual' ? route('seller.dashboard') : route('profile.index') }}" class="text-gray-600 hover:text-primary transition font-medium flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                    Mulai Jualan
                </a>
                
                <div class="h-6 w-px bg-gray-200"></div>

                <!-- Auth / User Menu -->
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary transition font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/30">Daftar</a>
                @else
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-danger transition">
                        <i data-lucide="heart" class="h-6 w-6"></i>
                    </a>
                    
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary transition">
                        <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                        @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp
                        @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center min-w-[1.125rem] px-1 py-0.5 text-[10px] font-bold leading-none text-white bg-danger rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <!-- Profile Dropdown (Hover) -->
                    <div class="relative group cursor-pointer ml-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-full border-2 border-transparent group-hover:border-primary transition overflow-hidden">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="px-4 py-3 border-b border-gray-100 mb-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="user" class="w-4 h-4 mr-3"></i> Profil Saya</a>
                            
                            <a href="{{ route('order.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="package" class="w-4 h-4 mr-3"></i> Pesanan Saya</a>
                            
                            <a href="{{ route('negotiations.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="handshake" class="w-4 h-4 mr-3"></i> Nego Harga</a>
                            
                            @if(Auth::user()->role === 'penjual')
                            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="layout-dashboard" class="w-4 h-4 mr-3"></i> Dashboard Penjual</a>
                            @endif
                            @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition"><i data-lucide="shield" class="w-4 h-4 mr-3"></i> Dashboard Admin</a>
                            @endif
                            <div class="h-px bg-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-danger hover:bg-red-50 transition"><i data-lucide="log-out" class="w-4 h-4 mr-3"></i> Keluar</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
