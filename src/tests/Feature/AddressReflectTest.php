<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressReflectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = User::factory()->withProfile()->create();
        $seller = User::factory()->withProfile()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        $this->actingAs($user)
            ->post("/purchase/address/{$product->id}", [
                'postcode' => '111-2222',
                'address' => '大阪府大阪市',
                'building' => 'テストマンション',
            ]);

        $response = $this->actingAs($user)->get("/purchase/{$product->id}");

        $response->assertSee('111-2222');
        $response->assertSee('大阪府大阪市');
        $response->assertSee('テストマンション');
    }
}
