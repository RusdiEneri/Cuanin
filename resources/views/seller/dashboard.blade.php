@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard Penjual</h1>
            <p class="text-gray-500">Kelola toko dan pantau performa penjualan Anda.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('seller.orders.index') }}" class="px-5 py-2.5 bg-white border border-border-color text-gray-700 rounded-full font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i data-lucide="package-search" class="w-4 h-4"></i> Kelola Pesanan
            </a>
            <a href="{{ route('seller.products.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats (Tetap sama) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- ... (kode stats tidak berubah) ... -->
    </div>

    <!-- Product List -->
    <!-- Product List -->
<div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-900">Daftar Produk Anda</h2>
        <form method="GET" action="{{ route('seller.dashboard') }}" class="relative w-64">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk..."
                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
            >
            <button type="submit" class="absolute left-4 top-2.5">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                {{-- ✏️ Header tegas: bg solid + uppercase + garis bawah tebal --}}
                <tr class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b-2 border-gray-200">
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dilihat</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            {{-- ✏️ Garis pemisah baris: gray-100 -> gray-200 (pakai gray-300 jika ingin lebih tegas) --}}
            <tbody class="divide-y divide-gray-300">
                @forelse($products as $product)
                {{-- ✏️ Zebra + hover lebih kontras --}}
                <tr class="bg-white even:bg-slate-50 hover:bg-blue-100 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                @if($product->primaryImage)
                                    @php
                                        $imgUrl = str_starts_with($product->primaryImage->image_path, 'http') ? $product->primaryImage->image_path : asset('storage/' . $product->primaryImage->image_path);
                                    @endphp
                                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i data-lucide="image" class="w-5 h-5"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 mb-0.5 line-clamp-1">{{ $product->title }}</div>
                                <div class="text-xs text-gray-500">{{ $product->category->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </td>

                    <td class="px-6 py-4">
                        @if($product->status == 'active')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-100">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Aktif
                            </span>
                        @elseif($product->status == 'sold')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-50 text-yellow-700 text-xs font-semibold rounded-full border border-yellow-100">
                                <i data-lucide="package-check" class="w-3 h-3"></i> Terjual
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">
                                <i data-lucide="archive" class="w-3 h-3"></i> Diarsipkan
                            </span>
                        @endif
                    </td>

                    {{-- ✏️ Angka "Dilihat" lebih tegas --}}
                    <td class="px-6 py-4 text-gray-700 font-medium">
                        {{ $product->views }}
                    </td>

                    <td class="px-6 py-4">
                        {{-- ✏️ Ikon aksi: gray-400 -> gray-500 + area hover bulat supaya tidak samar --}}
                        <div class="flex items-center gap-1">
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                               class="p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-gray-100 transition" title="Lihat">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('seller.products.edit', $product->id) }}"
                               class="p-2 rounded-lg text-gray-500 hover:text-secondary hover:bg-gray-100 transition" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 rounded-lg text-gray-500 hover:text-danger hover:bg-red-50 transition" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        Anda belum menambahkan produk apapun.
                        <a href="{{ route('seller.products.create') }}" class="text-primary font-medium hover:underline">Tambah Sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection