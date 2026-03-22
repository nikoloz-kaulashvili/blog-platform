<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_comment()
    {
        $user = User::factory()->create();

        $post = Post::create([
            'title' => 'Test Post',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => Category::create(['name' => 'Test'])->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Test comment',
            'post_id' => $post->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'content' => 'Test comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_guest_cannot_create_comment()
    {
        $user = User::create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $category = Category::create([
            'name' => 'Test',
        ]);

        $post = Post::create([
            'title' => 'Test Post',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->post("/posts/{$post->id}/comments", [
            'content' => 'Test comment',
        ]);

        $response->assertStatus(403);;
    }

    public function test_user_can_reply_to_comment()
    {
        $user = User::factory()->create();

        $post = Post::create([
            'title' => 'Test Post',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => Category::create(['name' => 'Test'])->id,
            'status' => 'approved',
        ]);

        $parent = Comment::create([
            'content' => 'Parent',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Reply comment',
            'post_id' => $post->id,
            'parent_id' => $parent->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'parent_id' => $parent->id,
        ]);
    }

    public function test_comment_requires_content()
    {
        $user = User::factory()->create();

        $post = Post::create([
            'title' => 'Test Post',
            'description' => 'Test',
            'user_id' => $user->id,
            'category_id' => Category::create(['name' => 'Test'])->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => '',
            'post_id' => $post->id,
        ]);

        $response->assertSessionHasErrors('content');
    }
}
