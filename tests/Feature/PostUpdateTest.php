<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Old',
            'description' => 'Old',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put("/posts/{$post->id}", [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated title',
        ]);
    }

    public function test_user_cannot_update_others_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $otherUser = User::create([
            'name' => 'Other',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Old',
            'description' => 'Old',
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put("/posts/{$post->id}", [
            'title' => 'Hack',
            'description' => 'Hack',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect();
    }

    public function test_guest_cannot_update_post()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user3@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Old',
            'description' => 'Old',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->put("/posts/{$post->id}", [
            'title' => 'Hack',
            'description' => 'Hack',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_update_requires_valid_data()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user4@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create(['name' => 'Test']);

        $post = Post::create([
            'title' => 'Old',
            'description' => 'Old',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put("/posts/{$post->id}", [
            'title' => '',
            'description' => '',
            'category_id' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'description', 'category_id']);
    }
}