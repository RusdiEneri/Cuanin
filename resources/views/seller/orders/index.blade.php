@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('seller.dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Pesanan Masuk</h1>
            <p class="text-gray-500">Kelola pesanan dari pembeli untuk produk Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-6 py-4 font-medium">Order ID & Tanggal</th>
                        <th class="px-6 py-4 font-medium">Pembeli & Pengiriman</th>
                        <th class="px-6 py-4 font-medium">Produk</th>
                        <th class="px-6 py-4 font-medium">Status Pesanan (Keseluruhan)</th>
                        <th class="px-6 py-4 font-medium">Aksi Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orderItems as $item)
                    <tr class="hover:bg-gray-50 transition align-top">
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-900 mb-1">#ORD-{{ str_pad($item->order_id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-semibold text-gray-900 mb-1">{{ $item->order->user->name }}</div>
                            <div class="text-sm text-gray-600 line-clamp-2 max-w-xs">{{ $item->order->shipping_address }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                    @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <i data-lucide="image" class="w-4 h-4"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 text-sm line-clamp-1 mb-0.5">{{ $item->product->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'paid' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'paid' => 'Sudah Dibayar',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusColors[$item->order->status] }}">
                                {{ $statusLabels[$item->order->status] }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <form action="{{ route('seller.orders.status', $item->order_id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="appearance-none block w-full px-3 py-1.5 border border-border-color rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer">
                                    @foreach($statusLabels as $key => $label)
                                        <option value="{{ $key }}" {{ $item->order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="p-1.5 bg-primary text-white rounded-lg hover:bg-blue-700 transition" title="Update Status">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mb-3"></i>
                                <p>Belum ada pesanan masuk untuk produk Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
