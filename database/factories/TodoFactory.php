<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Dapatkan semua ID user yang ada
        $userIds = User::pluck('id')->toArray();
        
        // Pilihan status yang tersedia
        $statuses = ['Not started', 'In progress', 'Completed'];
        
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->text(100),
            'author_id' => fake()->randomElement($userIds),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
