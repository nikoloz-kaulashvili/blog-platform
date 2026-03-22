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
        $response = $this->post('/register', [
            'name' => 'Nika',
            'email' => 'nika@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'nika@test.com',
        ]);

        $this->assertAuthenticated();
    }

    public function test_register_validation_fails()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'wrong-email',
            'password' => '123',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'nika@test.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Nika',
            'email' => 'nika@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }
}