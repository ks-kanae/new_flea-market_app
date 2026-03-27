<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品出品画面にて必要な情報が保存できること()
    {
        Storage::fake('public');

        $user = User::factory()->withProfile()->create();

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'image' => UploadedFile::fake()->create(
                'test.jpg', 100, 'image/jpeg'),
            'categories' => [$category1->id, $category2->id],
            'condition' => '良好',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'price' => 9999,
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'price' => 9999,
            'condition' => '良好',
        ]);

        $product = Product::first();

        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $category2->id,
        ]);
    }
}
