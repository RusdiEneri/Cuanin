<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id', 
        'title',
        'slug',
        'description',
        'price',
        'stock',
        'condition',
        'location',
        'status',
        'views',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // 🎯 TAMBAHKAN METHOD INI
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function displayImage()
    {
        $primaryImage = $this->primaryImage()->first();

        if ($primaryImage) {
            return $primaryImage;
        }

        return $this->productImages()->orderBy('is_primary', 'desc')->orderBy('id', 'asc')->first();
    }

    public function displayImageUrl(): ?string
    {
        $image = $this->displayImage();

        if (!$image) {
            return null;
        }

        if (str_starts_with($image->image_path, 'http')) {
            return $image->image_path;
        }

        return asset('storage/' . ltrim($image->image_path, '/'));
    }
}