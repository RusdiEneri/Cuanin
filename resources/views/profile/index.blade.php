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

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        <form action="{{ route('seller.products.update', $product->id) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="editProductForm"
              class="p-6 sm:p-10 space-y-8">
            @csrf
            @method('PUT')
            
            <!-- INFORMASI DASAR -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Informasi Dasar
                </h3>
                <div class="space-y-4">
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
                                    $conditions = [
                                        'Barang Baru' => 'Barang Baru (BNIB)',
                                        'Like New' => 'Like New',
                                        'Sangat Baik' => 'Sangat Baik',
                                        'Baik' => 'Baik',
                                        'Cukup' => 'Cukup',
                                        'Rusak Ringan' => 'Rusak Ringan'
                                    ];
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

            <!-- HARGA & LOKASI -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Detail Harga & Lokasi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                   class="appearance-none block w-full pl-12 pr-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        </div>
                    </div>
                    
                    <div>
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

            <!-- DESKRIPSI & FOTO -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Deskripsi & Foto
                </h3>
                <div class="space-y-6">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Produk
                        </label>
                        
                        @php
                            $existingImages = $product->productImages ?? collect();
                            $currentCount = $existingImages->count();
                            $remainingSlots = 5 - $currentCount;
                        @endphp

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
                                        
                                        <button type="button" 
                                                onclick="confirmDeleteImage({{ $img->id }})" 
                                                class="absolute top-1 right-1 bg-white/90 hover:bg-red-50 text-gray-700 hover:text-danger rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition shadow-sm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <form id="delete-img-{{ $img->id }}" 
                                              action="{{ route('seller.products.images.destroy', $img->id) }}" 
                                              method="POST" 
                                              class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($remainingSlots > 0)
                            <div class="space-y-4">
                                <p class="text-sm font-medium text-gray-700">
                                    Tambah Foto Baru (Sisa slot: <span class="text-primary font-bold">{{ $remainingSlots }}</span>)
                                </p>
                                
                                <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 hidden"></div>

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
                                               multiple />
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

            <!-- STATUS PRODUK -->
            <section>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                    Status Produk
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @php
                        $statuses = [
                            'active' => [
                                'icon' => 'check-circle',
                                'label' => 'Aktif',
                                'desc' => 'Ditampilkan di Marketplace',
                                'color' => 'green',
                                'bg' => 'blue-50',
                                'border' => 'primary'
                            ],
                            'sold' => [
                                'icon' => 'package-check',
                                'label' => 'Terjual',
                                'desc' => 'Produk sudah terjual',
                                'color' => 'yellow',
                                'bg' => 'yellow-50',
                                'border' => 'yellow-500'
                            ],
                            'archived' => [
                                'icon' => 'archive',
                                'label' => 'Diarsipkan',
                                'desc' => 'Disembunyikan dari Marketplace',
                                'color' => 'gray',
                                'bg' => 'gray-50',
                                'border' => 'gray-500'
                            ]
                        ];
                    @endphp

                    @foreach($statuses as $value => $status)
                        <label class="cursor-pointer group">
                            <input type="radio" 
                                   name="status" 
                                   value="{{ $value }}" 
                                   {{ old('status', $product->status) == $value ? 'checked' : '' }} 
                                   class="sr-only peer" 
                                   required>
                            <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-{{ $status['border'] }} transition peer-checked:border-{{ $status['border'] }} peer-checked:bg-{{ $status['bg'] }}">
                                <div class="flex items-center gap-2 text-gray-700 font-semibold">
                                    <i data-lucide="{{ $status['icon'] }}" class="w-5 h-5 text-{{ $status['color'] }}-500"></i> 
                                    {{ $status['label'] }}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $status['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </section>

            <!-- ACTION BUTTONS -->
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('seller.dashboard') }}" 
                   class="px-6 py-3 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" 
                        id="submitBtn"
                        class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span id="submitText">Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Upload Foto - DIPERBAIKI -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const existingCount = {{ $currentCount }};
    const maxAllowed = {{ $remainingSlots }};
    let selectedFiles = [];
    
    const fileInput = document.getElementById('dropzone-file');
    const previewGrid = document.getElementById('preview-grid');
    const uploadArea = document.getElementById('upload-area');
    const form = document.getElementById('editProductForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');

    // Handle file input change
    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const newFiles = Array.from(event.target.files);
            
            if (selectedFiles.length + newFiles.length > maxAllowed) {
                alert(`Maksimal foto yang bisa ditambahkan adalah ${maxAllowed}`);
                event.target.value = '';
                return;
            }
            
            selectedFiles = selectedFiles.concat(newFiles);
            updateFileInputAndPreview();
        });
    }

    // Remove file from selection
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInputAndPreview();
    }

    // Confirm delete existing image
    window.confirmDeleteImage = function(imageId) {
        if (confirm('Yakin ingin menghapus foto ini?')) {
            const deleteForm = document.getElementById(`delete-img-${imageId}`);
            if (deleteForm) {
                deleteForm.submit();
            }
        }
    }

    // Update preview and file input
    function updateFileInputAndPreview() {
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        if (fileInput) {
            fileInput.files = dt.files;
        }

        // Clear preview
        if (previewGrid) {
            previewGrid.innerHTML = '';
        }
        
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
            
            // Re-initialize Lucide icons with error handling
            try {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            } catch (error) {
                console.warn('Lucide icons error:', error);
            }
        } else {
            if (previewGrid) {
                previewGrid.classList.add('hidden');
            }
            if (uploadArea) {
                uploadArea.classList.remove('hidden');
            }
        }
    }

    // Form submission with loading state
    if (form) {
        form.addEventListener('submit', function(e) {
            if (submitBtn && submitText) {
                submitBtn.disabled = true;
                submitText.textContent = 'Menyimpan...';
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });
    }
});
</script>
@endsection