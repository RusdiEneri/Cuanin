@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-16">
    <!-- Hero Section -->
    <div class="bg-primary text-white py-20 relative overflow-hidden shadow-xl shadow-blue-900/10">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-primary"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Mulai Hasilkan Cuan dari Barang Bekasmu!</h1>
            <p class="text-blue-100 text-lg md:text-xl max-w-2xl mx-auto mb-10">
                Jual barang preloved yang sudah tidak terpakai dengan mudah, aman, dan cepat di Cuanin. Ubah barang bekas jadi uang tunai sekarang juga!
            </p>
            <a href="{{ Auth::check() ? (Auth::user()->role == 'penjual' ? route('seller.dashboard') : route('profile.index')) : route('login') }}" class="inline-block bg-secondary text-dark font-bold px-8 py-4 rounded-full hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/30 transform hover:-translate-y-1">
                Mulai Jualan Sekarang
            </a>
        </div>
        
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-16 max-w-5xl flex flex-col gap-16">
        <!-- How it Works Section -->
        <div>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">4 Langkah Mudah Berjualan</h2>
                <p class="text-gray-600">Proses jualan di Cuanin dirancang se-simpel mungkin untuk Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition text-center group">
                    <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i data-lucide="user-plus" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-900">1. Daftar Akun</h3>
                    <p class="text-gray-500 text-sm">Buat akun Cuanin secara gratis dan lengkapi profil toko Anda agar pembeli lebih percaya.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition text-center group">
                    <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i data-lucide="camera" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-900">2. Foto & Unggah</h3>
                    <p class="text-gray-500 text-sm">Ambil foto barang dengan pencahayaan baik, lalu unggah dan berikan deskripsi yang jelas serta jujur.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition text-center group">
                    <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i data-lucide="tag" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-900">3. Pasang Harga</h3>
                    <p class="text-gray-500 text-sm">Tentukan harga yang kompetitif. Anda bisa mengaktifkan fitur Nego untuk menarik lebih banyak pembeli.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition text-center group">
                    <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                        <i data-lucide="handshake" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-900">4. Deal & Transaksi</h3>
                    <p class="text-gray-500 text-sm">Terima tawaran, diskusikan pengiriman atau pertemuan (COD), dan dapatkan uang Anda.</p>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 bg-blue-50 p-10 flex flex-col justify-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Tips Agar Barang Cepat Laku</h2>
                    <p class="text-gray-600 mb-6">Ikuti panduan ini untuk memaksimalkan peluang barang Anda terjual dengan cepat dan harga terbaik.</p>
                    <div class="w-20 h-1 bg-primary rounded"></div>
                </div>
                <div class="md:w-2/3 p-10">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex gap-4">
                            <div class="text-primary shrink-0 mt-1">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Foto Asli & Jelas</h4>
                                <p class="text-sm text-gray-500">Gunakan foto asli dari berbagai sisi, hindari mengambil foto dari Google. Tunjukkan juga jika ada minus/cacat.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-primary shrink-0 mt-1">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Deskripsi Lengkap</h4>
                                <p class="text-sm text-gray-500">Tulis ukuran, merek, lama pemakaian, dan alasan dijual. Semakin detail, pembeli semakin yakin.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-primary shrink-0 mt-1">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Harga Wajar</h4>
                                <p class="text-sm text-gray-500">Riset harga barang serupa di pasaran. Berikan harga yang realistis sesuai kondisi barang.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-primary shrink-0 mt-1">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Respon Cepat</h4>
                                <p class="text-sm text-gray-500">Jadilah penjual yang ramah dan responsif saat ada pembeli yang bertanya atau menawar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Pertanyaan yang Sering Diajukan</h2>
            <div class="space-y-4 max-w-3xl mx-auto">
                <!-- FAQ 1 -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 flex items-center gap-3">
                        <i data-lucide="help-circle" class="w-5 h-5 text-primary"></i> 
                        Apakah jualan di Cuanin aman?
                    </h3>
                    <p class="text-gray-600 pl-8">Tentu, keamanan Anda adalah prioritas kami. Kami memfasilitasi komunikasi yang transparan antara penjual dan pembeli. Pastikan untuk selalu berhati-hati, periksa profil pembeli, dan sepakati metode pembayaran atau pertemuan (COD) di tempat yang aman.</p>
                </div>
                <!-- FAQ 2 -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 flex items-center gap-3">
                        <i data-lucide="help-circle" class="w-5 h-5 text-primary"></i> 
                        Barang apa saja yang bisa dijual?
                    </h3>
                    <p class="text-gray-600 pl-8">Hampir semua barang bekas layak pakai bisa dijual, mulai dari pakaian, elektronik, buku, hingga kendaraan. Pastikan tidak menjual barang terlarang atau ilegal.</p>
                </div>
                <!-- FAQ 3 -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 flex items-center gap-3">
                        <i data-lucide="help-circle" class="w-5 h-5 text-primary"></i> 
                        Bagaimana sistem pembayarannya?
                    </h3>
                    <p class="text-gray-600 pl-8">Cuanin dapat melakukan pembayaran melalui COD (Cash on Delivery) setelah Anda dan pembeli sepakat harga.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
