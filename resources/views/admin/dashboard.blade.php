@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard Administrator</h1>
            <p class="text-gray-500">Kelola master data aplikasi Cuanin.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-primary text-white rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2 relative">
                <i data-lucide="check-square" class="w-4 h-4"></i> Kelola & Verifikasi Produk
                @if(($pendingProductsCount ?? 0) > 0)
                    <span class="px-2 py-0.5 bg-yellow-400 text-dark font-bold text-xs rounded-full ml-1 animate-pulse">
                        {{ $pendingProductsCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-border-color text-gray-700 rounded-full font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4"></i> Kelola User
            </a>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-white border border-border-color text-gray-700 rounded-full font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4"></i> Kelola Kategori
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card Menunggu Verifikasi -->
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="bg-gradient-to-br from-amber-500 to-yellow-500 text-white rounded-3xl p-6 shadow-lg shadow-amber-500/20 hover:scale-[1.02] transition-transform flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-15 text-white">
                <i data-lucide="clock" class="w-32 h-32"></i>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">Perlu Verifikasi</span>
            </div>
            <div>
                <div class="text-3xl font-extrabold mb-1">{{ $pendingProductsCount ?? 0 }}</div>
                <div class="text-sm text-amber-100 font-medium flex items-center gap-1">
                    Produk Menunggu Verifikasi <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-3xl p-6 border border-border-color shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-primary">
                    <i data-lucide="box" class="w-6 h-6"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalProducts }}</div>
                <div class="text-sm text-gray-500 font-medium">Total Katalog Produk</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-border-color shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-success">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalUsers }}</div>
                <div class="text-sm text-gray-500 font-medium">Total User Terdaftar</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-border-color shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalCategories }}</div>
                <div class="text-sm text-gray-500 font-medium">Kategori Produk</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Info -->
    <div class="bg-white rounded-3xl border border-border-color shadow-sm p-8 text-center mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Sistem Berjalan Normal</h2>
        <p class="text-gray-500 mb-6 max-w-lg mx-auto">Gunakan menu di atas untuk mengatur pengguna yang terdaftar atau mengkategorikan produk-produk yang ada di marketplace.</p>
        <div class="flex justify-center gap-4">
            <a href="/" class="text-primary hover:underline font-medium">Lihat Halaman Utama</a>
        </div>
    </div>
</div>
@endsection
