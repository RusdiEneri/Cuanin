@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('seller.dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Tambah Produk Baru</h1>
            <p class="text-gray-500">Jual barang bekas Anda dengan mudah.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl border border-red-100">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            <!-- Connector -->
            <div class="mx-4 sm:mx-8 flex-shrink-0 relative">
                <div class="w-20 sm:w-32 h-1 bg-gray-200 rounded-full overflow-hidden">
                    <div id="progress-fill" class="h-full bg-primary rounded-full transition-all duration-700 ease-out" style="width: 0%"></div>
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
            <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 transition flex-shrink-0">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        {{-- Form tunggal: kedua step ada di dalam form ini --}}
        <form id="product-form" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="action" id="form-action" value="publish">

            <!-- ==================== STEP 1: INFORMASI PRODUK ==================== -->
            <div id="step1-content" class="p-6 sm:p-10 space-y-8">

                <!-- Info Dasar -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="field-title" value="{{ old('title') }}" placeholder="Contoh: Sepatu Nike Air Max" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" id="field-category" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span class="text-danger">*</span></label>
                                <select name="condition" id="field-condition" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                    <option value="">Pilih Kondisi</option>
                                    <option value="BNOB" {{ old('condition') == 'BNOB' ? 'selected' : '' }}>BNOB (Brand New Open Box)</option>
                                    <option value="Like New" {{ old('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
                                    <option value="Normal" {{ old('condition') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Parah" {{ old('condition') == 'Rusak Parah' ? 'selected' : '' }}>Rusak Parah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Harga & Lokasi -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Detail Harga & Lokasi</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500">Rp</span>
                                    </div>
                                    <input type="number" name="price" id="field-price" value="{{ old('price') }}" placeholder="100000" min="0" class="appearance-none block w-full pl-12 pr-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pengiriman (Kota) <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="field-location" value="{{ old('location') }}" placeholder="Contoh: Jakarta Selatan" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi & Foto -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Deskripsi & Foto</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap <span class="text-danger">*</span></label>
                            <textarea name="description" id="field-description" rows="5" placeholder="Jelaskan detail produk, minus, kelengkapan, dll..." class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Maks 5 Foto) <span class="text-danger">*</span></label>
                            <div class="flex flex-col gap-4">

                                <!-- Preview Grid -->
                                <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 hidden"></div>

                                <!-- Upload Area: 2 jalur (Kamera + Galeri) -->
                                <div id="upload-area" class="w-full">
                                    <div class="flex flex-col items-center justify-center w-full gap-4 p-6 border-2 border-gray-300 border-dashed rounded-2xl bg-gray-50">
                                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                                            {{-- Tombol KAMERA --}}
                                            <label for="camera-input" class="flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-3 rounded-xl font-semibold text-white bg-primary hover:bg-blue-700 cursor-pointer transition shadow-sm">
                                                <i data-lucide="camera" class="w-5 h-5"></i>
                                                <span>Ambil Foto</span>
                                            </label>

                                            {{-- Tombol GALERI --}}
                                            <label for="gallery-input" class="flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-3 rounded-xl font-semibold text-gray-700 bg-white border border-gray-300 hover:border-primary hover:text-primary cursor-pointer transition">
                                                <i data-lucide="images" class="w-5 h-5"></i>
                                                <span>Pilih dari Galeri</span>
                                            </label>
                                        </div>

                                        <div class="text-center">
                                            <p class="text-sm text-gray-500">Bisa pilih lebih dari satu • maks 5 foto</p>
                                            <p class="text-xs text-gray-400 mt-1">PNG, JPG or WEBP (MAX. 2MB/foto)</p>
                                        </div>
                                    </div>

                                    {{-- Input KAMERA --}}
                                    <input id="camera-input" type="file" accept="image/*" capture="environment" class="hidden" onchange="addFiles(event)" />

                                    {{-- Input GALERI --}}
                                    <input id="gallery-input" type="file" accept="image/*" multiple class="hidden" onchange="addFiles(event)" />

                                    {{-- Input PENAMPUNG --}}
                                    <input id="final-images" type="file" name="images[]" multiple class="hidden" />

                                    {{-- Pesan error validasi manual --}}
                                    <p id="photo-error" class="hidden text-xs text-danger mt-2">Minimal 1 foto produk wajib diunggah.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 1 Buttons -->
                <div class="pt-6 flex flex-col sm:flex-row justify-between gap-3 border-t border-gray-100">
                    <button type="button" onclick="saveDraft()" class="order-2 sm:order-1 px-6 py-3 rounded-xl font-medium text-gray-600 bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-danger hover:border-red-200 transition flex items-center justify-center gap-2">
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
            </div>

            <!-- ==================== STEP 2: PEMBAYARAN ==================== -->
            <div id="step2-content" class="p-6 sm:p-10 hidden">
                
                <!-- Ringkasan Produk -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Ringkasan Produk</h3>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Nama Produk</p>
                                <p id="summary-title" class="text-sm font-semibold text-gray-900 line-clamp-2">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Kategori</p>
                                <p id="summary-category" class="text-sm font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Harga Jual</p>
                                <p id="summary-price" class="text-sm font-bold text-primary">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran Jasa -->
                <div class="mb-8">
                    <div class="text-center mb-10">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h3>
                        <p class="text-gray-500 text-sm">Satu langkah lagi agar produk Anda tampil di marketplace.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
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

                            <!-- Warning -->
                            <div class="bg-yellow-50/80 border border-yellow-200/50 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                                <div class="bg-yellow-100 rounded-full p-1.5 flex-shrink-0 mt-0.5">
                                    <i data-lucide="alert-triangle" class="w-4 h-4 text-warning"></i>
                                </div>
                                <p class="text-xs text-yellow-700 leading-relaxed">
                                    Pastikan Anda menyelesaikan pembayaran sebelum menekan tombol konfirmasi. Jika Anda ingin menundanya, silakan pilih <strong>Simpan sebagai Draft</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 Buttons -->
                <div class="pt-6 flex flex-col sm:flex-row justify-between gap-3 border-t border-gray-100">
                    <button type="button" onclick="saveDraft()" class="order-3 sm:order-1 px-6 py-3 rounded-xl font-medium text-gray-600 bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-danger hover:border-red-200 transition flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan sebagai Draft
                    </button>
                    <div class="flex gap-3 order-1 sm:order-2">
                        <button type="button" onclick="goToStep1()" class="px-6 py-3 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-yellow-50 hover:text-yellow-700 transition flex items-center gap-2">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Kembali
                        </button>
                        <button type="submit" onclick="document.getElementById('form-action').value='publish'" class="bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Konfirmasi Pembayaran & Publikasikan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ========================================
    // IMAGE UPLOAD (sama dengan versi lama)
    // ========================================
    let selectedFiles = [];

    const MAX_SIZE   = 2 * 1024 * 1024;
    const MAX_DIM    = 1600;
    const START_QUAL = 0.85;

    function compressImage(file) {
        return new Promise((resolve) => {
            if (file.size <= MAX_SIZE && file.type === 'image/jpeg') {
                resolve(file);
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    let { width, height } = img;
                    if (width > MAX_DIM || height > MAX_DIM) {
                        if (width > height) {
                            height = Math.round(height * MAX_DIM / width);
                            width  = MAX_DIM;
                        } else {
                            width  = Math.round(width * MAX_DIM / height);
                            height = MAX_DIM;
                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width  = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    let quality = START_QUAL;
                    let dataUrl = canvas.toDataURL('image/jpeg', quality);

                    while (dataUrl.length * 0.75 > MAX_SIZE && quality > 0.4) {
                        quality -= 0.1;
                        dataUrl = canvas.toDataURL('image/jpeg', quality);
                    }

                    const byteString = atob(dataUrl.split(',')[1]);
                    const ab = new ArrayBuffer(byteString.length);
                    const ia = new Uint8Array(ab);
                    for (let i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);

                    const blob = new Blob([ab], { type: 'image/jpeg' });
                    const compressed = new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });

                    resolve(compressed);
                };
                img.onerror = () => resolve(file);
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    async function addFiles(event) {
        const newFiles = Array.from(event.target.files);
        event.target.value = '';
        if (newFiles.length === 0) return;

        if (selectedFiles.length + newFiles.length > 5) {
            alert('Maksimal 5 foto produk.');
            return;
        }

        const compressed = await Promise.all(newFiles.map(f => compressImage(f)));

        const stillBig = compressed.filter(f => f.size > MAX_SIZE);
        if (stillBig.length > 0) {
            alert('Beberapa foto tetap terlalu besar setelah dikompres. Silakan pilih foto lain.');
            return;
        }

        selectedFiles = selectedFiles.concat(compressed);
        updatePreview();
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
    }

    function updatePreview() {
        const previewGrid = document.getElementById('preview-grid');
        const uploadArea  = document.getElementById('upload-area');
        const finalInput  = document.getElementById('final-images');
        const photoError  = document.getElementById('photo-error');

        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        finalInput.files = dt.files;

        previewGrid.innerHTML = '';

        if (selectedFiles.length > 0) {
            previewGrid.classList.remove('hidden');
            photoError.classList.add('hidden');
            uploadArea.classList.toggle('hidden', selectedFiles.length >= 5);

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative w-full aspect-square rounded-xl overflow-hidden border border-gray-200 group bg-white';

                    const label = index === 0
                        ? '<span class="absolute top-1 left-1 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">Foto Utama</span>'
                        : '';

                    const sizeKB = (file.size / 1024).toFixed(0);

                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" />
                        ${label}
                        <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded">${sizeKB}KB</span>
                        <button type="button" onclick="removeFile(${index})" class="absolute top-1 right-1 bg-white/80 hover:bg-red-50 text-gray-700 hover:text-danger rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            previewGrid.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        }
    }

    // ========================================
    // STEP NAVIGATION
    // ========================================
    let currentStep = 1;

    function goToStep2() {
        // Validate all required fields
        const title = document.getElementById('field-title').value.trim();
        const category = document.getElementById('field-category').value;
        const condition = document.getElementById('field-condition').value;
        const price = document.getElementById('field-price').value;
        const location = document.getElementById('field-location').value.trim();
        const description = document.getElementById('field-description').value.trim();

        const missing = [];
        if (!title) missing.push('Nama Produk');
        if (!category) missing.push('Kategori');
        if (!condition) missing.push('Kondisi');
        if (!price) missing.push('Harga');
        if (!location) missing.push('Lokasi');
        if (!description) missing.push('Deskripsi');
        if (selectedFiles.length === 0) missing.push('Foto Produk');

        if (missing.length > 0) {
            showToast('Harap lengkapi: ' + missing.join(', '));
            
            // Highlight empty fields
            highlightEmptyFields();
            return;
        }

        // Populate summary
        document.getElementById('summary-title').textContent = title;
        const catSelect = document.getElementById('field-category');
        document.getElementById('summary-category').textContent = catSelect.options[catSelect.selectedIndex].text;
        document.getElementById('summary-price').textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');

        // Switch to step 2
        currentStep = 2;
        document.getElementById('step1-content').classList.add('hidden');
        document.getElementById('step2-content').classList.remove('hidden');

        // Update progress bar
        document.getElementById('step1-indicator').classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        document.getElementById('step1-indicator').classList.add('bg-green-500', 'text-white', 'shadow-lg', 'shadow-green-500/30');
        document.getElementById('step1-indicator').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        document.getElementById('step1-label').classList.remove('text-primary');
        document.getElementById('step1-label').classList.add('text-green-600');

        document.getElementById('step2-indicator').classList.remove('bg-gray-200', 'text-gray-400');
        document.getElementById('step2-indicator').classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        document.getElementById('step2-label').classList.remove('text-gray-400');
        document.getElementById('step2-label').classList.add('text-primary');

        document.getElementById('progress-fill').style.width = '100%';

        // Scroll top
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Re-init lucide icons for step 2
        setTimeout(() => lucide.createIcons(), 100);
    }

    function goToStep1() {
        currentStep = 1;
        document.getElementById('step2-content').classList.add('hidden');
        document.getElementById('step1-content').classList.remove('hidden');

        // Reset progress bar to step 1 active
        document.getElementById('step1-indicator').classList.remove('bg-green-500', 'shadow-green-500/30');
        document.getElementById('step1-indicator').classList.add('bg-primary', 'shadow-blue-500/30');
        document.getElementById('step1-indicator').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>';
        document.getElementById('step1-label').classList.remove('text-green-600');
        document.getElementById('step1-label').classList.add('text-primary');

        document.getElementById('step2-indicator').classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        document.getElementById('step2-indicator').classList.add('bg-gray-200', 'text-gray-400');
        document.getElementById('step2-label').classList.remove('text-primary');
        document.getElementById('step2-label').classList.add('text-gray-400');

        document.getElementById('progress-fill').style.width = '0%';

        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => lucide.createIcons(), 100);
    }

    function saveDraft() {
        const title = document.getElementById('field-title').value.trim();
        if (!title) {
            showToast('Minimal isi Nama Produk untuk menyimpan draft.');
            document.getElementById('field-title').classList.add('border-danger', 'ring-1', 'ring-danger');
            document.getElementById('field-title').focus();
            return;
        }
        document.getElementById('form-action').value = 'draft';
        document.getElementById('product-form').submit();
    }

    // ========================================
    // TOAST NOTIFICATION
    // ========================================
    let toastTimeout = null;

    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        const msgEl = document.getElementById('toast-message');
        msgEl.textContent = message;
        
        toast.classList.remove('-translate-y-[150%]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        // Re-init icons in toast
        setTimeout(() => lucide.createIcons(), 50);

        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => hideToast(), 5000);
    }

    function hideToast() {
        const toast = document.getElementById('toast-notification');
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('-translate-y-[150%]', 'opacity-0');
    }

    // ========================================
    // FIELD HIGHLIGHT ON ERROR
    // ========================================
    function highlightEmptyFields() {
        const fields = [
            { id: 'field-title', check: (el) => !el.value.trim() },
            { id: 'field-category', check: (el) => !el.value },
            { id: 'field-condition', check: (el) => !el.value },
            { id: 'field-price', check: (el) => !el.value },
            { id: 'field-location', check: (el) => !el.value.trim() },
            { id: 'field-description', check: (el) => !el.value.trim() },
        ];

        let firstEmpty = null;

        fields.forEach(({ id, check }) => {
            const el = document.getElementById(id);
            if (check(el)) {
                el.classList.add('border-danger', 'ring-1', 'ring-danger');
                if (!firstEmpty) firstEmpty = el;
            } else {
                el.classList.remove('border-danger', 'ring-1', 'ring-danger');
            }
        });

        // Photo check
        if (selectedFiles.length === 0) {
            document.getElementById('photo-error').classList.remove('hidden');
            document.getElementById('upload-area').classList.remove('hidden');
        }

        // Scroll to first error
        if (firstEmpty) {
            firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstEmpty.focus();
        }
    }

    // Remove highlight on input
    document.querySelectorAll('#step1-content input, #step1-content select, #step1-content textarea').forEach(el => {
        el.addEventListener('input', () => {
            el.classList.remove('border-danger', 'ring-1', 'ring-danger');
        });
        el.addEventListener('change', () => {
            el.classList.remove('border-danger', 'ring-1', 'ring-danger');
        });
    });

    // Prevent native form submit on step 1 (Enter key)
    document.getElementById('product-form').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && currentStep === 1 && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
        }
    });
</script>
@endpush
@endsection