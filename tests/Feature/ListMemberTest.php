<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\TodoList;
use App\Models\User;

class ListMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_add_member(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::create(['name' => 'Test', 'owner_id' => $owner->id]);
        $member = User::factory()->create();

        $response = $this->actingAs($owner)->postJson("/lists/{$list->id}/members", [
            'user_id' => $member->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('list_user', [
            'list_id' => $list->id,
            'user_id' => $member->id,
            'added_by' => $owner->id,
        ]);
    }

    public function test_non_owner_cannot_add_member(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::create(['name' => 'Test', 'owner_id' => $owner->id]);
        
        $otherUser = User::factory()->create();
        $member = User::factory()->create();

        $response = $this->actingAs($otherUser)->postJson("/lists/{$list->id}/members", [
            'user_id' => $member->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_remove_member(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::create(['name' => 'Test', 'owner_id' => $owner->id]);
        $member = User::factory()->create();
        
        $list->members()->attach($member->id, ['added_by' => $owner->id]);

        $response = $this->actingAs($owner)->deleteJson("/lists/{$list->id}/members/{$member->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('list_user', [
            'list_id' => $list->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_user_can_view_joined_lists(): void
    {
        $owner = User::factory()->create();
        $list = TodoList::create(['name' => 'Test', 'owner_id' => $owner->id]);
        $member = User::factory()->create();
        
        $list->members()->attach($member->id, ['added_by' => $owner->id]);

        $response = $this->actingAs($member)->getJson('/lists/joined');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Test']);
    }
}
