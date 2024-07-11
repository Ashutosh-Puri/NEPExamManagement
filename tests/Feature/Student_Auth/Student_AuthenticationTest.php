<?php

namespace Tests\Feature\Student_Auth;

use App\Models\Student;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Student_AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('student/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = Student::factory()->create();

        $response = $this->post('student/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('student');
        $response->assertRedirect('student/profile');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = Student::factory()->create();

        $this->post('student/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
