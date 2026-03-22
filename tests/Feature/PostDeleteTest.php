<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Test',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->delete("/posts/{$post->id}");

        $response->assertRedirect();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_admin_can_delete_any_post()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Test',
            'description' => 'Test',
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->delete("/posts/{$post->id}");

        $response->assertRedirect();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_guest_cannot_delete_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user3@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Test',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->delete("/posts/{$post->id}");

        $response->assertRedirect('/login');
    }
}