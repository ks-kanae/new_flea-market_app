<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Like;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $hitProduct = Product::factory()->create([
            'name' => 'ナイキ スニーカー',
        ]);

        $missProduct = Product::factory()->create([
            'name' => 'アディダス ジャケット',
        ]);

        $response = $this->get('/?keyword=ナイキ');

        $response->assertStatus(200);
        $response->assertSee($hitProduct->name);
        $response->assertDontSee($missProduct->name);
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $likedHitProduct = Product::factory()->create([
            'name' => 'アップル iPhone',
        ]);

        $likedMissProduct = Product::factory()->create([
            'name' => 'サムスン Galaxy',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'product_id' => $likedHitProduct->id,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'product_id' => $likedMissProduct->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist&keyword=iPhone');

        $response->assertStatus(200);
        $response->assertSee($likedHitProduct->name);
        $response->assertDontSee($likedMissProduct->name);
    }
}
