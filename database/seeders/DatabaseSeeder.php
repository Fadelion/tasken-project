<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'role' => 'user', // Rôle explicite
        ]);

        // Création d'un administrateur
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Création de catégories (3) pour l'utilisateur de test
        $categories = Category::factory(3)->create([
            'user_id' => $testUser->id
        ]);

        // Création de 10 tâches pour cet utilisateur, en les associant à ses catégories
        foreach ($categories as $category) {
            Task::factory(10)->create([
                'user_id' => $testUser->id,
                'category_id' => $category->id
            ])->each(function ($task) {
                // Pour chaque tâche, créer 3 sous-tâches
                Subtask::factory(3)->create(['task_id' => $task->id]);
            });
        }

        // Créer 10 autres utilisateurs aléatoires
        User::factory(10)->create();
    }
}
