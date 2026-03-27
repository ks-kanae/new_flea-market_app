<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseAddressSaveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
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
                    'postcode' => '111-2222',
                    'address' => '大阪府大阪市',
                    'building' => 'テストマンション',
                ],
            ])
            ->post("/purchase/{$product->id}", [
                'payment_method' => 'convenience',
            ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'postcode' => '111-2222',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
        ]);
    }

}
