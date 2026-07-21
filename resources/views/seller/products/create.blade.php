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

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        {{-- Tambahkan id form untuk validasi manual foto --}}
        <form id="product-form" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 space-y-8">
            @csrf

            <!-- Info Dasar -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Sepatu Nike Air Max" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category_id" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                            <select name="condition" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                <option value="">Pilih Kondisi</option>
                                <option value="Barang Baru" {{ old('condition') == 'Barang Baru' ? 'selected' : '' }}>Barang Baru (BNIB)</option>
                                <option value="Like New" {{ old('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
                                <option value="Sangat Baik" {{ old('condition') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Cukup" {{ old('condition') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" name="price" value="{{ old('price') }}" required placeholder="100000" min="0" class="appearance-none block w-full pl-12 pr-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pengiriman (Kota)</label>
                            <input type="text" name="location" value="{{ old('location') }}" required placeholder="Contoh: Jakarta Selatan" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi & Foto -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Deskripsi & Foto</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap</label>
                        <textarea name="description" rows="5" required placeholder="Jelaskan detail produk, minus, kelengkapan, dll..." class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Maks 5 Foto)</label>
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

                                {{-- Input KAMERA: capture="environment" = kamera belakang. TANPA name. --}}
                                <input id="camera-input" type="file" accept="image/*" capture="environment" class="hidden" onchange="addFiles(event)" />

                                {{-- Input GALERI: multiple. TANPA name. --}}
                                <input id="gallery-input" type="file" accept="image/*" multiple class="hidden" onchange="addFiles(event)" />

                                {{-- Input PENAMPUNG yang benar-benar dikirim (name="images[]"). Diisi via JS. --}}
                                <input id="final-images" type="file" name="images[]" multiple class="hidden" />

                                {{-- Pesan error validasi manual --}}
                                <p id="photo-error" class="hidden text-xs text-danger mt-2">Minimal 1 foto produk wajib diunggah.</p>
                            </div>
                        </div>
                    </div>

                    <script>
                    let selectedFiles = [];

                    const MAX_SIZE   = 2 * 1024 * 1024; // 2 MB (target akhir)
                    const MAX_DIM    = 1600;            // resize dimensi max (px)
                    const START_QUAL = 0.85;            // kualitas awal JPEG

                    // Kompres 1 file jadi < 2MB, return File baru
                    function compressImage(file) {
                        return new Promise((resolve) => {
                            // Kalau sudah kecil & bukan HEIC, langsung pakai aslinya
                            if (file.size <= MAX_SIZE && file.type === 'image/jpeg') {
                                resolve(file);
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const img = new Image();
                                img.onload = () => {
                                    // Hitung dimensi baru (jaga aspect ratio)
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

                                    // Turunkan kualitas bertahap sampai < 2MB
                                    let quality = START_QUAL;
                                    let dataUrl = canvas.toDataURL('image/jpeg', quality);

                                    while (dataUrl.length * 0.75 > MAX_SIZE && quality > 0.4) {
                                        quality -= 0.1;
                                        dataUrl = canvas.toDataURL('image/jpeg', quality);
                                    }

                                    // dataURL -> Blob -> File
                                    const byteString = atob(dataUrl.split(',')[1]);
                                    const ab = new ArrayBuffer(byteString.length);
                                    const ia = new Uint8Array(ab);
                                    for (let i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);

                                    const blob = new Blob([ab], { type: 'image/jpeg' });
                                    const compressed = new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', {
                                        type: 'image/jpeg',
                                        lastModified: Date.now()
                                    });

                                    console.log(`${file.name}: ${(file.size/1024).toFixed(0)}KB -> ${(compressed.size/1024).toFixed(0)}KB`);
                                    resolve(compressed);
                                };
                                img.onerror = () => resolve(file); // gagal load -> pakai asli
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

                        // Tampilkan indikator (opsional)
                        const btn = event.target.previousElementSibling;

                        const compressed = await Promise.all(newFiles.map(f => compressImage(f)));

                        // Safety check: kalau masih > 2MB setelah kompresi, tolak
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

                    document.getElementById('product-form').addEventListener('submit', function (e) {
                        const photoError = document.getElementById('photo-error');
                        const uploadArea = document.getElementById('upload-area');

                        if (selectedFiles.length === 0) {
                            e.preventDefault();
                            photoError.classList.remove('hidden');
                            uploadArea.classList.remove('hidden');
                            uploadArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        } else {
                            photoError.classList.add('hidden');
                        }
                    });
                    </script>
                </div>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('seller.dashboard') }}" class="px-6 py-3 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                    Simpan & Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection