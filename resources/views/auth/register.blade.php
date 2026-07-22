@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl shadow-blue-900/5 border border-gray-100 relative overflow-hidden">
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-10 -mb-10"></div>

        <div class="relative z-10">
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
                            {{-- +cursor-pointer --}}
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
                            {{-- +cursor-pointer --}}
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm', 'eyeOff-confirm')" 
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                <i data-lucide="eye" id="eye-confirm" class="w-5 h-5 pointer-events-none"></i>
                                <i data-lucide="eye-off" id="eyeOff-confirm" class="w-5 h-5 hidden pointer-events-none"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    {{-- +cursor-pointer --}}
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