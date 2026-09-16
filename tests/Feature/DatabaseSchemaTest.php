<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_tables_exist(): void
    {
        foreach (['users', 'lists', 'tasks', 'list_user'] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Tabel {$table} seharusnya ada.");
        }
    }

    public function test_users_have_role_defaulting_to_user(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertSame('user', $user->role);
        $this->assertSame('admin', $admin->role);
    }

    public function test_deleting_owner_cascades_their_lists(): void
    {
        $owner = User::factory()->create();
        TodoList::factory()->for($owner, 'owner')->create(['name' => 'List A']);

        $this->assertSame(1, TodoList::count());

        $owner->delete();

        $this->assertSame(0, TodoList::count());
    }

    public function test_deleting_list_cascades_tasks_and_members(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create();
        $list = TodoList::factory()->for($user, 'owner')->create();

        DB::table('tasks')->insert([
            ['list_id' => $list->id, 'title' => 'T1', 'priority' => 'medium', 'is_completed' => false, 'created_at' => now(), 'updated_at' => now()],
            ['list_id' => $list->id, 'title' => 'T2', 'priority' => 'high', 'is_completed' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('list_user')->insert([
            ['list_id' => $list->id, 'user_id' => $member->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->assertSame(2, DB::table('tasks')->count());
        $this->assertSame(1, DB::table('list_user')->count());

        $list->delete();

        $this->assertSame(0, DB::table('tasks')->where('list_id', $list->id)->count());
        $this->assertSame(0, DB::table('list_user')->where('list_id', $list->id)->count());
        $this->assertDatabaseMissing('lists', ['id' => $list->id]);
    }
}