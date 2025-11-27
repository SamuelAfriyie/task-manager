<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::first();

        return [
            'title'         => $this->faker->sentence(),
            'desc'          => $this->faker->paragraph(),
            'deadline_date' => $this->faker->date(),
            'user_id'       => $user->id,
        ];
    }
}
