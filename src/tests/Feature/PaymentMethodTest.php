<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 小計画面で変更が反映される()
    {
        $user = User::factory()->create(['profile_completed' => true]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post("/purchase/{$product->id}", [
                'payment_method' => 'convenience',
            ]);

        $response = $this->actingAs($user)
            ->get("/purchase/{$product->id}");

        $response->assertSee('コンビニ払い');
    }
}
