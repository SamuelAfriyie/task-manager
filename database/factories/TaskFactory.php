<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $project = Project::first();
        
        return [
            'title'         => $this->faker->sentence(),
            'desc'          => $this->faker->paragraph(),
            'status'          => 'pending',
            'assigned_date' => $this->faker->date(),
            'project_id'       => $project->id,
        ];
    }
}
