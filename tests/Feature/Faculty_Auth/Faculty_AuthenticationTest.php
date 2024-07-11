<?php

namespace Tests\Feature\Faculty_Auth;

use App\Models\Faculty;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Faculty_AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('faculty/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = Faculty::factory()->create();

        $response = $this->post('faculty/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('faculty');
        $response->assertRedirect(RouteServiceProvider::FACULTYHOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = Faculty::factory()->create();

        $this->post('faculty/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
