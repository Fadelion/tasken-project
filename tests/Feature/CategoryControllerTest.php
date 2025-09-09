<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_categories()
    {
        $this->get(route('categories.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_see_their_categories()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_user_cannot_see_other_users_categories()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUsersCategory = Category::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertDontSee($otherUsersCategory->name);
    }

    public function test_user_can_create_category()
    {
        $user = User::factory()->create();
        $categoryData = ['name' => 'New Category'];

        $this->actingAs($user)
            ->post(route('categories.store'), $categoryData)
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'New Category',
        ]);
    }

    public function test_user_can_update_their_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $updatedData = ['name' => 'Updated Category Name'];

        $this->actingAs($user)
            ->put(route('categories.update', $category), $updatedData)
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name',
        ]);
    }

    public function test_user_cannot_update_other_users_category()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUsersCategory = Category::factory()->create(['user_id' => $otherUser->id]);
        $updatedData = ['name' => 'Updated Category Name'];

        $this->actingAs($user)
            ->put(route('categories.update', $otherUsersCategory), $updatedData)
            ->assertForbidden();
    }

    public function test_user_can_delete_their_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_other_users_category()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUsersCategory = Category::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user)
            ->delete(route('categories.destroy', $otherUsersCategory))
            ->assertForbidden();
    }
}
