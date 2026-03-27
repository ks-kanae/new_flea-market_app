<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Verified;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 会員登録後、認証メールが送信される()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::first();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    /** @test */
    public function メール認証誘導画面が表示される()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertViewIs('auth.verify-email');
        $response->assertSee('認証');
    }

    /** @test */
    public function メール認証完了後プロフィール設定画面に遷移する()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $user->markEmailAsVerified();
        event(new Verified($user));

        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);
    }
}
