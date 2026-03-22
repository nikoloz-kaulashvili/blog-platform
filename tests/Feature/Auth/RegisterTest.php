<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $data = [
            'name' => 'Nika',
            'email' => 'nika@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $data);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $this->assertAuthenticated();
    }

    public function test_register_validation_fails()
    {
        $data = [
            'name' => '',
            'email' => 'wrong-email',
            'password' => '123',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $existingUser = User::factory()->create([
            'email' => 'nika@test.com',
        ]);

        $data = [
            'name' => 'Nika',
            'email' => $existingUser->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHasErrors('email');
    }
}