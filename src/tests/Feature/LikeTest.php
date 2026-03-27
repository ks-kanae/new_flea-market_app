<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねアイコンを押下することによっていいねした商品として登録することができる()
    {
        $user = User::factory()->withProfile()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post("/like/{$product->id}");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /** @test */
    public function 追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->withProfile()->create();
        $product = Product::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)
            ->get("/item/{$product->id}");

        $response->assertSee('ハートロゴ_ピンク.png');
    }

    /** @test */
    public function 再度いいねアイコンを押下することによっていいねを解除することができる()
    {
        $user = User::factory()->withProfile()->create();
        $product = Product::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->post("/like/{$product->id}");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
