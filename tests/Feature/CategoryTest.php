<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_category()
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 'Tech',
        ];

        $response = $this->actingAs($admin)->post('/categories', $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'name' => $data['name'],
        ]);
    }

    public function test_user_cannot_create_category()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Tech',
        ];

        $response = $this->actingAs($user)->post('/categories', $data);

        $response->assertForbidden();
    }

    public function test_guest_cannot_create_category()
    {
        $data = [
            'name' => 'Tech',
        ];

        $response = $this->post('/categories', $data);

        $response->assertRedirect(route('login'));
    }
}