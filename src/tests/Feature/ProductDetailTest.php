<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細ページに必要な情報がすべて表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 12345,
            'description' => 'これはテスト用の商品説明です',
            'condition' => '良好',
            'image_path' => 'product_images/test.jpg',
        ]);

        $category = Category::factory()->create([
            'name' => '家電',
        ]);
        $product->categories()->attach($category);

        Like::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'body' => 'とても良い商品ですね',
        ]);

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('12,345');
        $response->assertSee('これはテスト用の商品説明です');
        $response->assertSee('良好');

        $response->assertSee('家電');

        $response->assertSee('1');
        $response->assertSee('コメント (1)');

        $response->assertSee('テストユーザー');
        $response->assertSee('とても良い商品ですね');
    }

    /** @test */
    public function 複数選択されたカテゴリが商品詳細ページに表示される()
    {
        $product = Product::factory()->create([
            'name' => 'カテゴリテスト商品',
        ]);

        $categories = Category::factory()->count(2)->sequence(
            ['name' => 'ファッション'],
            ['name' => 'メンズ']
        )->create();

        $product->categories()->attach($categories->pluck('id'));

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
    }
}
