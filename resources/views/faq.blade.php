@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-16">
    <!-- Hero Section -->
    <div class="bg-primary text-white py-16 relative overflow-hidden shadow-xl shadow-blue-900/10">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-primary"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Pertanyaan yang Sering Diajukan</h1>
            <p class="text-blue-100 text-lg md:text-xl max-w-3xl mx-auto">
                Temukan jawaban dari pertanyaan yang paling sering ditanyakan oleh pengguna Cuanin.
            </p>
        </div>
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12 max-w-4xl">
        
        <!-- Umum -->
        <div id="akun" class="mb-10 scroll-mt-24">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-sm">
                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                </span>
                Umum
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Apa itu Cuanin?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Cuanin adalah marketplace barang bekas berkualitas yang menghubungkan penjual dan pembeli. Kami memudahkan Anda untuk menjual barang yang tidak terpakai atau menemukan barang bekas berkualitas dengan harga terjangkau.
                    </div>
                </details>
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Apakah Cuanin gratis digunakan?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Ya! Mendaftar dan menggunakan Cuanin sepenuhnya gratis, baik sebagai pembeli maupun penjual. Tidak ada biaya tersembunyi untuk bergabung.
                    </div>
                </details>
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Bagaimana cara mendaftar akun?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Klik tombol <strong>"Daftar"</strong> di pojok kanan atas halaman, isi formulir pendaftaran dengan data diri Anda (nama, email, kata sandi), lalu klik <strong>"Daftar"</strong>. Akun Anda langsung aktif dan siap digunakan!
                    </div>
                </details>
            </div>
        </div>

        <!-- Mencari Barang -->
        <div id="pembelian" class="mb-10 scroll-mt-24">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-sm">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                Mencari & Menawar Barang
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Bagaimana cara membeli barang di Cuanin?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Cuanin berfungsi sebagai platform pencarian barang bekas. Anda cukup mencari produk yang diinginkan, lalu hubungi penjual melalui WhatsApp (dengan menekan tombol <strong>Hubungi Penjual via WA</strong>) untuk bernegosiasi dan menyepakati cara transaksi (misalnya COD).
                    </div>
                </details>
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Apakah ada fitur checkout atau keranjang?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Tidak ada. Untuk menjaga kesederhanaan dan memfasilitasi transaksi tatap muka, Cuanin tidak menahan dana, tidak memiliki sistem keranjang belanja, dan tidak mengurus pengiriman paket. Segala transaksi dilakukan secara pribadi antara pembeli dan penjual.
                    </div>
                </details>
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Bagaimana sistem pembayarannya?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Pembayaran disepakati langsung antara Anda dan penjual. Kami sangat menyarankan sistem <strong>COD (Cash on Delivery) / Ketemuan</strong> untuk memastikan Anda bisa memeriksa kondisi barang secara langsung sebelum membayar.
                    </div>
                </details>
            </div>
        </div>

        <!-- Biaya & Fitur Platform -->
        <div id="biaya-fitur" class="mb-10 scroll-mt-24">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-sm">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </span>
                Biaya & Fitur Platform
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Apakah upload produk berbayar?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Ya. Untuk menjaga kualitas barang yang diupload, setiap penjual yang ingin melakukan <strong>upload produk akan dikenakan biaya sebesar Rp5.000</strong> per produk. Setelah dibayar, produk Anda akan tayang dan dapat dicari oleh seluruh pengguna.
                    </div>
                </details>
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Bagaimana cara kerja fitur negosiasi (nego)?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Website kami dilengkapi dengan <strong>Fitur Nego Harga</strong>. Pembeli dapat mengajukan penawaran harga yang lebih rendah melalui sistem kami. Jika penjual menyetujui harga tersebut, kedua belah pihak dapat langsung melanjutkan kesepakatan dan lokasi pertemuan (COD) melalui <strong>WhatsApp</strong>.
                    </div>
                </details>
            </div>
        </div>

        <!-- Keamanan -->
        <div id="keamanan" class="mb-10 scroll-mt-24">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-sm">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                </span>
                Keamanan
            </h2>
            <div class="space-y-3">
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Apakah data saya aman?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Ya, kami menjaga keamanan data pribadi Anda dengan serius. Kata sandi Anda dienkripsi dan kami tidak pernah membagikan informasi pribadi Anda kepada pihak ketiga tanpa izin.
                    </div>
                </details>
                <details class="bg-white rounded-2xl border border-gray-100 shadow-sm group">
                    <summary class="cursor-pointer px-6 py-5 font-semibold text-gray-900 flex justify-between items-center hover:text-primary transition">
                        Bagaimana cara menghindari penipuan?
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4">
                        Selalu bertransaksi melalui platform Cuanin, periksa profil dan reputasi penjual sebelum membeli, dan jangan pernah memberikan informasi pribadi sensitif di luar platform kami.
                    </div>
                </details>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 md:p-12 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Pertanyaan Anda Belum Terjawab?</h3>
            <p class="text-gray-500 mb-6 max-w-xl mx-auto">Jangan ragu untuk menghubungi kami. Tim support Cuanin siap membantu Anda.</p>
            <a href="{{ route('hubungi') }}" class="inline-block bg-primary text-white font-bold px-8 py-3 rounded-full hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
@endsection
