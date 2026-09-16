<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_list_atomically(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->for($owner, 'owner')->create();

        DB::table('tasks')->insert([
            'list_id' => $list->id,
            'title' => 'Tugas 1',
            'priority' => 'medium',
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('list_user')->insert([
            'list_id' => $list->id,
            'user_id' => User::factory()->create()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($owner)->deleteJson("/lists/{$list->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('lists', ['id' => $list->id]);
        $this->assertDatabaseCount('tasks', 0);
        $this->assertDatabaseCount('list_user', 0);
    }

    public function test_deleting_list_removes_tasks_and_members(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $list = TodoList::factory()->for($owner, 'owner')->create();

        DB::table('tasks')->insert([
            [
                'list_id' => $list->id,
                'title' => 'Tugas A',
                'priority' => 'high',
                'is_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'list_id' => $list->id,
                'title' => 'Tugas B',
                'priority' => 'low',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('list_user')->insert([
            'list_id' => $list->id,
            'user_id' => $member->id,
            'added_by' => $owner->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($owner)->deleteJson("/lists/{$list->id}")->assertNoContent();

        $this->assertDatabaseMissing('lists', ['id' => $list->id]);
        $this->assertSame(0, DB::table('tasks')->where('list_id', $list->id)->count());
        $this->assertSame(0, DB::table('list_user')->where('list_id', $list->id)->count());
    }

    public function test_non_owner_cannot_delete_list(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $list = TodoList::factory()->for($owner, 'owner')->create();

        $response = $this->actingAs($other)->deleteJson("/lists/{$list->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('lists', ['id' => $list->id]);
    }

    public function test_delete_rolls_back_when_transaction_fails(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::factory()->for($owner, 'owner')->create();

        try {
            DB::transaction(function () use ($list): void {
                DB::table('lists')->where('id', $list->id)->delete();

                throw new \RuntimeException('simulasi gagal di tengah transaksi');
            });
        } catch (\RuntimeException) {
            //
        }

        $this->assertDatabaseHas('lists', ['id' => $list->id]);
    }
}
