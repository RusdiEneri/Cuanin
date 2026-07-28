@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Top Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-border-color flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Verifikasi & Kelola Produk</h1>
                <p class="text-gray-500">Tinjau, setujui, dan kelola katalog barang yang diunggah oleh penjual.</p>
            </div>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="relative w-full md:w-72">
            <input type="hidden" name="status" value="{{ $status }}">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari judul atau penjual..." 
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-border-color rounded-full text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm"
            >
            <button type="submit" class="absolute left-3.5 top-3 text-gray-400 hover:text-primary">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-2xl flex items-center gap-3 border border-green-200 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-success flex-shrink-0">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-2xl flex items-center gap-3 border border-red-200 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-danger flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 scrollbar-none border-b border-gray-200">
        <a href="{{ route('admin.products.index', ['status' => 'pending', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition flex items-center gap-2 {{ $status === 'pending' ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'bg-white text-gray-600 border border-border-color hover:bg-gray-50' }}">
            <i data-lucide="clock" class="w-4 h-4"></i>
            Menunggu Verifikasi
            <span class="px-2 py-0.5 rounded-full text-xs {{ $status === 'pending' ? 'bg-white/20 text-white' : 'bg-yellow-100 text-yellow-800' }}">
                {{ $counts['pending'] }}
            </span>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'active', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition flex items-center gap-2 {{ $status === 'active' ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'bg-white text-gray-600 border border-border-color hover:bg-gray-50' }}">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            Disetujui / Aktif
            <span class="px-2 py-0.5 rounded-full text-xs {{ $status === 'active' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-800' }}">
                {{ $counts['active'] }}
            </span>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'rejected', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition flex items-center gap-2 {{ $status === 'rejected' ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'bg-white text-gray-600 border border-border-color hover:bg-gray-50' }}">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            Ditolak
            <span class="px-2 py-0.5 rounded-full text-xs {{ $status === 'rejected' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-800' }}">
                {{ $counts['rejected'] }}
            </span>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'draft', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition flex items-center gap-2 {{ $status === 'draft' ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'bg-white text-gray-600 border border-border-color hover:bg-gray-50' }}">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Draft Penjual
            <span class="px-2 py-0.5 rounded-full text-xs {{ $status === 'draft' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                {{ $counts['draft'] }}
            </span>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'all', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition flex items-center gap-2 {{ $status === 'all' ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'bg-white text-gray-600 border border-border-color hover:bg-gray-50' }}">
            <i data-lucide="layers" class="w-4 h-4"></i>
            Semua Produk
            <span class="px-2 py-0.5 rounded-full text-xs {{ $status === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                {{ $counts['all'] }}
            </span>
        </a>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Penjual</th>
                        <th class="px-6 py-4">Harga & Stok</th>
                        <th class="px-6 py-4">Kondisi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi Verifikasi & Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="bg-white hover:bg-blue-50/50 transition-colors">
                        <!-- Informasi Produk -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 border border-border-color overflow-hidden flex-shrink-0 relative group">
                                    @if($product->displayImageUrl())
                                        <img src="{{ $product->displayImageUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i data-lucide="image" class="w-6 h-6"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm line-clamp-1 hover:text-primary transition cursor-pointer" onclick="openProductModal({{ json_encode($product) }})">
                                        {{ $product->title }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-0.5 rounded-md bg-blue-50 text-primary font-medium border border-blue-100">
                                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                        <span class="text-xs text-gray-400 flex items-center gap-0.5">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $product->location }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Informasi Penjual -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-primary font-bold text-xs flex-shrink-0 border border-white shadow-sm overflow-hidden">
                                    @if($product->user->avatar)
                                        <img src="{{ asset('storage/' . $product->user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($product->user->name ?? 'U', 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $product->user->name ?? 'Unknown Seller' }}</div>
                                    <div class="text-xs text-gray-400">{{ $product->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Harga & Stok -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Stok: <span class="font-medium text-gray-800">{{ $product->stock }} pcs</span></div>
                        </td>

                        <!-- Kondisi -->
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 border border-gray-200 inline-block">
                                {{ $product->condition }}
                            </span>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4">
                            @if($product->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-50 text-yellow-800 text-xs font-semibold rounded-full border border-yellow-200 animate-pulse">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-yellow-600"></i> Menunggu Verifikasi
                                </span>
                            @elseif($product->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-success text-xs font-semibold rounded-full border border-green-200">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-success"></i> Disetujui (Aktif)
                                </span>
                            @elseif($product->status == 'rejected')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-danger text-xs font-semibold rounded-full border border-red-200">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5 text-danger"></i> Ditolak
                                </span>
                            @elseif($product->status == 'draft')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-gray-500"></i> Draft
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-primary text-xs font-semibold rounded-full border border-blue-100">
                                    {{ ucfirst($product->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Aksi Admin -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">

                                <!-- Tombol Detail Modal -->
                                <button type="button" 
                                        onclick="openProductModal({{ json_encode($product) }})"
                                        class="p-2 rounded-xl bg-gray-100 text-gray-600 hover:text-primary hover:bg-blue-100 transition shadow-sm"
                                        title="Lihat Detail Produk & QRIS Listing">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>

                                @if($product->status !== 'active')
                                    <!-- Form Setujui (Approve) -->
                                    <form action="{{ route('admin.products.status', $product->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" 
                                                onclick="confirmAction(event, 'Setujui Produk', 'Setujui dan publikasikan produk ini ke marketplace?', 'Ya, Setujui', '#10b981')"
                                                class="px-3 py-1.5 rounded-xl bg-success text-white text-xs font-semibold hover:bg-green-700 transition shadow-md shadow-green-500/20 flex items-center gap-1"
                                                title="Setujui Produk">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i> Setujui
                                        </button>
                                    </form>
                                @endif

                                @if($product->status !== 'rejected')
                                    <!-- Form Tolak (Reject) -->
                                    <form action="{{ route('admin.products.status', $product->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" 
                                                onclick="confirmAction(event, 'Tolak Produk', 'Tolak produk ini? Penjual akan melihat status produk ditolak.', 'Ya, Tolak', '#f59e0b')"
                                                class="px-3 py-1.5 rounded-xl bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600 transition shadow-md shadow-amber-500/20 flex items-center gap-1"
                                                title="Tolak Produk">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Tolak
                                        </button>
                                    </form>
                                @endif

                                <!-- Form Hapus Produk -->
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="confirmAction(event, 'Hapus Permanen', 'Apakah Anda yakin ingin MENGHAPUS PERMANEN produk ini? Semua foto produk juga akan dihapus dari sistem.', 'Ya, Hapus', '#ef4444')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 rounded-xl text-gray-400 hover:text-danger hover:bg-red-50 transition" 
                                            title="Hapus Produk">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-primary mb-4 border border-blue-100">
                                    <i data-lucide="package-search" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak Ada Produk Ditemukan</h3>
                                <p class="text-gray-500 text-sm max-w-sm">
                                    Belum ada produk dengan status ini atau kata kunci pencarian tidak cocok.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ==================== MODAL DETAIL PRODUK ==================== -->
<div id="productModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl border border-border-color shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-300 relative p-6 sm:p-8">
        
        <!-- Close Button -->
        <button type="button" onclick="closeProductModal()" class="absolute top-5 right-5 text-gray-400 hover:text-dark hover:bg-gray-100 p-2 rounded-full transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <!-- Modal Content -->
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span id="modal-category" class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-primary font-semibold border border-blue-100">Kategori</span>
                <span id="modal-status-badge"></span>
            </div>
            
            <h2 id="modal-title" class="text-xl font-bold text-gray-900 mb-4">Judul Produk</h2>

            <!-- Main Image & Gallery -->
            <div class="mb-6">
                <div class="w-full h-64 bg-gray-100 rounded-2xl overflow-hidden mb-3 border border-border-color flex items-center justify-center">
                    <img id="modal-primary-img" src="" class="w-full h-full object-contain bg-gray-50">
                </div>
                <div id="modal-gallery" class="flex gap-2 overflow-x-auto pb-2">
                    <!-- Images inserted dynamically -->
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 mb-6 text-sm">
                <div>
                    <span class="text-xs text-gray-400 block">Harga Jual</span>
                    <span id="modal-price" class="font-bold text-primary text-base">Rp 0</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Stok Available</span>
                    <span id="modal-stock" class="font-semibold text-gray-800">1 pcs</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Kondisi Barang</span>
                    <span id="modal-condition" class="font-semibold text-gray-800">Baik</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Lokasi Penjual</span>
                    <span id="modal-location" class="font-semibold text-gray-800">-</span>
                </div>
                <div class="col-span-2">
                    <span class="text-xs text-gray-400 block">Nama Penjual</span>
                    <span id="modal-seller" class="font-semibold text-gray-800">-</span>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h4 class="text-sm font-bold text-gray-900 mb-2">Deskripsi Produk:</h4>
                <p id="modal-description" class="text-sm text-gray-600 leading-relaxed whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100 max-h-40 overflow-y-auto">
                    -
                </p>
            </div>

            <!-- Bukti Pembayaran Listing -->
            <div class="mb-6">
                <h4 class="text-sm font-bold text-gray-900 mb-2 flex items-center justify-between">
                    <span>Bukti Pembayaran Listing:</span>
                    <span id="modal-payment-status"></span>
                </h4>
                <div id="modal-payment-proof-container" class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <!-- Injected via JS -->
                </div>
            </div>

            <!-- Modal Verification Action Footer -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeProductModal()" class="px-5 py-2.5 rounded-full border border-border-color text-gray-700 font-medium hover:bg-gray-50 text-sm transition">
                    Tutup
                </button>
                
                <div id="modal-actions" class="flex gap-2">
                    <!-- Action buttons injected dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openProductModal(product) {
        const modal = document.getElementById('productModal');
        const titleEl = document.getElementById('modal-title');
        const categoryEl = document.getElementById('modal-category');
        const primaryImgEl = document.getElementById('modal-primary-img');
        const galleryEl = document.getElementById('modal-gallery');
        const priceEl = document.getElementById('modal-price');
        const stockEl = document.getElementById('modal-stock');
        const conditionEl = document.getElementById('modal-condition');
        const locationEl = document.getElementById('modal-location');
        const sellerEl = document.getElementById('modal-seller');
        const descEl = document.getElementById('modal-description');
        const statusBadgeEl = document.getElementById('modal-status-badge');
        const modalActionsEl = document.getElementById('modal-actions');

        titleEl.textContent = product.title;
        categoryEl.textContent = product.category ? product.category.name : 'Uncategorized';
        priceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(product.price);
        stockEl.textContent = product.stock + ' pcs';
        conditionEl.textContent = product.condition;
        locationEl.textContent = product.location;
        sellerEl.textContent = product.user ? `${product.user.name} (${product.user.email})` : 'Unknown';
        descEl.textContent = product.description || 'Tidak ada deskripsi.';

        // Status Badge
        if (product.status === 'pending') {
            statusBadgeEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>`;
        } else if (product.status === 'active') {
            statusBadgeEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">Disetujui (Aktif)</span>`;
        } else if (product.status === 'rejected') {
            statusBadgeEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">Ditolak</span>`;
        } else {
            statusBadgeEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">${product.status}</span>`;
        }

        // Images handling
        let images = product.product_images || [];
        galleryEl.innerHTML = '';

        let mainUrl = '';
        if (images.length > 0) {
            mainUrl = images[0].image_path.startsWith('http') ? images[0].image_path : `/storage/${images[0].image_path.replace(/^\//, '')}`;
            
            images.forEach((img, idx) => {
                const url = img.image_path.startsWith('http') ? img.image_path : `/storage/${img.image_path.replace(/^\//, '')}`;
                const thumb = document.createElement('img');
                thumb.src = url;
                thumb.className = `w-14 h-14 object-cover rounded-xl border-2 cursor-pointer transition ${idx === 0 ? 'border-primary' : 'border-gray-200 hover:border-primary'}`;
                thumb.onclick = () => {
                    primaryImgEl.src = url;
                    Array.from(galleryEl.children).forEach(child => child.classList.replace('border-primary', 'border-gray-200'));
                    thumb.classList.replace('border-gray-200', 'border-primary');
                };
                galleryEl.appendChild(thumb);
            });
        } else {
            mainUrl = 'https://via.placeholder.com/400x300?text=No+Image';
        }
        primaryImgEl.src = mainUrl;

        // Payment Proof handling
        const paymentProofContainer = document.getElementById('modal-payment-proof-container');
        const paymentStatusEl = document.getElementById('modal-payment-status');

        if (product.payment_proof) {
            const proofUrl = product.payment_proof.startsWith('http') 
                ? product.payment_proof 
                : `/storage/${product.payment_proof.replace(/^\//, '')}`;
            
            paymentStatusEl.innerHTML = `<span class="text-xs px-2.5 py-0.5 rounded-full bg-green-100 text-green-800 font-semibold inline-flex items-center gap-1"><i data-lucide="check-circle" class="w-3 h-3 text-green-600"></i> Bukti Ada</span>`;
            
            paymentProofContainer.innerHTML = `
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="w-full sm:w-44 h-32 bg-white rounded-xl border border-gray-200 overflow-hidden flex-shrink-0 relative group">
                        <img src="${proofUrl}" class="w-full h-full object-contain bg-gray-100">
                        <a href="${proofUrl}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1">
                            <i data-lucide="external-link" class="w-4 h-4"></i> Zoom
                        </a>
                    </div>
                    <div class="text-xs text-gray-600 space-y-1">
                        <p class="font-semibold text-gray-900">Periksa bukti transaksi transfer dari penjual.</p>
                        <p class="text-gray-500">Pastikan nominal transfer sesuai dengan biaya listing sebelum menyetujui produk.</p>
                        <a href="${proofUrl}" target="_blank" class="inline-flex items-center gap-1 text-primary font-semibold hover:underline pt-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Buka Gambar Ukuran Penuh
                        </a>
                    </div>
                </div>
            `;
        } else {
            paymentStatusEl.innerHTML = `<span class="text-xs px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800 font-semibold inline-flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3 text-yellow-600"></i> Belum Ada Bukti</span>`;
            paymentProofContainer.innerHTML = `
                <div class="flex items-center gap-3 text-amber-700">
                    <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="text-xs">Penjual belum mengunggah foto bukti pembayaran untuk produk ini.</span>
                </div>
            `;
        }

        // Modal Action buttons (Approve / Reject)
        let approveUrl = `/admin/products/${product.id}/status`;
        let csrf = `{{ csrf_token() }}`;

        modalActionsEl.innerHTML = `
            ${product.status !== 'active' ? `
                <form action="${approveUrl}" method="POST">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="status" value="active">
                    <button type="submit" onclick="confirmAction(event, 'Setujui Produk', 'Setujui produk ini?', 'Ya, Setujui', '#10b981')" class="px-5 py-2.5 rounded-full bg-success text-white font-semibold text-sm hover:bg-green-700 transition shadow-md shadow-green-500/20">
                        Setujui Produk
                    </button>
                </form>
            ` : ''}
            ${product.status !== 'rejected' ? `
                <form action="${approveUrl}" method="POST">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" onclick="confirmAction(event, 'Tolak Produk', 'Tolak produk ini?', 'Ya, Tolak', '#f59e0b')" class="px-5 py-2.5 rounded-full bg-amber-500 text-white font-semibold text-sm hover:bg-amber-600 transition shadow-md shadow-amber-500/20">
                        Tolak Produk
                    </button>
                </form>
            ` : ''}
        `;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);

        lucide.createIcons();
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush
@endsection
