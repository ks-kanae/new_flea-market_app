<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;

class ProductMyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::factory()->create([
        'profile_completed' => true,
        ]);
        $otherUser = User::factory()->create();

        $likedProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'LIKE_PRODUCT',
        ]);

        $notLikedProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'NOT_LIKE_PRODUCT',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'product_id' => $likedProduct->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($likedProduct->name);
        $response->assertDontSee($notLikedProduct->name);
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示される()
    {
        $user = User::factory()->create([
        'profile_completed' => true,
        ]);
        $otherUser = User::factory()->create();

        $soldProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'is_sold' => true,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'product_id' => $soldProduct->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('sold-badge');
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('product-card');
    }
}
