<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création d'un utilisateur de test spécifique
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Création d'un administrateur
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Création de catégories (3) pour l'utilisateur de test
        $categories = Category::factory(3)->create(['user_id' => $testUser->id]);

        foreach ($categories as $category) {
            // --- Tâches AVEC sous-tâches et statut cohérent ---

            // 1. Tâche "Open" (aucune sous-tâche terminée)
            Task::factory()->create([
                'user_id' => $testUser->id,
                'category_id' => $category->id,
                'status' => 'Open',
            ])->each(function ($task) {
                Subtask::factory(3)->create(['task_id' => $task->id, 'status' => false]);
            });

            // 2. Tâche "In Progress" (quelques sous-tâches terminées)
            Task::factory()->create([
                'user_id' => $testUser->id,
                'category_id' => $category->id,
                'status' => 'In Progress',
            ])->each(function ($task) {
                Subtask::factory(2)->create(['task_id' => $task->id, 'status' => true]);
                Subtask::factory(2)->create(['task_id' => $task->id, 'status' => false]);
            });

            // 3. Tâche "Completed" (toutes sous-tâches terminées)
            Task::factory()->create([
                'user_id' => $testUser->id,
                'category_id' => $category->id,
                'status' => 'Completed',
            ])->each(function ($task) {
                Subtask::factory(4)->create(['task_id' => $task->id, 'status' => true]);
            });

            // --- Tâches SANS sous-tâches ---
            Task::factory(5)->create([
                'user_id' => $testUser->id,
                'category_id' => $category->id,
            ]);
        }

        // Créer 10 autres utilisateurs aléatoires
        User::factory(10)->create();
    }
}
