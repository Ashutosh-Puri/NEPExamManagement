<?php

namespace Tests\Feature\Student_Auth;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\Student\StudentResetPasswordNotification;

class Student_PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('student/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = Student::factory()->create();
 
        $this->post('student/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, StudentResetPasswordNotification::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = Student::factory()->create();

        $this->post('student/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, StudentResetPasswordNotification::class, function ($notification) {
            $response = $this->get('student/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }


    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = Student::factory()->create();

        $this->post('student/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, StudentResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->post('student/reset-password', [
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
