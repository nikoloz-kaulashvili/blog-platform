<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => fake()->sentence(),
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'parent_id' => null,
        ];
    }
}