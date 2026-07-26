@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header dengan Tombol Kembali -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('seller.dashboard') }}" 
           class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Edit Produk</h1>
            <p class="text-gray-500">Perbarui informasi barang bekas Anda.</p>
        </div>
    </div>

    <!-- Error Validation -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl border border-red-100">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($product->status == 'draft')
    <!-- Step Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <!-- Step 1 -->
            <div class="flex items-center">
                <div id="step1-indicator" class="flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white shadow-lg shadow-blue-500/30 transition-all duration-500">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <span id="step1-label" class="ml-3 text-sm font-semibold text-primary transition-colors duration-300">Informasi Produk</span>
            </div>
            <!-- Divider -->
            <div class="w-16 sm:w-24 mx-2">
                <div class="h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div id="step-divider" class="h-full bg-primary w-0 transition-all duration-500 ease-out"></div>
                </div>
            </div>
            <!-- Step 2 -->
            <div class="flex items-center">
                <div id="step2-indicator" class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-200 text-gray-400 transition-all duration-500">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <span id="step2-label" class="ml-3 text-sm font-semibold text-gray-400 transition-colors duration-300">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast-notification" class="fixed top-10 left-1/2 -translate-x-1/2 z-[100] max-w-sm w-full px-4 transform -translate-y-[150%] opacity-0 transition-all duration-500 ease-out pointer-events-none">
        <div class="bg-white border border-red-200 rounded-2xl shadow-2xl shadow-red-500/10 p-4 flex items-start gap-3 pointer-events-auto">
            <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                <i data-lucide="alert-circle" class="w-5 h-5 text-danger"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">Lengkapi Informasi</p>
                <p id="toast-message" class="text-xs text-gray-500 mt-0.5">Harap isi semua field yang wajib diisi.</p>
            </div>
            <button type="button" onclick="hideToast()" class="text-gray-400 hover:text-gray-600 transition flex-shrink-0">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        <form action="{{ route('seller.products.update', $product->id) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-6 sm:p-10 space-y-0" id="main-edit-form">
            @csrf
            @method('PUT')
            
            <div id="step1-content" class="space-y-8">
            <!-- ==================== INFORMASI DASAR ==================== -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Informasi Dasar
                </h3>
                <div class="space-y-4">
                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title', $product->title) }}" 
                               required 
                               class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>
                    
                    <!-- Kategori & Kondisi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    required 
                                    class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kondisi <span class="text-red-500">*</span>
                            </label>
                            <select name="condition" 
                                    required 
                                    class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                @php
                                    $conditions = \App\Models\Product::CONDITIONS;
                                @endphp
                                @foreach($conditions as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ old('condition', $product->condition) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== HARGA & LOKASI ==================== -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Detail Harga & Lokasi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-medium">Rp</span>
                            </div>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price', (int)$product->price) }}" 
                                   required 
                                   min="0" 
                                   max="9999999999999" 
                                   class="appearance-none block w-full pl-12 pr-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <!-- Stok -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Stok Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="stock" 
                               value="{{ old('stock', $product->stock) }}" 
                               required 
                               min="0" 
                               class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>
                    
                    <!-- Lokasi -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Lokasi Pengiriman (Kota) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="location" 
                               value="{{ old('location', $product->location) }}" 
                               required 
                               placeholder="Contoh: Jakarta Selatan"
                               class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>
                </div>
            </section>

            <!-- ==================== DESKRIPSI & FOTO ==================== -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Deskripsi & Foto
                </h3>
                <div class="space-y-6">
                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  rows="5" 
                                  required 
                                  placeholder="Jelaskan kondisi produk, kelengkapan, dan detail lainnya..."
                                  class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Produk
                        </label>
                        
                        @php
                            $existingImages = $product->productImages;
                            $currentCount = $existingImages->count();
                            $remainingSlots = 5 - $currentCount;
                        @endphp

                        <!-- Foto Existing -->
                        @if($currentCount > 0)
                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-700 mb-3">
                                Foto Saat Ini ({{ $currentCount }}/5):
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                                @foreach($existingImages as $img)
                                    @php
                                        $imgUrl = str_starts_with($img->image_path, 'http') 
                                            ? $img->image_path 
                                            : asset('storage/' . $img->image_path);
                                    @endphp
                                    <div class="relative w-full aspect-square rounded-xl overflow-hidden border border-gray-200 group bg-white">
                                        <img src="{{ $imgUrl }}" 
                                             alt="Product image" 
                                             class="w-full h-full object-cover">
                                        
                                        @if($img->is_primary)
                                            <span class="absolute top-1 left-1 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                                                Utama
                                            </span>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDeleteImage({{ $img->id }})" 
                                                class="absolute top-1 right-1 bg-white/90 hover:bg-red-50 text-gray-700 hover:text-danger rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition shadow-sm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Upload Area -->
                        @if($remainingSlots > 0)
                            <div class="space-y-4">
                                <p class="text-sm font-medium text-gray-700">
                                    Tambah Foto Baru (Sisa slot: <span class="text-primary font-bold">{{ $remainingSlots }}</span>)
                                </p>
                                
                                <!-- Preview Grid -->
                                <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 hidden"></div>

                                <!-- Upload Input -->
                                <div id="upload-area" class="flex items-center justify-center w-full">
                                    <label for="dropzone-file" 
                                           class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition hover:border-primary hover:bg-blue-50">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i data-lucide="images" class="w-8 h-8 text-gray-400 mb-2"></i>
                                            <p class="mb-1 text-sm text-gray-500">
                                                <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-gray-400">PNG, JPG hingga 5MB</p>
                                        </div>
                                        <input id="dropzone-file" 
                                               type="file" 
                                               name="images[]" 
                                               class="hidden" 
                                               accept="image/*" 
                                               multiple 
                                               onchange="handleFiles(event)" />
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 text-yellow-800 text-sm flex items-center gap-2">
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                Anda sudah mencapai batas maksimal 5 foto. Hapus beberapa foto untuk menambahkan yang baru.
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            @if($product->status == 'draft')
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between gap-3 mt-8">
                    <input type="hidden" name="status" id="draft-status-step1" value="draft">
                    <button type="submit" class="order-2 sm:order-1 px-6 py-3 rounded-xl font-medium text-gray-600 bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-danger hover:border-red-200 transition flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan sebagai Draft
                    </button>
                    <div class="flex gap-3 order-1 sm:order-2">
                        <a href="{{ route('seller.dashboard') }}" class="px-6 py-3 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-yellow-50 hover:text-yellow-700 transition">Batal</a>
                        <button type="button" onclick="goToStep2()" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                            Lanjut ke Pembayaran
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            @endif
            </div> <!-- End step1-content -->

            <!-- ==================== STATUS PRODUK ==================== -->
            @if($product->status !== 'draft')
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Status Produk
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    
                    <!-- Pilihan 1: Aktif -->
                    <label class="cursor-pointer">
                        <input type="radio" 
                               name="status" 
                               value="active" 
                               {{ old('status', $product->status) == 'active' ? 'checked' : '' }} 
                               class="sr-only peer" 
                               required>
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-primary transition peer-checked:border-primary peer-checked:bg-blue-50">
                            <div class="flex items-center gap-2 text-gray-700 font-semibold">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i> 
                                Aktif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Ditampilkan di Marketplace</p>
                        </div>
                    </label>

                    <!-- Pilihan 2: Terjual -->
                    <label class="cursor-pointer">
                        <input type="radio" 
                               name="status" 
                               value="sold" 
                               {{ old('status', $product->status) == 'sold' ? 'checked' : '' }} 
                               class="sr-only peer" 
                               required>
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-yellow-500 transition peer-checked:border-yellow-500 peer-checked:bg-yellow-50">
                            <div class="flex items-center gap-2 text-gray-700 font-semibold">
                                <i data-lucide="package-check" class="w-5 h-5 text-yellow-600"></i> 
                                Terjual
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Produk sudah terjual</p>
                        </div>
                    </label>

                    <!-- Pilihan 3: Diarsipkan -->
                    <label class="cursor-pointer">
                        <input type="radio" 
                               name="status" 
                               value="archived" 
                               {{ old('status', $product->status) == 'archived' ? 'checked' : '' }} 
                               class="sr-only peer" 
                               required>
                        <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-gray-500 transition peer-checked:border-gray-500 peer-checked:bg-gray-50">
                            <div class="flex items-center gap-2 text-gray-700 font-semibold">
                                <i data-lucide="archive" class="w-5 h-5 text-gray-500"></i> 
                                Diarsipkan
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Disembunyikan dari Marketplace</p>
                        </div>
                    </label>

                </div>
            </section>
            @endif

            @if($product->status == 'draft')
                <!-- ==================== PEMBAYARAN JASA (UNTUK DRAFT) ==================== -->
                <div id="step2-content" class="hidden">
                    <section class="mt-8">
                        <div class="text-center mb-10">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h3>
                            <p class="text-gray-500 text-sm">Satu langkah lagi agar produk Anda tampil di marketplace.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-10">
                            
                            <!-- Left: QR Code -->
                            <div class="lg:col-span-5 flex flex-col items-center">
                                <div class="relative group w-full max-w-[280px]">
                                    <!-- Glowing effect behind -->
                                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-indigo-500 rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-500"></div>
                                    
                                    <!-- QR Card -->
                                    <div class="relative bg-white border border-gray-100 rounded-3xl p-6 shadow-xl shadow-blue-900/5 mx-auto flex flex-col items-center w-full">
                                        <div class="bg-gray-50 rounded-2xl p-4 mb-4 border border-gray-100 w-full flex justify-center">
                                            <img src="{{ asset('qr-ilham.jpeg') }}" alt="QR Code Pembayaran" class="w-full h-auto rounded-xl shadow-sm mix-blend-multiply">
                                        </div>
                                        
                                        <div class="text-center w-full">
                                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Total Tagihan</p>
                                            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary to-indigo-600">Rp 5.000</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Instructions -->
                            <div class="lg:col-span-7 space-y-6">
                                <!-- Alert / Info -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100/50 rounded-2xl p-5 flex gap-4 items-start shadow-sm">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-1 shadow-inner shadow-white/50">
                                        <i data-lucide="info" class="w-5 h-5 text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 mb-1">Biaya Jasa Listing</h4>
                                        <p class="text-sm text-gray-600 leading-relaxed">
                                            Biaya ini dikenakan satu kali untuk mendukung operasional marketplace Cuanin. Produk Anda akan otomatis aktif setelah pembayaran terkonfirmasi.
                                        </p>
                                    </div>
                                </div>

                                <!-- Timeline Steps -->
                                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                                    <h4 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-2">
                                        <i data-lucide="list-ordered" class="w-4 h-4 text-primary"></i>
                                        Cara Pembayaran
                                    </h4>
                                    
                                    <div class="relative pl-2 space-y-6 border-l-2 border-gray-100 ml-3">
                                        <div class="relative">
                                            <div class="absolute -left-[25px] bg-primary w-6 h-6 rounded-full flex items-center justify-center border-4 border-white shadow-sm ring-1 ring-gray-100">
                                                <span class="text-[10px] font-bold text-white">1</span>
                                            </div>
                                            <p class="text-sm text-gray-600 pt-0.5 pl-3">Buka aplikasi e-wallet (GoPay, OVO, Dana) atau Mobile Banking Anda.</p>
                                        </div>
                                        
                                        <div class="relative">
                                            <div class="absolute -left-[25px] bg-primary w-6 h-6 rounded-full flex items-center justify-center border-4 border-white shadow-sm ring-1 ring-gray-100">
                                                <span class="text-[10px] font-bold text-white">2</span>
                                            </div>
                                            <p class="text-sm text-gray-600 pt-0.5 pl-3">Pilih menu <strong>Scan QR</strong> atau <strong>QRIS</strong>.</p>
                                        </div>

                                        <div class="relative">
                                            <div class="absolute -left-[25px] bg-primary w-6 h-6 rounded-full flex items-center justify-center border-4 border-white shadow-sm ring-1 ring-gray-100">
                                                <span class="text-[10px] font-bold text-white">3</span>
                                            </div>
                                            <p class="text-sm text-gray-600 pt-0.5 pl-3">Arahkan kamera ke kode QR di samping dan lakukan pembayaran sebesar <strong class="text-primary">Rp 5.000</strong>.</p>
                                        </div>

                                        <div class="relative">
                                            <div class="absolute -left-[25px] bg-primary w-6 h-6 rounded-full flex items-center justify-center border-4 border-white shadow-sm ring-1 ring-gray-100">
                                                <span class="text-[10px] font-bold text-white">4</span>
                                            </div>
                                            <p class="text-sm text-gray-600 pt-0.5 pl-3">Setelah berhasil, klik tombol <strong>"Konfirmasi Pembayaran"</strong> di bawah ini.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between gap-3">
                            <button type="button" onclick="goToStep1()" class="order-2 sm:order-1 px-6 py-3 rounded-xl font-medium text-gray-600 bg-gray-50 border border-gray-200 hover:bg-yellow-50 hover:text-yellow-700 hover:border-yellow-200 transition flex items-center justify-center gap-2">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                Kembali
                            </button>
                            <div class="flex gap-3 order-1 sm:order-2">
                                <button type="submit" onclick="document.getElementById('draft-status-step1').value='active'" class="bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Konfirmasi Pembayaran & Publikasikan
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            @else
                <!-- ==================== ACTION BUTTONS ==================== -->
                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('seller.dashboard') }}" 
                       class="px-6 py-3 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-yellow-50 hover:text-yellow-700 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Simpan Perubahan
                    </button>
                </div>
            @endif
        </form>
    </div>

    <!-- ==================== FORM HAPUS GAMBAR ==================== -->
    <!-- PENTING: Form ini diletakkan DI LUAR form utama agar HTML Valid & terbaca JavaScript -->
    @if(isset($existingImages) && $existingImages->count() > 0)
        @foreach($existingImages as $img)
            <form id="delete-img-{{ $img->id }}" 
                  action="{{ route('seller.products.images.destroy', $img->id) }}" 
                  method="POST" 
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
</div>

<!-- JavaScript untuk Upload Foto -->
<script>
    const existingCount = {{ $currentCount ?? 0 }};
    const maxAllowed = {{ $remainingSlots ?? 5 }};
    let selectedFiles = [];

    function handleFiles(event) {
        const newFiles = Array.from(event.target.files);
        if (selectedFiles.length + newFiles.length > maxAllowed) {
            alert(`Maksimal foto yang bisa ditambahkan adalah ${maxAllowed}`);
            return;
        }
        selectedFiles = selectedFiles.concat(newFiles);
        updateFileInputAndPreview();
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileInputAndPreview();
    }

    function confirmDeleteImage(imageId) {
        if(confirm('Yakin ingin menghapus foto ini?')) {
            const form = document.getElementById(`delete-img-${imageId}`);
            if(form) {
                form.submit();
            } else {
                alert('Form hapus tidak ditemukan.');
            }
        }
    }

    function updateFileInputAndPreview() {
        const previewGrid = document.getElementById('preview-grid');
        const uploadArea = document.getElementById('upload-area');
        
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        const fileInput = document.getElementById('dropzone-file');
        if(fileInput) fileInput.files = dt.files;

        // Clear and rebuild preview
        if(previewGrid) previewGrid.innerHTML = '';
        
        if (selectedFiles.length > 0 && previewGrid) {
            previewGrid.classList.remove('hidden');
            
            if (selectedFiles.length >= maxAllowed && uploadArea) {
                uploadArea.classList.add('hidden');
            } else if (uploadArea) {
                uploadArea.classList.remove('hidden');
            }

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative w-full aspect-square rounded-xl overflow-hidden border border-gray-200 group bg-white';
                    
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview" />
                        <button type="button" 
                                onclick="removeFile(${index})" 
                                class="absolute top-1 right-1 bg-white/90 hover:bg-red-50 text-gray-700 hover:text-danger rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition shadow-sm">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    `;
                    previewGrid.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
            
            // Re-initialize Lucide icons for new elements
            if (window.lucide) {
                window.lucide.createIcons();
            }
        } else {
            if(previewGrid) previewGrid.classList.add('hidden');
            if(uploadArea) uploadArea.classList.remove('hidden');
        }
    }

    // ========================================
    // TOAST NOTIFICATION
    // ========================================
    let toastTimeout = null;

    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        if(!toast) return;
        const msgEl = document.getElementById('toast-message');
        msgEl.textContent = message;
        
        toast.classList.remove('-translate-y-[150%]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => { if(window.lucide) window.lucide.createIcons() }, 50);

        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => hideToast(), 5000);
    }

    function hideToast() {
        const toast = document.getElementById('toast-notification');
        if(!toast) return;
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('-translate-y-[150%]', 'opacity-0');
    }

    // ========================================
    // FIELD HIGHLIGHT ON ERROR
    // ========================================
    function highlightEmptyFields() {
        const fields = [
            { id: 'title', check: (el) => !el.value.trim() },
            { id: 'category_id', check: (el) => !el.value },
            { id: 'condition', check: (el) => !el.value },
            { id: 'price', check: (el) => !el.value },
            { id: 'location', check: (el) => !el.value.trim() },
            { id: 'description', check: (el) => !el.value.trim() }
        ];

        let firstInvalid = null;
        let hasError = false;

        fields.forEach(field => {
            const el = document.querySelector(`[name="${field.id}"]`);
            if (el) {
                if (field.check(el)) {
                    el.classList.add('border-red-500', 'ring-1', 'ring-red-500', 'bg-red-50');
                    if (!firstInvalid) firstInvalid = el;
                    hasError = true;
                } else {
                    el.classList.remove('border-red-500', 'ring-1', 'ring-red-500', 'bg-red-50');
                }

                el.addEventListener('input', function() {
                    if (!field.check(this)) {
                        this.classList.remove('border-red-500', 'ring-1', 'ring-red-500', 'bg-red-50');
                    }
                }, { once: true });
            }
        });

        // Cek gambar: kalau existing images 0 dan selected files 0
        if (existingCount + selectedFiles.length === 0) {
            const dropzone = document.getElementById('upload-area').querySelector('label');
            if (dropzone) {
                dropzone.classList.add('border-red-500', 'bg-red-50');
                hasError = true;
                if (!firstInvalid) firstInvalid = dropzone;

                const fileInput = document.getElementById('dropzone-file');
                fileInput.addEventListener('change', function() {
                    dropzone.classList.remove('border-red-500', 'bg-red-50');
                }, { once: true });
            }
        }

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return hasError;
    }

    // ========================================
    // STEP NAVIGATION
    // ========================================
    function goToStep2() {
        if (highlightEmptyFields()) {
            showToast('Harap lengkapi informasi produk & foto yang wajib diisi.');
            return;
        }

        // Animate Step Indicators
        document.getElementById('step-divider').classList.remove('w-0');
        document.getElementById('step-divider').classList.add('w-full');

        document.getElementById('step1-indicator').classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        document.getElementById('step1-indicator').classList.add('bg-blue-100', 'text-primary');
        document.getElementById('step1-indicator').innerHTML = '<i data-lucide="check" class="w-5 h-5"></i>';
        
        document.getElementById('step1-label').classList.remove('font-bold', 'text-gray-900');
        document.getElementById('step1-label').classList.add('text-primary');

        document.getElementById('step2-indicator').classList.remove('bg-gray-200', 'text-gray-400');
        document.getElementById('step2-indicator').classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        
        document.getElementById('step2-label').classList.remove('text-gray-400');
        document.getElementById('step2-label').classList.add('font-bold', 'text-gray-900');

        // Switch Content (Fade out Step 1, Fade in Step 2)
        const s1 = document.getElementById('step1-content');
        const s2 = document.getElementById('step2-content');
        
        s1.style.opacity = '0';
        setTimeout(() => {
            s1.classList.add('hidden');
            s2.classList.remove('hidden');
            s2.style.opacity = '0';
            setTimeout(() => {
                s2.style.opacity = '1';
                s2.style.transition = 'opacity 0.3s ease';
            }, 50);
            
            // Re-init icons
            if(window.lucide) window.lucide.createIcons();
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }

    function goToStep1() {
        // Reverse Animate Step Indicators
        document.getElementById('step-divider').classList.remove('w-full');
        document.getElementById('step-divider').classList.add('w-0');

        document.getElementById('step1-indicator').classList.remove('bg-blue-100', 'text-primary');
        document.getElementById('step1-indicator').classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        document.getElementById('step1-indicator').innerHTML = '<i data-lucide="clipboard-list" class="w-5 h-5"></i>';
        
        document.getElementById('step1-label').classList.remove('text-primary');
        document.getElementById('step1-label').classList.add('font-bold', 'text-gray-900');

        document.getElementById('step2-indicator').classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        document.getElementById('step2-indicator').classList.add('bg-gray-200', 'text-gray-400');
        
        document.getElementById('step2-label').classList.remove('font-bold', 'text-gray-900');
        document.getElementById('step2-label').classList.add('text-gray-400');

        // Switch Content
        const s1 = document.getElementById('step1-content');
        const s2 = document.getElementById('step2-content');
        
        s2.style.opacity = '0';
        setTimeout(() => {
            s2.classList.add('hidden');
            s1.classList.remove('hidden');
            s1.style.opacity = '0';
            setTimeout(() => {
                s1.style.opacity = '1';
                s1.style.transition = 'opacity 0.3s ease';
            }, 50);
            
            if(window.lucide) window.lucide.createIcons();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 300);
    }
</script>
@endsection