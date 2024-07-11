<?php

namespace Tests\Feature\Faculty_Auth;

use Tests\TestCase;
use App\Models\Faculty;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\Faculty\FacultyResetPasswordNotification;

class Faculty_PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('faculty/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = Faculty::factory()->create();
 
        $this->post('faculty/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, FacultyResetPasswordNotification::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = Faculty::factory()->create();

        $this->post('faculty/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, FacultyResetPasswordNotification::class, function ($notification) {
            $response = $this->get('faculty/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = Faculty::factory()->create();

        $this->post('faculty/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, FacultyResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->post('faculty/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
