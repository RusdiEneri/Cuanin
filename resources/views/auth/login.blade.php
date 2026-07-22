@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl shadow-blue-900/5 border border-gray-100 relative overflow-hidden">

        {{-- Decoration --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-10 -mb-10"></div>

        <div class="relative z-10">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h2>
                <p class="text-gray-500 text-sm">Masuk untuk melanjutkan aktivitas jual beli kamu.</p>
            </div>

            {{-- Alert Error Global (dari session) --}}
            @if(session('error'))
            <div class="mt-4 bg-red-50 text-danger p-3 rounded-xl flex items-center gap-2 text-sm border border-red-100" role="alert">
                <i data-lucide="circle-alert" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- Alert Success (misal setelah register) --}}
            @if(session('success'))
            <div class="mt-4 bg-green-50 text-green-700 p-3 rounded-xl flex items-center gap-2 text-sm border border-green-100" role="alert">
                <i data-lucide="circle-check" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- Alert Status (default Laravel, misal setelah reset password) --}}
            @if(session('status'))
            <div class="mt-4 bg-blue-50 text-blue-700 p-3 rounded-xl flex items-center gap-2 text-sm border border-blue-100" role="alert">
                <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form class="mt-8 space-y-5" action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="space-y-4">

                    {{-- ═══════════════════════════════════════════ --}}
                    {{-- LOGIN FIELD (Email / Nomor HP)             --}}
                    {{-- ═══════════════════════════════════════════ --}}
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                            Email / Nomor HP
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="at-sign" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input
                                id="login"
                                name="login"
                                type="text"
                                autocomplete="username"
                                required
                                autofocus
                                class="appearance-none block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('login') border-danger ring-1 ring-danger @enderror"
                                placeholder="contoh@email.com"
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

                    {{-- ═══════════════════════════════════════════ --}}
                    {{-- PASSWORD FIELD                             --}}
                    {{-- ═══════════════════════════════════════════ --}}
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
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="appearance-none block w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('password') border-danger ring-1 ring-danger @enderror"
                                placeholder="••••••••"
                                aria-describedby="password-error"
                            >
                            {{-- Toggle Password Visibility --}}
                            <button
                                type="button"
                                onclick="togglePassword()"
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
                        id="remember-me"
                        name="remember"
                        type="checkbox"
                        {{ old('remember') ? 'checked' : '' }}
                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                    >
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>

                {{-- Submit Button --}}
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