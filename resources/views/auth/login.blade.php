@extends('layouts.app')

@section('title', 'Login')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    .font-poppins{
        font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }

    /* ═══════════════════ KOIN 3D ═══════════════════ */
    /* Panggung koin: perspective biar efek 3D kebaca.
       --coin-t = KETEBALAN koin (atur di sini). */
    .coin-stage{
        --coin-t: 12px;                 /* ketebalan mobile */
        width: 88px; height: 88px;
        perspective: 900px;
        perspective-origin: 50% 50%;
    }
    .coin-stage--lg{ --coin-t: 16px; width: 112px; height: 112px; }   /* desktop */
    @media (min-width: 1280px){ .coin-stage--lg{ --coin-t: 18px; width: 128px; height: 128px; } }

    /* Wrapper melayang (terpisah dari spin supaya bisa digabung) */
    .coin-float{ animation: cuanin-coin-float 3.8s ease-in-out infinite; }
    @keyframes cuanin-coin-float{
        0%,100%{ transform: translateY(0); }
        50%    { transform: translateY(-9px); }
    }

    /* Benda koin: preserve-3d WAJIB supaya anak-anaknya hidup di ruang 3D */
    .coin{
        position: relative; width: 100%; height: 100%;
        transform-style: preserve-3d;
        will-change: transform;
        animation: cuanin-coin-spin 3.4s linear infinite;
    }

    /* Muka & belakang koin (tutup silinder) */
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
    /* [FIX 3D] dorong muka & belakang ke ujung ketebalan */
    .coin-face.front{ transform: translateZ(calc(var(--coin-t) / 2 + .6px)); }
    .coin-face.back { transform: rotateY(180deg) translateZ(calc(var(--coin-t) / 2 + .6px)); }

    .coin-emblem{ color: #78350f; filter: drop-shadow(0 1px 0 rgba(255,255,255,.5)); }

    /* [NEW 3D] DINDING / TEPI koin: tumpukan layer emas sepanjang sumbu Z.
       Ini yang bikin koin terlihat TEBAL (bukan kertas) saat miring. */
    .coin-edge{
        position: absolute; inset: 0; border-radius: 9999px;
        backface-visibility: visible;
        background: linear-gradient(180deg,
            #fef08a 0%, #eab308 20%, #a16207 47%,
            #713f12 52%, #a16207 80%, #fef08a 100%);
        box-shadow:
            inset 0  1px 1px rgba(255,255,255,.55),
            inset 0 -1px 2px rgba(0,0,0,.45);
    }

    /* Bayangan lantai (grounding) — bikin terasa mengambang */
    .coin-shadow{
        width: 64%; height: 12px; margin: 16px auto 0;
        border-radius: 50%;
        background: radial-gradient(ellipse at center, rgba(15,23,42,.30), rgba(15,23,42,0) 72%);
        filter: blur(2px);
        animation: cuanin-coin-shadow 3.8s ease-in-out infinite;
    }
    @keyframes cuanin-coin-shadow{
        0%,100%{ transform: scaleX(1);   opacity: .55; }
        50%    { transform: scaleX(.68); opacity: .28; }
    }

    /* Spin: tilt rotateX diperbesar biar 3D-nya kebaca */
    @keyframes cuanin-coin-spin{
        0%   { transform: rotateY(0deg)   rotateX(14deg); }
        100% { transform: rotateY(360deg) rotateX(14deg); }
    }

    @media (prefers-reduced-motion: reduce){
        .coin, .coin-float, .coin-shadow{ animation: none; }
        .coin{ transform: rotateY(-22deg) rotateX(14deg); }
    }
</style>

@php $coinLayers = 40; @endphp   {{-- jumlah layer tepi (makin banyak = makin mulus) --}}

<div class="bg-background px-4 py-8 sm:px-6 sm:py-12 lg:px-8">

    <div class="font-poppins mx-auto w-full max-w-md lg:max-w-5xl grid lg:grid-cols-2 bg-white rounded-3xl shadow-xl shadow-blue-900/10 border border-gray-100 overflow-hidden">

        {{-- ══ KOLOM KIRI : PANEL BRANDING (desktop only) ══ --}}
        <div class="hidden lg:flex relative flex-col items-center justify-center text-center p-10 xl:p-12 bg-primary text-white overflow-hidden">

            <div class="absolute inset-0 opacity-60"
                 style="background-image: radial-gradient(rgba(255,255,255,0.12) 1px, transparent 1px); background-size: 22px 22px;"></div>
            <div class="absolute -top-20 -right-16 w-72 h-72 bg-secondary rounded-full mix-blend-overlay filter blur-3xl opacity-30"></div>
            <div class="absolute -bottom-24 -left-16 w-72 h-72 bg-blue-950 rounded-full filter blur-3xl opacity-40"></div>
            <div class="absolute top-1/3 -left-10 w-40 h-40 bg-secondary rounded-full filter blur-3xl opacity-20"></div>

            <div class="relative z-10 flex flex-col items-center">

                {{-- KOIN 3D (desktop) --}}
                <div class="relative inline-block mb-6 xl:mb-8">
                    <div class="coin-float">
                        <div class="absolute inset-0 -m-4 rounded-full bg-secondary opacity-40 blur-2xl pointer-events-none"></div>
                        <div class="coin-stage coin-stage--lg relative" aria-hidden="true">
                            <div class="coin">
                                <div class="coin-face front">
                                    <i data-lucide="dollar-sign" class="coin-emblem w-12 h-12 xl:w-14 xl:h-14"></i>
                                </div>
                                <div class="coin-face back">
                                    <i data-lucide="dollar-sign" class="coin-emblem w-12 h-12 xl:w-14 xl:h-14"></i>
                                </div>
                                {{-- DINDING/TEPI koin --}}
                                @for ($i = 0; $i < $coinLayers; $i++)
                                    <div class="coin-edge" style="transform: translateZ(calc(({{ $i }} / {{ $coinLayers - 1 }} - 0.5) * var(--coin-t)))"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight max-w-xs xl:max-w-sm">
                    Jual Beli Barang Bekas, <span class="text-secondary">Jadi Cuan!</span>
                </h2>
            </div>
        </div>

        {{-- ══ KOLOM KANAN : FORM LOGIN ══ --}}
        <div class="relative flex flex-col justify-center p-8 sm:p-10 xl:p-12">

            <div class="absolute top-0 right-0 w-32 h-32 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-10 -mr-10 -mt-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-10 -mb-10 pointer-events-none"></div>

            <div class="relative z-10">

                {{-- HEADER MOBILE : KOIN 3D --}}
                <div class="lg:hidden flex flex-col items-center mb-6">
                    <div class="relative mb-1">
                        <div class="coin-float">
                            <div class="absolute inset-0 -m-3 rounded-full bg-secondary opacity-40 blur-xl pointer-events-none"></div>
                            <div class="coin-stage relative" aria-hidden="true">
                                <div class="coin">
                                    <div class="coin-face front">
                                        <i data-lucide="dollar-sign" class="coin-emblem w-9 h-9"></i>
                                    </div>
                                    <div class="coin-face back">
                                        <i data-lucide="dollar-sign" class="coin-emblem w-9 h-9"></i>
                                    </div>
                                    @for ($i = 0; $i < $coinLayers; $i++)
                                        <div class="coin-edge" style="transform: translateZ(calc(({{ $i }} / {{ $coinLayers - 1 }} - 0.5) * var(--coin-t)))"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="coin-shadow" aria-hidden="true"></div>
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