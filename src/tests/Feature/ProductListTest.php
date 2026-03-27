<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品を取得できる()
    {
        $products = Product::factory()->count(10)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示される()
    {
        $product = Product::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('sold-badge');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->withProfile()->create();

        $ownProduct = Product::factory()->create([
        'user_id' => $user->id,
        ]);

        $otherUser = User::factory()->withProfile()->create();

        $otherProduct = Product::factory()->create([
        'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)
        ->followingRedirects()
        ->get('/');

        $response->assertDontSee('/item/' . $ownProduct->id);
        $response->assertSee('/item/' . $otherProduct->id);

    }
}
