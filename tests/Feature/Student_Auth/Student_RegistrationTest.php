<?php

namespace Tests\Feature\Student_Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Student_RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('student/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('student/register', [
            'first_name' => 'X',
            'middle_name' => 'Y',
            'last_name' => 'Z',
            'member_id' => '12345',
            'mobile_no' => '1234567890',
            'mother_name' => 'XYZ',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_profile_complete' => 1,
        ]);

        $this->assertAuthenticated('student');
        $response->assertRedirect('student/profile');
    }
}
