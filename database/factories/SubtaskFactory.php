<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtask>
 */
class SubtaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(5), // Titre de 5 mots
            'status' => $this->faker->randomElement([false, true]), // Statut aléatoire
            'task_id' => Task::factory(), // Associe à une tâche parente
        ];
    }
}
