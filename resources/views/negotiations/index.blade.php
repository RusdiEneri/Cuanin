@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Tawar Menawar (Nego)</h1>
        <p class="text-gray-500">Kelola daftar tawaran Anda dan tawaran yang masuk dari pembeli.</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Tawaran Saya (Sebagai Pembeli) -->
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="arrow-up-right" class="w-5 h-5 text-primary"></i> Tawaran Saya
            </h2>
            
            <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
                @if($myOffers->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($myOffers as $offer)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($offer->product->primaryImage)
                                            <img src="{{ asset('storage/' . $offer->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-1 mb-1">{{ $offer->product->title }}</h3>
                                        <div class="text-xs text-gray-500 mb-2">Penjual: {{ $offer->seller->name }}</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm line-through text-gray-400">Rp {{ number_format($offer->product->price, 0, ',', '.') }}</span>
                                            <span class="font-bold text-primary">Rp {{ number_format($offer->offered_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>
                                        @if($offer->status == 'pending')
                                            <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-md">Menunggu Respon</span>
                                        @elseif($offer->status == 'accepted')
                                            <span class="text-xs font-medium text-success bg-green-50 px-2.5 py-1 rounded-md">Disetujui</span>
                                        @else
                                            <span class="text-xs font-medium text-danger bg-red-50 px-2.5 py-1 rounded-md">Ditolak</span>
                                        @endif
                                    </div>
                                    
                                    @if($offer->status == 'accepted')
                                        @php
                                            $waNumber = $offer->seller->phone_number;
                                            if (substr($waNumber, 0, 1) == '0') $waNumber = '62' . substr($waNumber, 1);
                                            $waText = "Halo *" . $offer->seller->name . "*, sesuai kesepakatan nego di Cuanin, saya siap membeli *" . $offer->product->title . "* seharga Rp " . number_format($offer->offered_price, 0, ',', '.') . ". Mohon info pembayarannya.";
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Checkout via WA
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <i data-lucide="handshake" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>Belum ada penawaran yang Anda buat.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tawaran Masuk (Sebagai Penjual) -->
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="arrow-down-left" class="w-5 h-5 text-success"></i> Tawaran Masuk
            </h2>
            
            <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
                @if($incomingOffers->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($incomingOffers as $offer)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($offer->product->primaryImage)
                                            <img src="{{ asset('storage/' . $offer->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-1 mb-1">{{ $offer->product->title }}</h3>
                                        <div class="text-xs text-gray-500 mb-2">Penawar: {{ $offer->buyer->name }}</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm line-through text-gray-400">Rp {{ number_format($offer->product->price, 0, ',', '.') }}</span>
                                            <span class="font-bold text-success">Rp {{ number_format($offer->offered_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>
                                        @if($offer->status == 'pending')
                                            <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-md">Pending</span>
                                        @elseif($offer->status == 'accepted')
                                            <span class="text-xs font-medium text-success bg-green-50 px-2.5 py-1 rounded-md">Disetujui</span>
                                        @else
                                            <span class="text-xs font-medium text-danger bg-red-50 px-2.5 py-1 rounded-md">Ditolak</span>
                                        @endif
                                    </div>
                                    
                                    @if($offer->status == 'pending')
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('negotiations.reject', $offer->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="px-3 py-1.5 bg-white border border-gray-200 text-danger text-sm font-semibold rounded-lg hover:bg-red-50 transition">
                                                    Tolak
                                                </button>
                                            </form>
                                            <form action="{{ route('negotiations.accept', $offer->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="px-3 py-1.5 bg-success text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition">
                                                    Terima
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>Belum ada penawaran masuk untuk produk Anda.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
