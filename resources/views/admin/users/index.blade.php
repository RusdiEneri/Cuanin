@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Kelola Pengguna</h1>
            <p class="text-gray-500">Atur peran pengguna di aplikasi Cuanin.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-success p-4 rounded-xl flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-border-color shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-6 py-4 font-medium">Pengguna</th>
                        <th class="px-6 py-4 font-medium">Tanggal Mendaftar</th>
                        <th class="px-6 py-4 font-medium">Role Saat Ini</th>
                        <th class="px-6 py-4 font-medium">Ubah Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition align-middle">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-white shadow-sm overflow-hidden flex-shrink-0 flex items-center justify-center text-primary font-bold">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role == 'admin')
                                <span class="px-2.5 py-1 bg-red-50 text-danger text-xs font-semibold rounded-full border border-red-100">Admin</span>
                            @elseif($user->role == 'penjual')
                                <span class="px-2.5 py-1 bg-green-50 text-success text-xs font-semibold rounded-full border border-green-100">Penjual</span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-50 text-primary text-xs font-semibold rounded-full border border-blue-100">Pembeli</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="appearance-none block w-full px-3 py-1.5 border border-border-color rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white cursor-pointer {{ $user->id === Auth::id() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                    <option value="pembeli" {{ $user->role == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                    <option value="penjual" {{ $user->role == 'penjual' ? 'selected' : '' }}>Penjual</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="p-1.5 bg-primary text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50" title="Simpan" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
