<footer class="bg-white border-t border-border-color mt-12 pt-8 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4 xl:gap-6">

            {{-- Kolom 1: Brand --}}
            <div class="max-w-sm pr-2">
                <a href="/" class="text-2xl font-bold text-primary tracking-tight mb-3 inline-block">
                    Cuanin<span class="text-secondary">.</span>
                </a>
                <p class="text-gray-500 text-sm leading-relaxed mt-1">
                    Marketplace barang bekas berkualitas. Temukan barang impianmu dengan harga terbaik dan aman.
                </p>
            </div>

            {{-- Kolom 2: Bantuan --}}
            <div class="flex flex-col items-start pr-2">
                <h4 class="font-semibold text-gray-900 mb-3">Bantuan</h4>
                <ul class="space-y-1.5 text-sm text-gray-500">
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Pusat Bantuan</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">FAQ</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Hubungi Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kategori --}}
            <div class="flex flex-col items-start pr-2">
                <h4 class="font-semibold text-gray-900 mb-3">Kategori</h4>
                <ul class="space-y-1.5 text-sm text-gray-500">
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Elektronik</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Pakaian</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Kendaraan</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Furniture</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Hobi & Mainan</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-primary transition">Buku</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Kontak --}}
            <div class="flex flex-col items-start pr-2">
                <h4 class="font-semibold text-gray-900 mb-3">Kontak</h4>
                <ul class="space-y-1.5 text-sm text-gray-500">
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