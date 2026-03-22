<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModeratorPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_approve_post()
    {
        $moderator = User::create([
            'name' => 'Moderator',
            'email' => 'mod@test.com',
            'password' => bcrypt('password'),
            'role' => 'moderator',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Test',
            'description' => 'Test',
            'user_id' => $moderator->id,
            'category_id' => $category->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($moderator)
            ->post("/posts/{$post->id}/approve");

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => 'approved',
        ]);
    }

    public function test_moderator_can_reject_post()
    {
        $moderator = User::create([
            'name' => 'Moderator',
            'email' => 'mod2@test.com',
            'password' => bcrypt('password'),
            'role' => 'moderator',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Test',
            'description' => 'Test',
            'user_id' => $moderator->id,
            'category_id' => $category->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($moderator)
            ->post("/posts/{$post->id}/reject");

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => 'rejected',
        ]);
    }

    public function test_user_cannot_approve_post()
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
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->post("/posts/{$post->id}/approve");

        $response->assertStatus(403);
    }

    public function test_guest_cannot_approve_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Test',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'pending',
        ]);

        $response = $this->post("/posts/{$post->id}/approve");

        $response->assertRedirect('/login');
    }
}