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

{{-- ====== SATU CARD TERPUSAT : FORM KIRI | KOIN KANAN ====== --}}
{{-- [FIX] padding outer dibuat responsif (sm:px-6 lg:px-8) seperti login,
          supaya di layar kecil ada napas & di layar besar card tidak mepet. --}}
<div class="flex min-h-[calc(100vh-4rem)] items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 px-4 py-10 sm:px-6 lg:px-8">

    {{-- ============ CARD TUNGGAL ============ --}}
    {{-- [FIX] lebar card disamakan dengan login:
              - mobile/tablet : max-w-md  (1 kolom, form tidak bolong kiri-kanan)
              - lg            : max-w-6xl
              - xl            : max-w-7xl
              Sebelumnya max-w-5xl -> bikin margin luar card terlalu lebar. --}}
    <div class="mx-auto grid w-full max-w-md grid-cols-1 overflow-hidden rounded-3xl bg-white shadow-2xl shadow-blue-900/10 ring-1 ring-black/5 lg:max-w-6xl lg:grid-cols-2 xl:max-w-7xl">

        {{-- -------- KOLOM 1 : FORM REGISTER -------- --}}
        {{-- [FIX] flex-col justify-center (mirror login) + padding xl:p-12 --}}
        <div class="relative flex flex-col justify-center overflow-hidden bg-white p-8 sm:p-10 xl:p-12">

            <!-- Decoration blur -->
            <div class="absolute -mr-10 -mt-10 right-0 top-0 h-32 w-32 rounded-full bg-primary opacity-10 blur-3xl mix-blend-multiply"></div>
            <div class="absolute -mb-10 -ml-10 bottom-0 left-0 h-32 w-32 rounded-full bg-secondary opacity-10 blur-3xl mix-blend-multiply"></div>

            {{-- [FIX] pembatas max-w-md DIHILANGKAN (cukup w-full).
                      Sekarang form mengisi lebar kolom (dibatasi padding kolom saja),
                      persis seperti login -> tidak ada rongga kosong kiri-kanan form. --}}
            <div class="relative z-10 w-full">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                    <p class="text-gray-500 text-sm">Gabung dengan Cuanin dan temukan barang impianmu.</p>
                </div>

                <form class="mt-8 space-y-5" action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Budi Setiawan" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="contoh@email.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                            <input id="phone_number" name="phone_number" type="text" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="081234567890" value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ===== PASSWORD dengan toggle mata ===== --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                    class="appearance-none block w-full px-4 py-3 pr-12 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" onclick="togglePassword('password', 'eye-password', 'eyeOff-password')"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                    <i data-lucide="eye" id="eye-password" class="w-5 h-5 pointer-events-none"></i>
                                    <i data-lucide="eye-off" id="eyeOff-password" class="w-5 h-5 hidden pointer-events-none"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ===== KONFIRMASI PASSWORD dengan toggle mata ===== --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="appearance-none block w-full px-4 py-3 pr-12 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Ulangi password">
                                <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm', 'eyeOff-confirm')"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                    <i data-lucide="eye" id="eye-confirm" class="w-5 h-5 pointer-events-none"></i>
                                    <i data-lucide="eye-off" id="eyeOff-confirm" class="w-5 h-5 hidden pointer-events-none"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-blue-500/20 text-sm font-semibold text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition transform hover:-translate-y-0.5 cursor-pointer">
                            Daftar
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:text-blue-700 transition">Masuk di sini</a>
                </div>
            </div>
        </div>

        {{-- -------- KOLOM 2 : PANEL ANIMASI KOIN -------- --}}
        {{-- [FIX] padding disamakan xl:p-12 --}}
        <div class="coin-panel relative hidden lg:flex flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-primary via-blue-700 to-blue-900 text-white p-10 xl:p-12">

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
                <div class="relative inline-flex items-center justify-center mb-8">
                    <div class="glow absolute w-44 h-44 bg-secondary rounded-full filter blur-2xl"></div>
                    <div class="coin coin-xl relative">C</div>
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