<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 変更項目が初期値として過去設定されていること()
    {
        $user = User::factory()->withProfile()->create();

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertSee($user->name);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
    }
}
