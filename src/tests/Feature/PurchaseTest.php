<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入するボタンを押下すると購入が完了する()
    {
        $buyer = User::factory()->withProfile()->create([
        'profile_completed' => true,
        ]);
        $seller = User::factory()->withProfile()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        $this->actingAs($buyer)->withSession([
            "purchase_address.{$product->id}" => [
                'postcode' => '123-4567',
                'address' => '東京都',
                'building' => 'テスト',
            ],
        ])
        ->post("/purchase/{$product->id}", [
            'payment_method' => 'convenience',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => 'テスト',
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面にてsoldと表示される()
    {
        $buyer = User::factory()->withProfile()->create([
        'profile_completed' => true,
        ]);
        $seller = User::factory()->withProfile()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        $this->actingAs($buyer)
            ->withSession([
                "purchase_address.{$product->id}" => [
                    'postcode' => '123-4567',
                    'address' => '東京都',
                    'building' => 'テスト',
                ],
            ])
            ->post("/purchase/{$product->id}", [
                'payment_method' => 'convenience',
            ]);

        $product->refresh();

        $this->assertTrue($product->is_sold);

        $response = $this->get('/');
        $response->assertSee('sold-badge');
    }

    /** @test */
    public function プロフィール購入した商品一覧に追加されている()
    {
        $buyer = User::factory()->withProfile()->create();
        $seller = User::factory()->withProfile()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($buyer)->get('/mypage?page=buy');

        $response->assertSee($product->name);
    }
}
