<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_a_list(): void
    {
        $this->postJson('/lists', ['name' => 'Tugas Kuliah'])
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_a_list_and_becomes_owner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/lists', [
                'name' => 'Tugas Kuliah',
                'description' => 'Daftar tugas semester',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('lists', [
            'id' => $response->json('id'),
            'name' => 'Tugas Kuliah',
            'description' => 'Daftar tugas semester',
            'owner_id' => $user->id,
        ]);
    }

    public function test_owner_can_update_own_list(): void
    {
        $user = User::factory()->create();
        $list = TodoList::factory()->forUser($user)->create();

        $this->actingAs($user)
            ->patchJson("/lists/{$list->id}", ['name' => 'Nama Baru'])
            ->assertOk();

        $this->assertDatabaseHas('lists', [
            'id' => $list->id,
            'name' => 'Nama Baru',
        ]);
    }

    public function test_non_owner_cannot_update_list(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $list = TodoList::factory()->forUser($owner)->create();

        $this->actingAs($other)
            ->patchJson("/lists/{$list->id}", ['name' => 'Hack'])
            ->assertForbidden();
    }
}
