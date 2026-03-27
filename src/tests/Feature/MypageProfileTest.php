<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MypageProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が取得できる()
    {
        $user = User::factory()->withProfile()->create();

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertSee($user->name);

        $this->assertTrue(
        str_contains($response->getContent(), 'profile-avatar')
        );
    }
}
