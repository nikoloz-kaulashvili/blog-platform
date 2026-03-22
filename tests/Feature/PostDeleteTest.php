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
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
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
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
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
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->delete("/posts/{$post->id}");

        $response->assertRedirect(route('login'));
    }
}