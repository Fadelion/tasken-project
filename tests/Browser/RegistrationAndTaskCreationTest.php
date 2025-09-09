<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationAndTaskCreationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_create_task(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/tasks/create')
                    ->type('title', 'My First Task')
                    ->type('description', 'This is a test task.')
                    ->select('category_id', (string)$category->id)
                    ->type('due_date', now()->addDay()->format('Y-m-d'))
                    ->select('priority', 'Normal')
                    ->press('Créer la tâche')
                    ->assertPathIs('/tasks')
                    ->assertSee('My First Task');
        });
    }
}
