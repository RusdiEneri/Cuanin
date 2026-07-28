@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Kelola Kategori</h1>
            <p class="text-gray-500">Atur kategori produk yang tersedia di marketplace.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl flex items-center gap-2 border border-red-100">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Form Tambah -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Kategori</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" required placeholder="Contoh: Elektronik" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Lucide)</label>
                        <input type="text" name="icon" placeholder="Contoh: smartphone" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        <p class="text-xs text-gray-500 mt-1">Nama icon dari lucide.dev</p>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Tambah
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel List -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-sm border-b border-gray-100">
                            <th class="px-6 py-4 font-medium w-16">Icon</th>
                            <th class="px-6 py-4 font-medium">Nama Kategori</th>
                            <th class="px-6 py-4 font-medium">Jml Produk</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="w-10 h-10 bg-blue-50 text-primary rounded-xl flex items-center justify-center">
                                    <i data-lucide="{{ $category->icon ?? 'box' }}" class="w-5 h-5"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $category->name }}</div>
                                <div class="text-xs text-gray-500">/{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $category->products_count }} produk
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada produk di dalamnya.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-danger hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
                <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
