<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ========================================
    // Helper Methods untuk Role
    // ========================================

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah seller.
     */
    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    /**
     * Cek apakah user adalah buyer.
     */
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    // ========================================
    // Relasi (sesuaikan jika ada tabel terkait)
    // ========================================

    // Contoh: jika ada tabel products milik seller
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

    // Contoh: jika ada tabel orders milik buyer
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }
}