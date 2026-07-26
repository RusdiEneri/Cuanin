@extends('layouts.app')

@section('content')
<style>
    /* ============ SCENE 3D ============ */
    @keyframes floatScene {
        0%,100% { transform: translateY(0px); }
        50%      { transform: translateY(-12px); }
    }
    @keyframes glowPulse {
        0%,100% { opacity: .45; transform: scale(1); }
        50%      { opacity: .75; transform: scale(1.1); }
    }
    @keyframes spinDollar {
        0%   { transform: rotateY(0deg) rotateX(12deg); }
        100% { transform: rotateY(360deg) rotateX(12deg); }
    }
    @keyframes orbitSmall {
        from { transform: rotateX(70deg) rotateZ(0deg); }
        to   { transform: rotateX(70deg) rotateZ(360deg); }
    }
    @keyframes floatBadge {
        0%,100% { transform: translateY(0) rotate(-3deg); }
        50%      { transform: translateY(-9px) rotate(-3deg); }
    }
    @keyframes floatBadge2 {
        0%,100% { transform: translateY(0) rotate(4deg); }
        50%      { transform: translateY(-11px) rotate(4deg); }
    }
    @keyframes floatBadge3 {
        0%,100% { transform: translateY(0) rotate(-6deg); }
        50%      { transform: translateY(-7px) rotate(-6deg); }
    }

    .scene-float { animation: floatScene 4.5s ease-in-out infinite; }

    /* Double rings below coin */
    .ring-outer {
        position: absolute;
        width: 180px; height: 180px;
        border-radius: 9999px;
        border: 1.5px solid rgba(255,255,255,.28);
        animation: orbitSmall 7s linear infinite;
        top: 50%; left: 50%;
        margin-top: -90px; margin-left: -90px;
    }
    .ring-inner {
        position: absolute;
        width: 125px; height: 125px;
        border-radius: 9999px;
        border: 1.5px solid rgba(255,255,255,.18);
        animation: orbitSmall 5s linear infinite reverse;
        top: 50%; left: 50%;
        margin-top: -62.5px; margin-left: -62.5px;
    }
    .ring-outer::before {
        content: '';
        position: absolute;
        width: 9px; height: 9px;
        border-radius: 9999px;
        background: #fde047;
        box-shadow: 0 0 8px 3px rgba(253,224,71,.65);
        top: -5px; left: 50%; margin-left: -4px;
    }
    .ring-outer::after {
        content: '';
        position: absolute;
        width: 6px; height: 6px;
        border-radius: 9999px;
        background: rgba(255,255,255,.7);
        bottom: -3px; left: 30%;
    }
    .ring-inner::before {
        content: '';
        position: absolute;
        width: 6px; height: 6px;
        border-radius: 9999px;
        background: rgba(255,200,50,.8);
        box-shadow: 0 0 5px 2px rgba(253,224,71,.5);
        bottom: -3px; right: 20%;
    }

    /* ---- center coin 3D (larger) ---- */
    .coin-3d-stage {
        --ct: 15px;
        width: 130px; height: 130px;
        perspective: 900px;
        perspective-origin: 50% 50%;
    }
    .coin-3d-inner {
        position: relative; width: 100%; height: 100%;
        transform-style: preserve-3d;
        animation: spinDollar 3.6s linear infinite;
    }
    .coin-face {
        position: absolute; inset: 0;
        border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        backface-visibility: hidden;
        background: radial-gradient(circle at 32% 28%,
            #fff7cc 0%, #fde047 22%, #facc15 45%, #eab308 70%, #a16207 100%);
        border: 3px solid #b45309;
        box-shadow:
            inset 0 0 0 4px rgba(255,255,255,.3),
            inset 0 6px 10px rgba(255,255,255,.5),
            inset 0 -6px 10px rgba(120,53,15,.4),
            0 8px 24px rgba(180,83,9,.4);
    }
    .coin-face::before {
        content: '';
        position: absolute; inset: 8px;
        border-radius: 9999px;
        border: 2px dashed rgba(120,53,15,.4);
    }
    .coin-face.front { transform: translateZ(calc(var(--ct)/2 + 1px)); }
    .coin-face.back  { transform: rotateY(180deg) translateZ(calc(var(--ct)/2 + 1px)); }
    .coin-edge-layer {
        position: absolute; inset: 0;
        border-radius: 9999px;
        background: linear-gradient(180deg,
            #fef08a 0%, #eab308 20%, #a16207 47%,
            #713f12 52%, #a16207 80%, #fef08a 100%);
    }
    .coin-emblem { color: #78350f; filter: drop-shadow(0 1px 0 rgba(255,255,255,.5)); }

    /* ---- floating badges ---- */
    .badge-float {
        position: absolute;
        background: rgba(255,255,255,.13);
        border: 1px solid rgba(255,255,255,.25);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        color: white;
        font-size: 11.5px;
        font-weight: 600;
        padding: 7px 12px;
        display: flex; align-items: center; gap: 7px;
        white-space: nowrap;
        letter-spacing: .01em;
    }
    .badge-float .dot { width: 7px; height: 7px; border-radius: 9999px; flex-shrink: 0; }
    .badge-inline {
        font-size: 10.5px;
        padding: 5px 9px;
        gap: 5px;
    }
    .bf-1 { top: 12%; left: 18%;  animation: floatBadge  3.8s ease-in-out infinite; }
    .bf-2 { top: 12%; right: 18%; animation: floatBadge2 4.5s ease-in-out infinite .5s; }
    /* bf-3 and bf-4 are now inline beside coin — no absolute positioning */

    /* ---- glow orbs ---- */
    .glow-orb {
        position: absolute;
        border-radius: 9999px;
        filter: blur(60px);
        pointer-events: none;
        animation: glowPulse 4s ease-in-out infinite;
    }

    /* ---- particles ---- */
    @keyframes particle {
        0%   { opacity: 0; transform: translateY(0) scale(0); }
        30%  { opacity: 1; }
        100% { opacity: 0; transform: translateY(-48px) scale(.5); }
    }
    .particle {
        position: absolute;
        width: 5px; height: 5px;
        border-radius: 9999px;
        background: #fde047;
        box-shadow: 0 0 6px 2px rgba(253,224,71,.6);
    }
    .p1 { animation: particle 2.4s ease-in-out infinite;      top: 42%; left: 47%; }
    .p2 { animation: particle 2.9s ease-in-out infinite .6s;  top: 48%; left: 55%; }
    .p3 { animation: particle 2.1s ease-in-out infinite 1.1s; top: 38%; left: 53%; }
    .p4 { animation: particle 3.2s ease-in-out infinite .2s;  top: 51%; left: 44%; width:3px; height:3px; }
    .p5 { animation: particle 2.6s ease-in-out infinite .9s;  top: 45%; left: 58%; width:4px; height:4px; background:#fff; box-shadow:none; }
</style>

@php $coinLayers = 30; @endphp

<div class="min-h-[calc(100vh-180px)] flex items-center justify-center px-4 sm:px-6 py-4 md:py-6">

    {{-- ============ CARD ============ --}}
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-[1fr_1.1fr] bg-white rounded-3xl shadow-2xl shadow-blue-900/20 border border-gray-100 overflow-hidden">

        {{-- -------- KOLOM 1 : FORM -------- --}}
        <div class="relative flex flex-col justify-center overflow-hidden bg-white px-6 py-6 sm:px-8 xl:px-10">

            <!-- Decoration blur -->
            <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-primary opacity-[0.07] blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 h-28 w-28 rounded-full bg-yellow-400 opacity-[0.07] blur-3xl"></div>

            <div class="relative z-10">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h2>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-50 text-danger p-3 rounded-xl text-sm border border-red-100 flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form class="space-y-3.5" action="{{ route('register.post') }}" method="POST">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="name" name="name" type="text" required
                                class="appearance-none block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                placeholder="Budi Setiawan" value="{{ old('name') }}">
                        </div>
                        @error('name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                placeholder="contoh@email.com" value="{{ old('email') }}">
                        </div>
                        @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- No. WhatsApp --}}
                    <div>
                        <label for="phone_number" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">No. WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input id="phone_number" name="phone_number" type="text" required
                                class="appearance-none block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                placeholder="081234567890" value="{{ old('phone_number') }}">
                        </div>
                        @error('phone_number')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                placeholder="Min. 8 karakter">
                            <button type="button" onclick="togglePassword('password','eye-password','eyeOff-password')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                <i data-lucide="eye" id="eye-password" class="w-4 h-4 pointer-events-none"></i>
                                <i data-lucide="eye-off" id="eyeOff-password" class="w-4 h-4 hidden pointer-events-none"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                placeholder="Ulangi password">
                            <button type="button" onclick="togglePassword('password_confirmation','eye-confirm','eyeOff-confirm')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                <i data-lucide="eye" id="eye-confirm" class="w-4 h-4 pointer-events-none"></i>
                                <i data-lucide="eye-off" id="eyeOff-confirm" class="w-4 h-4 hidden pointer-events-none"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-1 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-blue-700 transition shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Buat Akun
                    </button>
                </form>

                <div class="mt-4 text-center text-sm text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-blue-700 transition">Masuk di sini</a>
                </div>
            </div>
        </div>

        {{-- -------- KOLOM 2 : PANEL ANIMASI 3D -------- --}}
        <div class="relative hidden lg:flex flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[#1a3de4] via-primary to-blue-900 text-white p-8 min-h-[460px]">

            <!-- Glow orbs (decorative only, no layout impact) -->
            <div class="glow-orb w-64 h-64 bg-yellow-400/20 -top-16 -right-16" style="animation-delay:.3s"></div>
            <div class="glow-orb w-56 h-56 bg-blue-300/20 -bottom-20 -left-10" style="animation-delay:1.2s"></div>

            <!-- Floating particles -->
            <div class="particle p1"></div>
            <div class="particle p2"></div>
            <div class="particle p3"></div>
            <div class="particle p4"></div>
            <div class="particle p5"></div>

            <!-- ALL content in one flow column, centered -->
            <div class="relative z-10 flex flex-col items-center text-center gap-3 w-full mt-8">

                <!-- Row 1: Top badges — wider apart -->
                <div class="flex items-center justify-center gap-16 w-full">
                    <div class="badge-float" style="position:relative; top:auto; left:auto; right:auto; bottom:auto; animation: floatBadge 3.8s ease-in-out infinite;">
                        <div class="dot bg-green-400"></div>
                        <span>Gratis Daftar</span>
                    </div>
                    <div class="badge-float" style="position:relative; top:auto; left:auto; right:auto; bottom:auto; animation: floatBadge2 4.5s ease-in-out infinite .5s;">
                        <div class="dot bg-yellow-400"></div>
                        <span>100% Aman</span>
                    </div>
                </div>

                <!-- Row 2: Coin + side badges closer to center, raised slightly -->
                <div class="scene-float relative flex items-start gap-3">

                    <!-- Left badge -->
                    <div class="badge-float" style="position:relative; top:auto; left:auto; right:auto; bottom:auto; margin-top:3rem; animation: floatBadge3 4.1s ease-in-out infinite .3s;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.998 2C6.477 2 2 6.477 2 11.998c0 1.873.49 3.63 1.345 5.156L2 22l4.954-1.302A9.956 9.956 0 0011.998 22C17.522 22 22 17.523 22 12c0-5.523-4.478-10.002-10.002-10zm0 18.214a8.209 8.209 0 01-4.188-1.148l-.3-.178-3.1.812.826-3.02-.196-.31A8.2 8.2 0 013.8 12c0-4.53 3.685-8.212 8.198-8.212 4.513 0 8.2 3.682 8.2 8.212 0 4.528-3.687 8.214-8.2 8.214z"/></svg>
                        <span>Via WhatsApp</span>
                    </div>

                    <!-- Coin + rings stacked, pushed down -->
                    <div class="relative flex flex-col items-center pt-12">
                        <!-- Glow halo -->
                        <div class="absolute inset-0 -m-6 rounded-full bg-yellow-400/20 blur-3xl pointer-events-none"></div>

                        <!-- Coin 3D -->
                        <div style="perspective:900px; perspective-origin:50% 50%; position:relative; z-index:2;">
                            <div class="coin-3d-stage">
                                <div class="coin-3d-inner" style="transform-style:preserve-3d;">
                                    <div class="coin-face front">
                                        <i data-lucide="dollar-sign" class="coin-emblem w-14 h-14"></i>
                                    </div>
                                    <div class="coin-face back">
                                        <i data-lucide="dollar-sign" class="coin-emblem w-14 h-14"></i>
                                    </div>
                                    @for ($i = 0; $i < $coinLayers; $i++)
                                        <div class="coin-edge-layer" style="transform: translateZ(calc(({{ $i }} / {{ $coinLayers - 1 }} - 0.5) * var(--ct))); position:absolute; inset:0;"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Double rings below coin -->
                        <div class="relative mt-1" style="width:180px; height:40px;">
                            <div class="ring-outer" style="position:absolute; top:50%; left:50%; margin-top:-90px; margin-left:-90px;"></div>
                            <div class="ring-inner" style="position:absolute; top:50%; left:50%; margin-top:-62.5px; margin-left:-62.5px;"></div>
                        </div>
                    </div>

                    <!-- Right badge -->
                    <div class="badge-float" style="position:relative; top:auto; left:auto; right:auto; bottom:auto; margin-top:3rem; animation: floatBadge2 3.5s ease-in-out infinite .9s;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Terverifikasi</span>
                    </div>
                </div>

                <!-- Row 3: Title -->
                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight drop-shadow mt-10">
                    Mulai Perjalanan<br><span class="text-yellow-300">Cuanmu</span> Sekarang!
                </h2>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function togglePassword(inputId, eyeId, eyeOffId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById(eyeId);
        const eyeOffIcon = document.getElementById(eyeOffId);
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush