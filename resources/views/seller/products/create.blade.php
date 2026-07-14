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
        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 space-y-8">
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
                            <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 hidden">
                                <!-- Previews will be injected here via JS -->
                            </div>

                            <!-- Upload Area -->
                            <div id="upload-area" class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition hover:border-primary">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i data-lucide="images" class="w-10 h-10 text-gray-400 mb-3"></i>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk pilih foto</span> (Bisa pilih lebih dari satu)</p>
                                        <p class="text-xs text-gray-400">PNG, JPG or WEBP (MAX. 2MB/foto)</p>
                                    </div>
                                    <input id="dropzone-file" type="file" name="images[]" class="hidden" accept="image/*" multiple required onchange="handleFiles(event)" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <script>
                        let selectedFiles = []; // Store selected files

                        function handleFiles(event) {
                            const newFiles = Array.from(event.target.files);
                            if (selectedFiles.length + newFiles.length > 5) {
                                alert('Maksimal 5 foto produk.');
                                return;
                            }

                            selectedFiles = selectedFiles.concat(newFiles);
                            updateFileInputAndPreview();
                        }

                        function removeFile(index) {
                            selectedFiles.splice(index, 1);
                            updateFileInputAndPreview();
                        }

                        function updateFileInputAndPreview() {
                            const previewGrid = document.getElementById('preview-grid');
                            const uploadArea = document.getElementById('upload-area');
                            
                            // Update input element using DataTransfer
                            const dt = new DataTransfer();
                            selectedFiles.forEach(file => dt.items.add(file));
                            document.getElementById('dropzone-file').files = dt.files;

                            // Update UI
                            previewGrid.innerHTML = '';
                            
                            if (selectedFiles.length > 0) {
                                previewGrid.classList.remove('hidden');
                                // Hide upload area if max 5 reached
                                if (selectedFiles.length >= 5) {
                                    uploadArea.classList.add('hidden');
                                } else {
                                    uploadArea.classList.remove('hidden');
                                    uploadArea.querySelector('label').classList.replace('h-48', 'h-32'); // make it smaller
                                }

                                selectedFiles.forEach((file, index) => {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const div = document.createElement('div');
                                        div.className = 'relative w-full aspect-square rounded-xl overflow-hidden border border-gray-200 group bg-white';
                                        
                                        const label = index === 0 ? '<span class="absolute top-1 left-1 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">Foto Utama</span>' : '';
                                        
                                        div.innerHTML = `
                                            <img src="${e.target.result}" class="w-full h-full object-cover" />
                                            ${label}
                                            <button type="button" onclick="removeFile(${index})" class="absolute top-1 right-1 bg-white/80 hover:bg-red-50 text-gray-700 hover:text-danger rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        `;
                                        previewGrid.appendChild(div);
                                    }
                                    reader.readAsDataURL(file);
                                });
                            } else {
                                previewGrid.classList.add('hidden');
                                uploadArea.classList.remove('hidden');
                                uploadArea.querySelector('label').classList.replace('h-32', 'h-48');
                                // Ensure input requires file if empty
                                document.getElementById('dropzone-file').required = true;
                            }
                        }
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
