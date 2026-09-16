<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use App\Policies\ListPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ListPolicy $policy;

    private User $owner;

    private User $other;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new ListPolicy();
        $this->owner = User::factory()->create();
        $this->other = User::factory()->create();
    }

    private function ownedList(): TodoList
    {
        return TodoList::factory()->for($this->owner, 'owner')->create();
    }

    public function test_owner_can_view_update_and_delete_their_list(): void
    {
        $list = $this->ownedList();

        $this->assertTrue($this->policy->view($this->owner, $list));
        $this->assertTrue($this->policy->update($this->owner, $list));
        $this->assertTrue($this->policy->delete($this->owner, $list));
    }

    public function test_non_owner_cannot_view_update_or_delete(): void
    {
        $list = $this->ownedList();

        $this->assertFalse($this->policy->view($this->other, $list));
        $this->assertFalse($this->policy->update($this->other, $list));
        $this->assertFalse($this->policy->delete($this->other, $list));
    }
}