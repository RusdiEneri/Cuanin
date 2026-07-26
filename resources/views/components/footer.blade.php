<footer class="bg-white border-t border-border-color mt-12 pt-8 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4 xl:gap-6">

            {{-- Kolom 1: Brand (LOGO GAMBAR) --}}
            <div class="max-w-sm pr-2">
                {{-- FIX: mb-3 -> mb-1.
                     Logo h-8 = 32px, sedangkan <h4> kolom lain ≈ 24px (selisih 8px).
                     12px (mb-3) - 8px = 4px (mb-1) supaya paragraf sejajar dgn list. --}}
                <a href="/" class="mb-1 inline-block">
                    <img src="{{ asset('logo.png') }}"
                         alt="Cuanin"
                         class="h-8 w-auto object-contain block">
                </a>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Marketplace barang bekas berkualitas. Temukan barang impianmu dengan harga terbaik dan aman.
                </p>
            </div>

            {{-- Kolom 2: Bantuan --}}
            <div class="flex flex-col items-start pr-2">
                <h4 class="font-semibold text-gray-900 mb-3">Bantuan</h4>
                {{-- FIX: tambah leading-relaxed agar baseline baris pertama impas dgn <p> kolom 1 --}}
                <ul class="space-y-1.5 text-sm text-gray-500 leading-relaxed">
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Pusat Bantuan</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">FAQ</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Hubungi Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kategori --}}
            <div class="flex flex-col items-start pr-2">
                <h4 class="font-semibold text-gray-900 mb-3">Kategori</h4>
                <ul class="space-y-1.5 text-sm text-gray-500 leading-relaxed">
                    <li><a href="{{ route('marketplace', ['category' => 1]) }}" class="hover:text-primary transition">Elektronik</a></li>
                    <li><a href="{{ route('marketplace', ['category' => 2]) }}" class="hover:text-primary transition">Pakaian</a></li>
                    <li><a href="{{ route('marketplace', ['category' => 3]) }}" class="hover:text-primary transition">Kendaraan</a></li>
                    <li><a href="{{ route('marketplace', ['category' => 4]) }}" class="hover:text-primary transition">Furnitur</a></li>
                    <li><a href="{{ route('marketplace', ['category' => 5]) }}" class="hover:text-primary transition">Hobi & Mainan</a></li>
                    <li><a href="{{ route('marketplace', ['category' => 6]) }}" class="hover:text-primary transition">Buku</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Kontak --}}
            <div class="flex flex-col items-start pr-2">
                <h4 class="font-semibold text-gray-900 mb-3">Kontak</h4>
                <ul class="space-y-1.5 text-sm text-gray-500 leading-relaxed">
                    <li><a href="mailto:support@cuanin.id" class="hover:text-primary transition">support@cuanin.id</a></li>
                    <li><span>Gresik, Jawa Timur, Indonesia</span></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">TikTok: Cuanin.id</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Instagram: Cuanin.id</a></li>
                </ul>
            </div>

        </div>

        {{-- Baris copyright --}}
        <div class="border-t border-gray-100 mt-6 pt-4 text-left">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} Cuanin. All rights reserved.
            </p>
        </div>
    </div>
</footer>