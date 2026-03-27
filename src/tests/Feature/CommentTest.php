<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create(['profile_completed' => true,
        'email_verified_at' => now(),]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post("/comment/{$product->id}", [
                'body' => 'テストコメント',
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'テストコメント',
        ]);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $product = Product::factory()->create();

        $this->post("/comment/{$product->id}", [
            'body' => 'テストコメント',
        ]);

        $this->assertDatabaseMissing('comments', [
            'body' => 'テストコメント',
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create(['profile_completed' => true,
        'email_verified_at' => now(),]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post("/comment/{$product->id}", [
                'body' => '',
            ]);

        $response->assertSessionHasErrors('body');
    }

    /** @test */
    public function コメントが255字以上の場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create(['profile_completed' => true,
        'email_verified_at' => now(),]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post("/comment/{$product->id}", [
                'body' => str_repeat('a', 256),
            ]);

        $response->assertSessionHasErrors('body');
    }
}
