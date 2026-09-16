<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_owner_can_create_a_task_in_a_list(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($owner)->post(route('tasks.store', ['list' => $list->id]), [
            'title' => 'Prepare demo',
            'description' => 'Prepare the MVP demonstration.',
            'priority' => 'high',
            'due_date' => '2030-01-15 09:00:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'list_id' => $list->id,
            'title' => 'Prepare demo',
            'priority' => 'high',
            'is_completed' => false,
        ]);
    }

    public function test_owner_can_update_a_task(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->forList($list->id)->create([
            'title' => 'Old title',
            'priority' => 'low',
        ]);

        $response = $this->actingAs($owner)->patch(route('tasks.update', $task), [
            'title' => 'Updated title',
            'description' => 'Updated description.',
            'priority' => 'urgent',
            'due_date' => '2030-02-20 10:30:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated title',
            'description' => 'Updated description.',
            'priority' => 'urgent',
        ]);
    }

    public function test_owner_can_delete_a_task(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->forList($list->id)->create();

        $response = $this->actingAs($owner)->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_owner_can_toggle_task_completion(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->forList($list->id)->create();

        $response = $this->actingAs($owner)->patch(route('tasks.toggle', $task));

        $response->assertRedirect();
        $completedTask = Task::findOrFail($task->id);
        $this->assertTrue($completedTask->is_completed);
        $this->assertNotNull($completedTask->completed_at);

        $response = $this->actingAs($owner)->patch(route('tasks.toggle', $task));

        $response->assertRedirect();
        $pendingTask = Task::findOrFail($task->id);
        $this->assertFalse($pendingTask->is_completed);
        $this->assertNull($pendingTask->completed_at);
    }

    public function test_task_input_is_validated(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($owner)->post(route('tasks.store', ['list' => $list->id]), [
            'title' => '',
            'priority' => 'critical',
            'due_date' => now()->subDay()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['title', 'priority', 'due_date']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_member_can_update_a_task(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);
        $this->addMember($list, $member);
        $task = Task::factory()->forList($list->id)->create(['title' => 'Team task']);

        $response = $this->actingAs($member)->patch(route('tasks.update', $task), [
            'title' => 'Updated by member',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated by member',
        ]);
    }

    public function test_non_member_cannot_manage_tasks(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $list = TodoList::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->forList($list->id)->create();

        $this->actingAs($outsider)
            ->post(route('tasks.store', ['list' => $list->id]), [
                'title' => 'Unauthorized task',
                'priority' => 'medium',
            ])
            ->assertForbidden();

        $this->actingAs($outsider)
            ->patch(route('tasks.update', $task), ['title' => 'Unauthorized update'])
            ->assertForbidden();

        $this->actingAs($outsider)
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();
    }

    private function addMember(TodoList $list, User $member): void
    {
        DB::table('list_user')->insert([
            'list_id' => $list->id,
            'user_id' => $member->id,
            'added_by' => $list->owner_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
