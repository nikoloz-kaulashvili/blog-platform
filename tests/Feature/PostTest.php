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
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'title' => 'Test post',
            'description' => 'Test description',
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($user)->post('/posts', $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => $data['title'],
            'user_id' => $user->id,
        ]);
    }

    public function test_post_is_created_with_pending_status()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Another post',
            'description' => 'Description',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => 'Another post',
        ]);
    }

    public function test_guest_cannot_create_post()
    {
        $category = Category::factory()->create();

        $response = $this->post('/posts', [
            'title' => 'Test post',
            'description' => 'Test description',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_create_post_without_required_fields()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', []);

        $response->assertSessionHasErrors([
            'title',
            'description',
            'category_id',
        ]);
    }
}