<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uses_first_image_when_primary_image_is_missing(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Laptop Bekas',
            'slug' => 'laptop-bekas',
            'description' => 'Laptop bekas dalam kondisi baik',
            'price' => 1500000,
            'condition' => 'used',
            'location' => 'Bandung',
            'status' => 'active',
        ]);

        $product->productImages()->create([
            'image_path' => 'products/laptop.jpg',
            'is_primary' => false,
        ]);

        $image = $product->displayImage();

        $this->assertNotNull($image);
        $this->assertSame('products/laptop.jpg', $image->image_path);
    }
}
