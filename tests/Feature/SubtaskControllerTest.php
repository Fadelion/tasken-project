<?php

namespace Tests\Feature;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubtaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_subtask_for_their_task()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $subtaskData = ['title' => 'New Subtask'];

        $this->actingAs($user)
            ->post(route('tasks.subtasks.store', $task), $subtaskData)
            ->assertRedirect();

        $this->assertDatabaseHas('subtasks', [
            'task_id' => $task->id,
            'title' => 'New Subtask',
        ]);
    }

    public function test_user_cannot_create_subtask_for_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherUsersTask = Task::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);
        $subtaskData = ['title' => 'New Subtask'];

        $this->actingAs($user)
            ->post(route('tasks.subtasks.store', $otherUsersTask), $subtaskData)
            ->assertForbidden();
    }

    public function test_user_can_update_their_subtask()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $subtask = Subtask::factory()->create(['task_id' => $task->id]);
        $updatedData = ['title' => 'Updated Subtask Title'];

        $this->actingAs($user)
            ->put(route('subtasks.update', $subtask), $updatedData)
            ->assertRedirect();

        $this->assertDatabaseHas('subtasks', [
            'id' => $subtask->id,
            'title' => 'Updated Subtask Title',
        ]);
    }

    public function test_user_cannot_update_other_users_subtask()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherUsersTask = Task::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);
        $otherUsersSubtask = Subtask::factory()->create(['task_id' => $otherUsersTask->id]);
        $updatedData = ['title' => 'Updated Subtask Title'];

        $this->actingAs($user)
            ->put(route('subtasks.update', $otherUsersSubtask), $updatedData)
            ->assertForbidden();
    }

    public function test_user_can_delete_their_subtask()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $subtask = Subtask::factory()->create(['task_id' => $task->id]);

        $this->actingAs($user)
            ->delete(route('subtasks.destroy', $subtask))
            ->assertRedirect();

        $this->assertDatabaseMissing('subtasks', ['id' => $subtask->id]);
    }

    public function test_user_cannot_delete_other_users_subtask()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherUsersTask = Task::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id]);
        $otherUsersSubtask = Subtask::factory()->create(['task_id' => $otherUsersTask->id]);

        $this->actingAs($user)
            ->delete(route('subtasks.destroy', $otherUsersSubtask))
            ->assertForbidden();
    }
}
