@extends('layouts.app')

@section('content')
{{-- ====== STYLE ANIMASI KOIN ====== --}}
<style>
    .coin-panel { perspective: 1000px; }

    @keyframes floatSpin {
        0%   { transform: translateY(0)     rotateY(0deg);   }
        50%  { transform: translateY(-22px) rotateY(180deg); }
        100% { transform: translateY(0)     rotateY(360deg); }
    }
    @keyframes floatY {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-14px); }
    }
    @keyframes pulseGlow {
        0%, 100% { opacity: .35; transform: scale(1); }
        50%      { opacity: .6;  transform: scale(1.08); }
    }

    .coin {
        position: absolute;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #92400e;
        background: linear-gradient(145deg, #fef3c7 0%, #fbbf24 45%, #f59e0b 100%);
        border: 3px solid #fef9c3;
        box-shadow: 0 12px 28px -6px rgba(245, 158, 11, .55),
                    inset 0 2px 5px rgba(255, 255, 255, .75),
                    inset 0 -3px 6px rgba(146, 64, 14, .35);
        transform-style: preserve-3d;
        will-change: transform;
        user-select: none;
    }
    .coin::after { /* garis tepi dalam biar mirip koin asli */
        content: "";
        position: absolute;
        inset: 6px;
        border-radius: 9999px;
        border: 2px dashed rgba(146, 64, 14, .35);
    }

    /* ukuran */
    .coin-xl { width: 9rem;   height: 9rem;   font-size: 3.5rem; border-width: 5px; position: relative; }
    .coin-lg { width: 5.5rem; height: 5.5rem; font-size: 2rem; }
    .coin-md { width: 4rem;   height: 4rem;   font-size: 1.5rem; }
    .coin-sm { width: 3rem;   height: 3rem;   font-size: 1.1rem; }

    /* posisi koin melayang (tersebar di panel biru) */
    .pos-1 { top: 12%; left: 14%; }
    .pos-2 { top: 16%; right: 15%; }
    .pos-3 { bottom: 18%; left: 16%; }
    .pos-4 { bottom: 12%; right: 14%; }
    .pos-5 { top: 44%; left: 7%; }
    .pos-6 { top: 38%; right: 8%; }

    /* ═══════════════════ KOIN 3D UTAMA ═══════════════════ */
    .coin-stage{
        --coin-t: 12px;
        width: 88px; height: 88px;
        perspective: 900px;
        perspective-origin: 50% 50%;
    }
    .coin-stage--lg{ --coin-t: 16px; width: 112px; height: 112px; }
    @media (min-width: 1280px){ .coin-stage--lg{ --coin-t: 18px; width: 128px; height: 128px; } }

    .coin-float{ animation: cuanin-coin-float 3.8s ease-in-out infinite; }
    @keyframes cuanin-coin-float{
        0%,100%{ transform: translateY(0); }
        50%    { transform: translateY(-9px); }
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
    .coin-face.front{ transform: translateZ(calc(var(--coin-t) / 2 + .6px)); }
    .coin-face.back { transform: rotateY(180deg) translateZ(calc(var(--coin-t) / 2 + .6px)); }
    .coin-emblem{ color: #78350f; filter: drop-shadow(0 1px 0 rgba(255,255,255,.5)); }

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
    .coin-stage .coin {
        position: relative; width: 100%; height: 100%;
        transform-style: preserve-3d;
        will-change: transform;
        animation: cuanin-coin-spin 3.4s linear infinite;
        background: none;
        border: none;
        box-shadow: none;
    }
    .coin-stage .coin::after { display: none; }
    @keyframes cuanin-coin-spin{
        0%   { transform: rotateY(0deg)   rotateX(14deg); }
        100% { transform: rotateY(360deg) rotateX(14deg); }
    }


    /* variasi animasi */
    .anim-1 { animation: floatSpin 6s   ease-in-out infinite; }
    .anim-2 { animation: floatSpin 7.5s ease-in-out infinite .4s; }
    .anim-3 { animation: floatSpin 5.5s ease-in-out infinite .8s; }
    .anim-4 { animation: floatSpin 8s   ease-in-out infinite .2s; }
    .anim-5 { animation: floatY    4.5s ease-in-out infinite .6s; }
    .anim-6 { animation: floatY    5s   ease-in-out infinite 1s; }

    .coin-xl { animation: floatSpin 9s ease-in-out infinite; }
    .glow    { animation: pulseGlow 5s ease-in-out infinite; }
</style>

@php $coinLayers = 40; @endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 flex flex-col justify-center min-h-[calc(100vh-180px)]">

    {{-- ============ CARD TUNGGAL ============ --}}
    <div class="font-poppins w-full grid grid-cols-1 lg:grid-cols-2 bg-white rounded-3xl shadow-2xl shadow-blue-900/30 border border-gray-100 overflow-hidden min-h-[500px] lg:min-h-[560px]">

        {{-- -------- KOLOM 1 : FORM REGISTER -------- --}}
        {{-- [FIX] flex-col justify-center (mirror login) + padding xl:p-12 --}}
        <div class="relative flex flex-col justify-center overflow-hidden bg-white p-4 sm:p-6 xl:p-8">

            <!-- Decoration blur -->
            <div class="absolute -mr-10 -mt-10 right-0 top-0 h-32 w-32 rounded-full bg-primary opacity-10 blur-3xl mix-blend-multiply"></div>
            <div class="absolute -mb-10 -ml-10 bottom-0 left-0 h-32 w-32 rounded-full bg-secondary opacity-10 blur-3xl mix-blend-multiply"></div>

            <div class="relative z-10">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                    <p class="text-gray-500 text-sm">Bergabung dan mulai transaksi cuanmu sekarang.</p>
                </div>

                <form class="mt-4 space-y-3" action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Budi Setiawan" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="contoh@email.com" value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                                <input id="phone_number" name="phone_number" type="text" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="081234567890" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- ===== PASSWORD dengan toggle mata ===== --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" required
                                        class="appearance-none block w-full px-4 py-3 pr-10 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                        placeholder="Min. 8 karakter">
                                    <button type="button" onclick="togglePassword('password', 'eye-password', 'eyeOff-password')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                        <i data-lucide="eye" id="eye-password" class="w-4 h-4 pointer-events-none"></i>
                                        <i data-lucide="eye-off" id="eyeOff-password" class="w-4 h-4 hidden pointer-events-none"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ===== KONFIRMASI PASSWORD dengan toggle mata ===== --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                        class="appearance-none block w-full px-4 py-3 pr-10 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                        placeholder="Ulangi password">
                                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm', 'eyeOff-confirm')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                        <i data-lucide="eye" id="eye-confirm" class="w-4 h-4 pointer-events-none"></i>
                                        <i data-lucide="eye-off" id="eyeOff-confirm" class="w-4 h-4 hidden pointer-events-none"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-blue-500/20 text-sm font-semibold text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition transform hover:-translate-y-0.5 cursor-pointer">
                            Daftar
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:text-blue-700 transition">Masuk di sini</a>
                </div>
            </div>
        </div>

        {{-- -------- KOLOM 2 : PANEL ANIMASI KOIN -------- --}}
        {{-- [FIX] padding disamakan xl:p-12 --}}
        <div class="coin-panel relative hidden lg:flex flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-primary via-blue-700 to-blue-900 text-white p-6 xl:p-8">

            <!-- blurred glow decoration -->
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-secondary rounded-full mix-blend-screen filter blur-3xl opacity-30"></div>
            <div class="absolute -bottom-20 -left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-screen filter blur-3xl opacity-30"></div>

            <!-- koin melayang (absolute) -->
            <div class="coin coin-lg pos-1 anim-1">C</div>
            <div class="coin coin-md pos-2 anim-2">C</div>
            <div class="coin coin-sm pos-3 anim-3">C</div>
            <div class="coin coin-md pos-4 anim-4">C</div>
            <div class="coin coin-sm pos-5 anim-5">C</div>
            <div class="coin coin-lg pos-6 anim-6">C</div>

            <!-- konten tengah : HANYA headline (paragraf & list dihapus) -->
            <div class="relative z-10 text-center">
                <!-- glow di belakang koin utama -->
                <div class="relative inline-flex items-center justify-center mb-6 xl:mb-8">
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
                                @for ($i = 0; $i < $coinLayers; $i++)
                                    <div class="coin-edge" style="transform: translateZ(calc(({{ $i }} / {{ $coinLayers - 1 }} - 0.5) * var(--coin-t)))"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight drop-shadow-sm">
                    Mulai Perjalanan<br>Cuanmu Sekarang!
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

    // Inisialisasi Lucide Icons
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush