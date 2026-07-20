<footer class="bg-white border-t border-border-color mt-16 pt-12 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- 3 kolom aktif -> grid-cols-3 biar terisi penuh (tidak nempel kiri) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

            {{-- Kolom 1: Brand --}}
            <div>
                <a href="/" class="text-2xl font-bold text-primary tracking-tight mb-4 inline-block">
                    Cuanin<span class="text-secondary">.</span>
                </a>
                <p class="text-gray-500 text-sm leading-relaxed md:max-w-xs">
                    Marketplace barang bekas berkualitas. Temukan barang impianmu dengan harga terbaik dan aman.
                </p>
            </div>

            {{-- Kolom 2: Layanan --}}
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Layanan Cuanin</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ Auth::check() && Auth::user()->role == 'penjual' ? route('seller.dashboard') : route('profile.index') }}" class="hover:text-primary transition">Mulai Jualan</a></li>
                    <li><a href="cara-jualan" class="hover:text-primary transition">Cara Jualan</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="hover:text-primary transition">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-primary transition">Daftar</a></li>
                    @endguest
                </ul>
            </div>

            {{-- Kolom 3: Download --}}
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Download Aplikasi</h4>
                <p class="text-sm text-gray-500 mb-4 md:max-w-xs">Coming Soon Guys 🙏</p>
                <div class="flex flex-col space-y-2 md:max-w-xs">
                    <a href="javascript:void(0)" class="bg-dark text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-800 transition">
                        <i data-lucide="smartphone" class="h-5 w-5"></i>
                        <span>Google Play</span>
                    </a>
                    <a href="javascript:void(0)" class="bg-dark text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-800 transition">
                        <i data-lucide="apple" class="h-5 w-5"></i>
                        <span>App Store</span>
                    </a>
                </div>
            </div>

        </div>

        {{-- Baris copyright --}}
        <div class="border-t border-gray-100 mt-12 pt-8 text-center md:flex md:justify-between items-center">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} Cuanin. All rights reserved.
            </p>
            <div class="mt-4 md:mt-0 flex justify-center space-x-6 text-sm text-gray-400">
                <span>I ❤️ Indonesia</span>
            </div>
        </div>
    </div>
</footer>