@extends('layouts.app')

@section('title', 'Login')

@section('content')

{{-- [FIX] Style + font. @import WAJIB baris paling atas.
     Kalau layouts.app punya @stack('styles'), lebih rapi pindahkan
     seluruh blok <style> ini ke @push('styles'). --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    /* [FIX] Jaminan Poppins untuk seluruh card (jika layout belum set global) */
    .font-poppins{
        font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }

    /* Panggung koin: kasih perspective biar efek 3D-nya kebaca */
    .coin-stage{ width: 88px; height: 88px; perspective: 800px; }
    .coin{
        position: relative; width: 100%; height: 100%;
        transform-style: preserve-3d;
        animation: cuanin-coin-spin 3.2s linear infinite;
    }
    .coin-face{
        position: absolute; inset: 0; border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        backface-visibility: hidden; -webkit-backface-visibility: hidden;
        border: 3px solid #b45309;
        background: radial-gradient(circle at 32% 28%,
            #fff7cc 0%, #fde047 22%, #facc15 45%, #eab308 70%, #a16207 100%);
        box-shadow:
            inset 0 0 0 4px rgba(255,255,255,.35),
            inset 0  6px 10px rgba(255,255,255,.55),
            inset 0 -6px 10px rgba(120,53,15,.45),
            0 8px 18px rgba(180,83,9,.35);
    }
    .coin-face::before{
        content: ""; position: absolute; inset: 8px;
        border-radius: 9999px; border: 2px dashed rgba(120,53,15,.45);
    }
    .coin-face.back{ transform: rotateY(180deg); }
    .coin-emblem{ color: #78350f; filter: drop-shadow(0 1px 0 rgba(255,255,255,.5)); }
    @keyframes cuanin-coin-spin{
        0%   { transform: rotateY(0deg)   rotateX(8deg); }
        100% { transform: rotateY(360deg) rotateX(8deg); }
    }
    @media (prefers-reduced-motion: reduce){
        .coin{ animation: none; transform: rotateY(-20deg) rotateX(8deg); }
    }
</style>

{{-- [FIX] Wrapper: HAPUS min-h-screen & items-center (penyebab space kosong).
     Ganti jadi padding vertikal wajar. bg-background dipertahankan. --}}
<div class="bg-background px-4 py-8 sm:px-6 sm:py-12 lg:px-8">

    {{-- [FIX] Card: tambah mx-auto (center horizontal tanpa flex)
         + font-poppins (seluruh teks card pakai Poppins). --}}
    <div class="font-poppins mx-auto w-full max-w-md lg:max-w-5xl grid lg:grid-cols-2 bg-white rounded-3xl shadow-xl shadow-blue-900/10 border border-gray-100 overflow-hidden">

        {{-- ══ KOLOM KIRI : PANEL BRANDING (desktop only) ══ --}}
        <div class="hidden lg:flex relative flex-col justify-between p-10 xl:p-12 bg-primary text-white overflow-hidden">

            <div class="absolute inset-0 opacity-60"
                 style="background-image: radial-gradient(rgba(255,255,255,0.12) 1px, transparent 1px); background-size: 22px 22px;"></div>

            <div class="absolute -top-20 -right-16 w-72 h-72 bg-secondary rounded-full mix-blend-overlay filter blur-3xl opacity-30"></div>
            <div class="absolute -bottom-24 -left-16 w-72 h-72 bg-blue-950 rounded-full filter blur-3xl opacity-40"></div>
            <div class="absolute top-1/3 -left-10 w-40 h-40 bg-secondary rounded-full filter blur-3xl opacity-20"></div>

            <div class="relative z-10 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center shadow-lg shadow-blue-900/30">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-primary"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight">Cuanin<span class="text-secondary">.</span></span>
            </div>

            <div class="relative z-10 my-10">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-medium text-secondary backdrop-blur-sm mb-5">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    Marketplace Barang Bekas #1
                </span>
                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight mb-4">
                    Jual Beli Barang Bekas, <span class="text-secondary">Jadi Cuan!</span>
                </h2>
                <p class="text-blue-100/80 text-sm leading-relaxed mb-8 max-w-sm">
                    Bergabunglah dengan ribuan pengguna yang sudah mengubah barang tak terpakai menjadi penghasilan.
                </p>

                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
                            <i data-lucide="tag" class="w-4 h-4 text-secondary"></i>
                        </span>
                        <span class="text-sm text-blue-50">Jual barang bekas dengan mudah & cepat</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
                            <i data-lucide="search" class="w-4 h-4 text-secondary"></i>
                        </span>
                        <span class="text-sm text-blue-50">Temukan harga terbaik untuk incaranmu</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4 text-secondary"></i>
                        </span>
                        <span class="text-sm text-blue-50">Transaksi aman & terpercaya</span>
                    </li>
                </ul>
            </div>

            <div class="relative z-10">
                <p class="text-sm text-blue-100/80 mb-3">Belum punya akun?</p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-secondary text-primary font-semibold text-sm shadow-lg shadow-blue-900/30 hover:bg-yellow-300 transition transform hover:-translate-y-0.5 active:translate-y-0">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Daftar Sekarang
                </a>
            </div>
        </div>

        {{-- ══ KOLOM KANAN : FORM LOGIN ══ --}}
        <div class="relative flex flex-col justify-center p-8 sm:p-10 xl:p-12">

            <div class="absolute top-0 right-0 w-32 h-32 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-10 -mr-10 -mt-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-10 -mb-10 pointer-events-none"></div>

            <div class="relative z-10">

                {{-- HEADER MOBILE : KOIN EMAS BERPUTAR --}}
                <div class="lg:hidden flex flex-col items-center mb-6">
                    <div class="relative mb-3">
                        <div class="absolute inset-0 -m-3 rounded-full bg-secondary opacity-40 blur-xl pointer-events-none"></div>
                        <div class="coin-stage relative" aria-hidden="true">
                            <div class="coin">
                                <div class="coin-face front">
                                    <i data-lucide="dollar-sign" class="coin-emblem w-9 h-9"></i>
                                </div>
                                <div class="coin-face back">
                                    <i data-lucide="dollar-sign" class="coin-emblem w-9 h-9"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-500 text-sm">Masuk untuk melanjutkan aktivitas jual beli kamu.</p>
                </div>

                @if(session('error'))
                <div class="mt-4 bg-red-50 text-danger p-3 rounded-xl flex items-center gap-2 text-sm border border-red-100" role="alert">
                    <i data-lucide="circle-alert" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="mt-4 bg-green-50 text-green-700 p-3 rounded-xl flex items-center gap-2 text-sm border border-green-100" role="alert">
                    <i data-lucide="circle-check" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('status'))
                <div class="mt-4 bg-blue-50 text-blue-700 p-3 rounded-xl flex items-center gap-2 text-sm border border-blue-100" role="alert">
                    <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                <form class="mt-8 space-y-5" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="space-y-4">

                        {{-- LOGIN FIELD --}}
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                                Email / Nomor HP
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="at-sign" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input
                                    id="login" name="login" type="text"
                                    autocomplete="username" required autofocus
                                    class="appearance-none block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('login') border-danger ring-1 ring-danger @enderror"
                                    placeholder="rusdi@example.com"
                                    value="{{ old('login') }}"
                                    aria-describedby="login-error"
                                >
                            </div>
                            @error('login')
                                <p id="login-error" class="text-danger text-xs mt-1 flex items-center gap-1" role="alert">
                                    <i data-lucide="circle-alert" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- PASSWORD FIELD --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                @if(Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-blue-700 transition">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input
                                    id="password" name="password" type="password"
                                    autocomplete="current-password" required
                                    class="appearance-none block w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('password') border-danger ring-1 ring-danger @enderror"
                                    placeholder="••••••••"
                                    aria-describedby="password-error"
                                >
                                <button
                                    type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition cursor-pointer"
                                    aria-label="Toggle password visibility"
                                >
                                    <i data-lucide="eye" class="w-4 h-4" id="eye-icon"></i>
                                    <i data-lucide="eye-off" class="w-4 h-4 hidden" id="eye-off-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p id="password-error" class="text-danger text-xs mt-1 flex items-center gap-1" role="alert">
                                    <i data-lucide="circle-alert" class="w-3 h-3"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input
                            id="remember-me" name="remember" type="checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        >
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-blue-500/20 text-sm font-semibold text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer"
                        >
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-primary hover:text-blue-700 transition">Daftar sekarang</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
@endpush