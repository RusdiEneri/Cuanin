<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi backend 2MB
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;

        // 🌟 LOGIKA AVATAR BARU 🌟
        if ($request->boolean('remove_avatar')) {
            // 1. Jika user mencentang "Hapus Foto"
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $user->avatar = null;
            }
        } elseif ($request->hasFile('avatar')) {
            // 2. Jika user upload foto baru
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar); // Hapus yang lama agar tidak menumpuk
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function becomeSeller(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'penjual') {
            $user->role = 'penjual';
            $user->save();
        }
        
        return redirect()->route('seller.dashboard')->with('success', 'Selamat! Akun Penjual Anda telah aktif. Anda sekarang bisa mulai menjual barang.');
    }

        public function destroy(Request $request)
{
    // ✅ Error masuk ke bag 'delete', BUKAN bag default (biar tidak bentrok dgn form update)
    $request->validateWithBag('delete', [
        'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();
    $user->delete(); // pakai forceDelete() kalau model pakai SoftDeletes & mau hapus permanen

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
}
}
