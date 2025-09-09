<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
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
        return [
            'title' => $this->faker->sentence(4), // Génère une phrase de 4 mots pour le titre
            'description' => $this->faker->paragraph(2), // Génère un paragraphe de 2 phrases
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'), // Date d'échéance dans l'année à venir
            'priority' => $this->faker->randomElement(['Low', 'Normal', 'High']), // Priorité aléatoire
            'status' => $this->faker->randomElement(['Open', 'In Progress', 'Completed', 'Cancel']), // Statut aléatoire
            'is_sequential' => $this->faker->boolean(25), // 25% de chance que la tâche soit séquentielle
            'user_id' => User::factory(), // Associe à un utilisateur
            'category_id' => Category::factory(), // Associe à une catégorie
        ];
    }
}
