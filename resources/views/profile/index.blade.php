@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Profil Saya</h1>
        <p class="text-gray-500">Kelola informasi data diri Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 bg-red-50 text-danger p-4 rounded-xl border border-red-100">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col sm:flex-row gap-10">
                <!-- Avatar Upload -->
                <div class="flex flex-col items-center sm:w-1/3">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-50 bg-gray-100 mb-4 relative group">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i data-lucide="user" class="w-12 h-12"></i>
                            </div>
                        @endif
                        <label for="avatar" class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition cursor-pointer">
                            <i data-lucide="camera" class="w-6 h-6 mb-1"></i>
                            <span class="text-xs font-medium">Ubah Foto</span>
                        </label>
                        <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*">
                    </div>
                    <div class="text-sm text-center text-gray-500">
                        Format .jpg .jpeg .png maksimal 2MB
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="flex-grow space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="appearance-none block w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">{{ old('address', $user->address) }}</textarea>
                    </div>
                    
                    @if($user->role !== 'penjual')
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-200 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-white p-2 rounded-lg text-primary shadow-sm flex-shrink-0">
                                <i data-lucide="store" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-900 mb-1">Ingin mulai berjualan?</h4>
                                <p class="text-sm text-gray-600">Aktifkan Dasbor Penjual Anda sekarang juga secara gratis dan mulai pasang iklan barang bekas Anda.</p>
                            </div>
                        </div>
                        <button type="button" onclick="event.preventDefault(); document.getElementById('become-seller-form').submit();" class="flex-shrink-0 whitespace-nowrap bg-primary text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                            Aktifkan Toko Saya
                        </button>
                    </div>
                    @else
                    <div class="bg-green-50 p-4 rounded-xl border border-green-100 flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 flex-shrink-0"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-success mb-1">Akun Penjual Aktif</h4>
                            <p class="text-xs text-green-800">Anda dapat memposting barang dagangan di Dashboard Penjual.</p>
                        </div>
                    </div>
                    @endif

                    <div class="border-t border-gray-100 pt-6 mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Ubah Password (Opsional)</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="appearance-none block w-full px-4 py-3 border border-border-color rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="become-seller-form" action="{{ route('profile.becomeSeller') }}" method="POST" class="hidden">
    @csrf
</form>
@endsection
