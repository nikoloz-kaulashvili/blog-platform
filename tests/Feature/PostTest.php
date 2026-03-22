<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create([
            'name' => 'Test',
        ]);

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Test post',
            'description' => 'Test description',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => 'Test post',
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_post()
    {
        $category = Category::create([
            'name' => 'Test',
        ]);

        $response = $this->post('/posts', [
            'title' => 'Test post',
            'description' => 'Test description',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_create_post_without_required_fields()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user3@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->post('/posts', []);

        $response->assertSessionHasErrors(['title', 'description', 'category_id']);
    }
}