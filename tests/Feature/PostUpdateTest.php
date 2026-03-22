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
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $data = [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($user)->put("/posts/{$post->id}", $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
    }

    public function test_guest_cannot_update_post()
    {
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->put("/posts/{$post->id}", [
            'title' => 'Hack',
            'description' => 'Hack',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_update_requires_valid_data()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put("/posts/{$post->id}", [
            'title' => '',
            'description' => '',
            'category_id' => '',
        ]);

        $response->assertSessionHasErrors([
            'title',
            'description',
            'category_id',
        ]);
    }
}
