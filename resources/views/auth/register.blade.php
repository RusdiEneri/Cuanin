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
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Ulangi password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-blue-500/20 text-sm font-semibold text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition transform hover:-translate-y-0.5">
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
