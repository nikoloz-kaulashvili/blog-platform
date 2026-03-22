<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_category()
    {
        $admin = User::create([
            'name' => 'Nika',
            'email' => 'nika@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/categories', [
            'name' => 'Tech',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'name' => 'Tech',
        ]);
    }

    public function test_user_cannot_create_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Tech',
        ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_category()
    {
        $response = $this->post('/categories', [
            'name' => 'Tech',
        ]);

        $response->assertRedirect('/login');
    }
}