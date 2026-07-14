@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Riwayat Pesanan</h1>
        <p class="text-gray-500">Pantau status pesanan dan transaksi belanja Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
                <!-- Order Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 block text-xs">Tanggal Belanja</span>
                            <span class="font-medium text-gray-900">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="hidden sm:block w-px h-8 bg-gray-200"></div>
                        <div>
                            <span class="text-gray-500 block text-xs">Total Tagihan</span>
                            <span class="font-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="hidden sm:block w-px h-8 bg-gray-200"></div>
                        <div>
                            <span class="text-gray-500 block text-xs">No. Pesanan</span>
                            <span class="font-medium text-gray-900">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] }}">
                            {{ $statusLabels[$order->status] }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-6">
                    @foreach($order->items->take(2) as $item)
                    <div class="flex items-center gap-4 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-100' : '' }}">
                        <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex-shrink-0">
                            @if($item->product->primaryImage)
                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-semibold text-gray-900 line-clamp-1 hover:text-primary transition">
                                <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->title }}</a>
                            </h4>
                            <div class="text-sm text-gray-500">{{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach

                    @if($order->items->count() > 2)
                        <div class="text-sm text-gray-500 mt-2">
                            + {{ $order->items->count() - 2 }} produk lainnya
                        </div>
                    @endif
                </div>

                <!-- Order Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                    <a href="{{ route('order.show', $order->id) }}" class="text-sm font-semibold text-primary hover:text-blue-700 transition flex items-center gap-1">
                        Lihat Detail Pesanan <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center justify-center p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4">
                <i data-lucide="package" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">Anda belum pernah melakukan transaksi pembelian. Mulai eksplorasi dan temukan barang impian Anda.</p>
            <a href="{{ route('marketplace') }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                Belanja Sekarang
            </a>
        </div>
    @endif
</div>
@endsection
