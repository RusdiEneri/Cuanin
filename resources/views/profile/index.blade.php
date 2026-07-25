@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('home') }}" 
           class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Profil Saya</h1>
            <p class="text-gray-500">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>
    </div>

    <!-- Error Validation (hanya bag DEFAULT = form update; bag 'delete' TIDAK ikut) -->
    @if($errors->default->any())
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl border border-red-100">
            <div class="flex items-center gap-2 mb-2 font-semibold">
                <i data-lucide="alert-circle" class="w-5 h-5"></i> Terdapat kesalahan input:
            </div>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach($errors->default->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Sidebar: Info User & Status Penjual -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl border border-border-color shadow-sm p-6 text-center sticky top-24">
                <div class="w-28 h-28 mx-auto mb-4 relative">
                    <div class="w-full h-full rounded-full overflow-hidden border-4 border-primary/10 shadow-md bg-blue-50">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                alt="{{ $user->name }}" 
                                class="w-full h-full object-cover"
                                style="aspect-ratio: 1 / 1;">
                        @else
                            <div class="w-full h-full rounded-full flex items-center justify-center text-primary font-bold text-4xl bg-blue-100" style="aspect-ratio: 1 / 1;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 mb-4 break-all">{{ $user->email }}</p>
                
                @if($user->role === 'penjual')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i> Penjual Aktif
                    </span>
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <a href="{{ route('seller.dashboard') }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-primary text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Buka Dashboard
                        </a>
                    </div>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i> Pembeli
                    </span>
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-3">Ingin menjual barang bekas Anda?</p>
                        <form action="{{ route('profile.becomeSeller') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 bg-secondary text-dark font-bold rounded-xl hover:bg-yellow-500 transition shadow-sm text-sm flex items-center justify-center gap-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Mulai Jadi Penjual
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Form -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" id="profile-form">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Pribadi -->
                    <section>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <i data-lucide="user-cog" class="w-5 h-5 text-primary"></i> Informasi Pribadi
                        </h3>
                        <div class="space-y-6">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Profil</label>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                    <div class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-primary/10 bg-blue-50 flex-shrink-0 shadow-sm" id="avatar-preview-container">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full rounded-full flex items-center justify-center text-primary font-bold text-3xl bg-blue-100">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 space-y-3">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <label for="avatar-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                                                <i data-lucide="camera" class="w-4 h-4"></i> Ganti Foto
                                            </label>
                                            <input type="file" name="avatar" id="avatar-input" accept="image/png, image/jpeg, image/webp" class="hidden">
                                            
                                            @if($user->avatar)
                                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-danger hover:text-red-700 font-medium transition select-none" id="remove-avatar-label">
                                                    <input type="checkbox" name="remove_avatar" value="1" class="w-4 h-4 text-danger border-gray-300 rounded focus:ring-danger" id="remove-avatar-checkbox">
                                                    Hapus Foto
                                                </label>
                                            @endif
                                        </div>
                                        
                                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                                            <i data-lucide="info" class="w-3.5 h-3.5 text-gray-400"></i>
                                            Format: JPG, PNG, WEBP. Maksimal 2MB.
                                        </div>
                                        
                                        <p class="text-xs text-danger font-medium hidden items-center gap-1.5" id="avatar-error">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                            <span id="avatar-error-text"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="08123456789" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="address" rows="3" placeholder="Jl. Contoh No. 123, Kecamatan, Kota" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Keamanan Akun -->
                    <section>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-primary"></i> Keamanan Akun
                        </h3>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-500 bg-blue-50 p-3 rounded-lg border border-blue-100 flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0 text-primary"></i> 
                                <span>Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</span>
                            </p>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                                <input type="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi baru" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>
                        </div>
                    </section>

                    {{-- ✅ ACTION BUTTONS — lebar DIJAMIN IDENTIK via grid 2 kolom (1fr 1fr).
                        Grid membagi ruang sama rata, TIDAK terpengaruh panjang teks.
                        - Mobile (grid-cols-1): tombol menumpuk full-width (otomatis sama).
                        `order-first` pada Simpan → Simpan tampil di ATAS (primary first).
                        - Desktop (sm:grid-cols-2): 2 kolom sama lebar; Hapus kiri, Simpan kanan.
                        `sm:max-w-lg sm:ml-auto` → blok tidak full-width, rata kanan. --}}
                    <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-gray-100 sm:max-w-lg sm:ml-auto">

                        {{-- Hapus Akun (MERAH) --}}
                        <button type="button" id="delete-account-btn"
                            class="w-full whitespace-nowrap bg-red-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-600 active:bg-red-700 transition shadow-md shadow-red-500/20 flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                            Hapus Akun
                        </button>

                        <button type="submit"
                            class="w-full lg:w-64 bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 🌟 MODAL KONFIRMASI HAPUS AKUN 🌟 -->
<div id="delete-account-modal"
     role="dialog" aria-modal="true" aria-labelledby="delete-modal-title"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    
    <div class="absolute inset-0" data-modal-close></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        
        <button type="button" data-modal-close aria-label="Tutup"
            class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition z-10">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="p-6 sm:p-8 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-red-600"></i>
            </div>

            <h3 id="delete-modal-title" class="text-xl font-bold text-gray-900 mb-2">Hapus Akun?</h3>
            <p class="text-sm text-gray-500 leading-relaxed mb-2">
                Apakah Anda benar-benar ingin menghapus akun <span class="font-semibold text-gray-700">{{ $user->name }}</span>?
            </p>
            <p class="text-sm text-red-600 font-medium bg-red-50 border border-red-100 rounded-lg p-3">
                Tindakan ini <strong>tidak dapat dibatalkan</strong>. Seluruh data profil, produk, dan riwayat Anda akan dihapus permanen.
            </p>
        </div>

        {{-- Form DELETE terpisah --}}
        <form action="{{ route('profile.destroy') }}" method="POST" class="px-6 sm:px-8 pb-6 sm:pb-8">
            @csrf
            @method('DELETE')

            {{-- ✅ [FIX 2] INPUT PASSWORD KONFIRMASI (wajib, sesuai validasi controller) --}}
            <div class="mb-5 text-left">
                <label for="delete-password" class="block text-sm font-medium text-gray-700 mb-1">
                    Kata Sandi Saat Ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input id="delete-password" name="password" type="password" required autocomplete="current-password"
                        class="appearance-none block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition @if($errors->delete->has('password')) border-red-400 ring-1 ring-red-400 @endif"
                        placeholder="Masukkan kata sandi Anda">
                </div>
                {{-- Error password tampil DI DALAM modal (named bag 'delete') --}}
                @if($errors->delete->has('password'))
                    <p class="text-xs text-danger mt-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        {{ $errors->delete->first('password') }}
                    </p>
                @endif
                <p class="text-[11px] text-gray-400 mt-1.5">Diperlukan untuk mengonfirmasi bahwa ini benar-benar Anda.</p>
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-3">
                <button type="button" data-modal-close
                    class="flex-1 px-5 py-3 rounded-xl font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-5 py-3 rounded-xl font-semibold text-white bg-red-500 hover:bg-red-600 active:bg-red-700 transition shadow-md shadow-red-500/20 flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🌟 JAVASCRIPT 🌟 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarError = document.getElementById('avatar-error');
    const avatarErrorText = document.getElementById('avatar-error-text');
    const previewContainer = document.getElementById('avatar-preview-container');
    const removeCheckbox = document.getElementById('remove-avatar-checkbox');
    const originalPreviewHTML = previewContainer.innerHTML;

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            avatarError.classList.add('hidden');
            avatarError.classList.remove('flex');
            avatarErrorText.textContent = '';

            if (file) {
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    avatarErrorText.textContent = `Ukuran file terlalu besar! Maksimal 2MB. (Ukuran file Anda: ${(file.size / (1024*1024)).toFixed(2)} MB)`;
                    avatarError.classList.remove('hidden');
                    avatarError.classList.add('flex');
                    e.target.value = '';
                    previewContainer.innerHTML = originalPreviewHTML;
                    if (removeCheckbox) removeCheckbox.checked = false;
                    if (window.lucide) lucide.createIcons();
                    return;
                }
                const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    avatarErrorText.textContent = 'Format file tidak valid! Gunakan JPG, PNG, atau WEBP.';
                    avatarError.classList.remove('hidden');
                    avatarError.classList.add('flex');
                    e.target.value = '';
                    previewContainer.innerHTML = originalPreviewHTML;
                    if (removeCheckbox) removeCheckbox.checked = false;
                    if (window.lucide) lucide.createIcons();
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewContainer.innerHTML = `<img src="${event.target.result}" alt="Preview Avatar" class="w-full h-full object-cover">`;
                }
                reader.readAsDataURL(file);
                if (removeCheckbox) removeCheckbox.checked = false;
            }
        });
    }

    if (removeCheckbox) {
        removeCheckbox.addEventListener('change', function(e) {
            avatarError.classList.add('hidden');
            if (e.target.checked) {
                const initial = "{{ strtoupper(substr($user->name, 0, 1)) }}";
                previewContainer.innerHTML = `<div class="w-full h-full rounded-full flex items-center justify-center text-primary font-bold text-3xl bg-blue-100">${initial}</div>`;
                if (avatarInput) avatarInput.value = '';
            } else {
                previewContainer.innerHTML = originalPreviewHTML;
            }
        });
    }

    /* =========================================================
       🌟 LOGIKA MODAL KONFIRMASI HAPUS AKUN
       ========================================================= */
    const deleteBtn = document.getElementById('delete-account-btn');
    const modal     = document.getElementById('delete-account-modal');

    if (deleteBtn && modal) {
        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            if (window.lucide) lucide.createIcons();
            // Fokus ke kolom password biar user langsung bisa ngetik
            const pw = modal.querySelector('input[name="password"]');
            if (pw) setTimeout(() => pw.focus(), 50);
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        deleteBtn.addEventListener('click', openModal);

        modal.querySelectorAll('[data-modal-close]').forEach(el => {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });

        // ✅ [FIX 3] AUTO-BUKA modal kalau ada error validasi hapus akun
        //    (named bag 'delete' -> mis. password salah / kosong)
        @if($errors->delete->any())
            openModal();
        @endif
    }
});
</script>
@endsection