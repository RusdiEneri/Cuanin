@extends('layouts.app')

@section('content')

@php
    $grouped    = $carts->filter(fn($i) => $i->product)
                        ->groupBy(fn($i) => $i->product->user_id);
    $storeCount = $grouped->count();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER + TOMBOL HAPUS SEMUA --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
            <p class="text-gray-500">Periksa kembali barang yang akan Anda beli.</p>
        </div>

        @if($carts->count() > 0)
            <button type="button" id="clear-cart-btn"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-red-200 text-danger hover:bg-red-50 font-semibold text-sm transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                Hapus Semua
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl flex items-center gap-2 border border-red-100">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
        </div>
    @endif

    @if($carts->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- CART ITEMS (PER TOKO + CHECKBOX) -->
            <div class="w-full lg:w-2/3 space-y-6">
                @foreach($grouped as $sellerId => $items)
                    @php
                        $seller   = $items->first()->product->user;
                        $subtotal = $items->sum(fn($i) => $i->product->price);
                    @endphp

                    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">

                        {{-- Header Toko --}}
                        <div class="flex items-center justify-between gap-3 px-5 sm:px-6 py-4 bg-gray-50/70 border-b border-gray-100">
                            <div class="flex items-center gap-3 min-w-0">
                                <input type="checkbox" checked
                                       class="store-check w-5 h-5 rounded border-gray-300 accent-[#1F49F2] cursor-pointer flex-shrink-0"
                                       data-store="{{ $sellerId }}"
                                       data-seller-name="{{ $seller->name }}"
                                       data-phone="{{ $seller->phone_number }}"
                                       title="Pilih semua produk toko ini">
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold flex-shrink-0">
                                    {{ strtoupper(substr($seller->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="store" class="w-4 h-4 text-primary flex-shrink-0"></i>
                                        <span class="font-semibold text-gray-900 truncate">{{ $seller->name }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        <span class="store-sel-count font-semibold text-primary" data-store="{{ $sellerId }}">{{ $items->count() }}</span>
                                        dari {{ $items->count() }} produk dipilih
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-gray-400">Terpilih</p>
                                <p class="store-subtotal font-bold text-primary text-sm" data-store="{{ $sellerId }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Daftar Produk --}}
                        <div class="p-5 sm:p-6 space-y-6">
                            @foreach($items as $item)
                                @php
                                    $product = $item->product;
                                    $displayImageUrl = $product->displayImageUrl();
                                @endphp
                                <div class="flex items-start gap-3 {{ !$loop->last ? 'pb-6 border-b border-gray-100' : '' }}">
                                    <input type="checkbox" checked
                                           class="item-check w-5 h-5 mt-1 rounded border-gray-300 accent-[#1F49F2] cursor-pointer flex-shrink-0"
                                           data-store="{{ $sellerId }}"
                                           data-price="{{ $product->price * $item->quantity }}"
                                           data-title="{{ $product->title }} (x{{ $item->quantity }})">

                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1 min-w-0">
                                        <div class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                                            @if($displayImageUrl)
                                                <img src="{{ $displayImageUrl }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i data-lucide="image" class="w-8 h-8"></i></div>
                                            @endif
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <h3 class="font-semibold text-gray-900 hover:text-primary transition line-clamp-1 mb-1">{{ $product->title }}</h3>
                                            </a>
                                            <div class="text-sm text-gray-500 mb-2">Penjual: {{ $seller->name }}</div>
                                            <div class="font-bold text-primary mb-2">Rp {{ number_format($product->price * $item->quantity, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500 ml-1">(Rp {{ number_format($product->price, 0, ',', '.') }} / pcs)</span></div>
                                            
                                            <!-- Quantity Update Form -->
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-block" id="form-qty-{{ $item->id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm w-fit">
                                                    <button type="button" onclick="const q = document.getElementById('qty-{{ $item->id }}'); if(q.value > 1) { q.value--; q.form.submit(); }" class="px-2 py-1 text-gray-500 hover:text-primary hover:bg-blue-50 rounded-l-lg transition">-</button>
                                                    <input type="number" id="qty-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $product->stock }}" onchange="this.form.submit()" class="w-10 text-center border-none focus:ring-0 text-xs font-bold text-gray-900 p-0" readonly>
                                                    <button type="button" onclick="const q = document.getElementById('qty-{{ $item->id }}'); if(q.value < {{ $product->stock }}) { q.value++; q.form.submit(); }" class="px-2 py-1 text-gray-500 hover:text-primary hover:bg-blue-50 rounded-r-lg transition">+</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end mt-2 sm:mt-0">
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-danger hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Toko --}}
                        <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-gray-500">
                                Total terpilih: <span class="store-foot-total font-semibold text-gray-900" data-store="{{ $sellerId }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </p>
                            <button type="button" data-store="{{ $sellerId }}"
                                class="store-wa-btn inline-flex items-center justify-center gap-2 bg-[#1F49F2] hover:bg-[#1a3fcc] text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                                <i data-lucide="message-circle" class="w-4 h-4"></i> Lanjut via WhatsApp
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- RINGKASAN -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Belanja</h3>

                    <div id="multi-store-note"
                         class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-xl p-3 flex gap-2 {{ $storeCount <= 1 ? 'hidden' : '' }}">
                        <i data-lucide="info" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <span>Pilihan Anda mencakup <strong>lebih dari 1 toko</strong>. Pembayaran via WhatsApp hanya bisa ke <strong>satu penjual per checkout</strong>. Centang produk dari satu toko saja, atau pakai tombol WA di masing‑masing kartu toko.</span>
                    </div>

                    <div class="space-y-4 mb-6 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Harga (<span id="sum-count">{{ $carts->count() }}</span> barang)</span>
                            <span id="sum-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Toko dipilih</span>
                            <span id="sum-stores">{{ $storeCount }} toko</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total Belanja</span>
                            <span id="sum-grand" class="font-bold text-xl text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="button" id="global-wa-btn"
                        class="flex items-center justify-center gap-2 w-full bg-[#1F49F2] hover:bg-[#1a3fcc] text-white py-3.5 rounded-xl font-semibold transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <i data-lucide="message-circle" class="w-5 h-5"></i> Lanjut via WhatsApp
                    </button>
                    <p class="text-[11px] text-gray-400 text-center mt-2">Mengirim daftar produk terpilih ke WhatsApp penjual.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center justify-center p-16 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-primary mb-4">
                <i data-lucide="shopping-cart" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">Belum ada produk yang Anda tambahkan ke keranjang. Yuk temukan barang bekas berkualitas di marketplace kami!</p>
            <a href="{{ route('marketplace') }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-md shadow-blue-500/20">Mulai Belanja</a>
        </div>
    @endif
</div>

{{-- MODAL KONFIRMASI HAPUS SEMUA --}}
<div id="clear-cart-modal" role="dialog" aria-modal="true" aria-labelledby="clear-modal-title"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="absolute inset-0" data-clear-close></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <button type="button" data-clear-close aria-label="Tutup"
            class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition z-10">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <div class="p-6 sm:p-8 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                <i data-lucide="trash-2" class="w-8 h-8 text-red-600"></i>
            </div>
            <h3 id="clear-modal-title" class="text-xl font-bold text-gray-900 mb-2">Hapus Semua Item?</h3>
            <p class="text-sm text-gray-500 leading-relaxed mb-2">
                Apakah Anda yakin ingin menghapus <strong class="text-gray-700">seluruh {{ $carts->count() }} produk</strong> di keranjang?
            </p>
            <p class="text-sm text-red-600 font-medium bg-red-50 border border-red-100 rounded-lg p-3">
                Tindakan ini <strong>tidak dapat dibatalkan</strong>. Semua produk akan dihapus dari keranjang Anda.
            </p>
        </div>
        <form action="{{ route('cart.clear') }}" method="POST" class="px-6 sm:px-8 pb-6 sm:pb-8">
            @csrf
            @method('DELETE')
            <div class="flex flex-col-reverse sm:flex-row gap-3">
                <button type="button" data-clear-close class="flex-1 px-5 py-3 rounded-xl font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 px-5 py-3 rounded-xl font-semibold text-white bg-red-500 hover:bg-red-600 active:bg-red-700 transition shadow-md shadow-red-500/20 flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-5 h-5"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- LOGIKA JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const globalBtn = document.getElementById('global-wa-btn');

    const num  = s => parseInt(s, 10) || 0;
    const fmt  = n => 'Rp ' + (n || 0).toLocaleString('id-ID');
    const set  = (sel, val) => { const el = document.querySelector(sel); if (el) el.textContent = val; };
    const toWa = phone => {
        const d = (phone || '').replace(/\D/g, '');
        if (!d) return null;
        if (d.startsWith('0'))   return '62' + d.slice(1);
        if (!d.startsWith('62')) return '62' + d;
        return d;
    };

    function buildWa(storeId, checks) {
        const sc   = document.querySelector('.store-check[data-store="' + storeId + '"]');
        const name = sc ? sc.dataset.sellerName : 'Penjual';
        const wn   = sc ? toWa(sc.dataset.phone) : null;
        if (!wn) { alert('Penjual ini belum mencantumkan nomor WhatsApp.'); return null; }
        let sub = 0; const lines = [];
        checks.forEach((c, i) => { const p = num(c.dataset.price); sub += p; lines.push((i + 1) + '. ' + c.dataset.title + ' - ' + fmt(p)); });
        const msg = 'Halo ' + name + ', saya ingin memesan produk berikut dari toko Anda:\n\n' + lines.join('\n') + '\n\nTotal: ' + fmt(sub) + '\n\nMohon info ketersediaan dan cara pembayarannya. Terima kasih.';
        return 'https://wa.me/' + wn + '?text=' + encodeURIComponent(msg);
    }

    function update() {
        if (!globalBtn) return;
        const allItems   = [...document.querySelectorAll('.item-check')];
        const checkedAll = allItems.filter(i => i.checked);
        const storeItems = {}, storeChecked = {};
        allItems.forEach(i => { (storeItems[i.dataset.store] ||= []).push(i); });
        checkedAll.forEach(i => { (storeChecked[i.dataset.store] ||= []).push(i); });

        let grand = 0; const selStores = new Set(); const phoneOk = {};
        document.querySelectorAll('.store-check').forEach(sc => {
            const sid = sc.dataset.store;
            const items = storeItems[sid] || []; const ch = storeChecked[sid] || [];
            sc.checked = (ch.length === items.length && items.length > 0);
            sc.indeterminate = (ch.length > 0 && ch.length < items.length);
            let sub = 0; ch.forEach(c => sub += num(c.dataset.price)); grand += sub;
            phoneOk[sid] = !!toWa(sc.dataset.phone);
            if (ch.length > 0) selStores.add(sid);
            set('.store-subtotal[data-store="'   + sid + '"]', fmt(sub));
            set('.store-foot-total[data-store="' + sid + '"]', fmt(sub));
            set('.store-sel-count[data-store="'  + sid + '"]', ch.length);
            const btn = document.querySelector('.store-wa-btn[data-store="' + sid + '"]');
            if (btn) btn.disabled = (ch.length === 0 || !phoneOk[sid]);
        });

        set('#sum-count',  checkedAll.length);
        set('#sum-total',  fmt(grand));
        set('#sum-grand',  fmt(grand));
        set('#sum-stores', selStores.size + ' toko');
        const note = document.getElementById('multi-store-note');
        if (note) note.classList.toggle('hidden', selStores.size <= 1);

        let disabled = (checkedAll.length === 0);
        if (!disabled && selStores.size === 1) { const sid = [...selStores][0]; if (!phoneOk[sid]) disabled = true; }
        globalBtn.disabled = disabled;
    }

    document.querySelectorAll('.item-check').forEach(c => c.addEventListener('change', update));
    document.querySelectorAll('.store-check').forEach(sc => sc.addEventListener('change', function () {
        const v = this.checked;
        document.querySelectorAll('.item-check[data-store="' + this.dataset.store + '"]').forEach(i => i.checked = v);
        update();
    }));
    document.querySelectorAll('.store-wa-btn').forEach(b => b.addEventListener('click', function () {
        const sid = this.dataset.store;
        const ch = [...document.querySelectorAll('.item-check[data-store="' + sid + '"]:checked')];
        if (!ch.length) return;
        const url = buildWa(sid, ch); if (url) window.open(url, '_blank');
    }));
    if (globalBtn) {
        globalBtn.addEventListener('click', function () {
            const checkedAll = [...document.querySelectorAll('.item-check:checked')];
            if (!checkedAll.length) return;
            const byStore = {};
            checkedAll.forEach(c => { (byStore[c.dataset.store] ||= []).push(c); });
            const sids = Object.keys(byStore);
            if (sids.length > 1) {
                alert('Keranjang pilihan Anda berisi produk dari ' + sids.length + ' toko berbeda.\n\nPembayaran via WhatsApp hanya bisa dilakukan ke satu penjual per checkout. Silakan centang produk dari satu toko saja, atau gunakan tombol "Lanjut via WhatsApp" di masing-masing kartu toko.');
                return;
            }
            const url = buildWa(sids[0], byStore[sids[0]]); if (url) window.open(url, '_blank');
        });
    }

    const clearBtn = document.getElementById('clear-cart-btn');
    const clearModal = document.getElementById('clear-cart-modal');
    if (clearBtn && clearModal) {
        const openClear  = () => { clearModal.classList.remove('hidden'); clearModal.classList.add('flex'); document.body.style.overflow = 'hidden'; if (window.lucide) lucide.createIcons(); };
        const closeClear = () => { clearModal.classList.add('hidden'); clearModal.classList.remove('flex'); document.body.style.overflow = ''; };
        clearBtn.addEventListener('click', openClear);
        clearModal.querySelectorAll('[data-clear-close]').forEach(el => el.addEventListener('click', closeClear));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !clearModal.classList.contains('hidden')) closeClear(); });
    }

    update();
});
</script>
@endsection