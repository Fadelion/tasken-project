<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_tasks()
    {
        $this->get(route('tasks.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_see_their_tasks()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertSee($task->title);
    }

    public function test_user_cannot_see_other_users_tasks()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherUsersTask = Task::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertDontSee($otherUsersTask->title);
    }

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
            'category_id' => $category->id,
            'due_date' => now()->addDay()->format('Y-m-d'),
            'priority' => 'Normal',
            'status' => 'In Progress',
        ];

        $this->actingAs($user)
            ->post(route('tasks.store'), $taskData)
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_user_can_update_their_task()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $updatedData = ['title' => 'Updated Task Title'];

        $this->actingAs($user)
            ->put(route('tasks.update', $task), $updatedData)
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
        ]);
    }

    public function test_user_cannot_update_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherUsersTask = Task::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);
        $updatedData = ['title' => 'Updated Task Title'];

        $this->actingAs($user)
            ->put(route('tasks.update', $otherUsersTask), $updatedData)
            ->assertForbidden();
    }

    public function test_user_can_delete_their_task()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->actingAs($user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherUsersTask = Task::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);

        $this->actingAs($user)
            ->delete(route('tasks.destroy', $otherUsersTask))
            ->assertForbidden();
    }
}
