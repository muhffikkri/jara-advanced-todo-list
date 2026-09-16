<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class TodoListModelConfigTest extends TestCase
{
    public function test_uses_lists_table(): void
    {
        $this->assertSame('lists', (new TodoList)->getTable());
    }

    public function test_has_expected_fillable_attributes(): void
    {
        $this->assertSame(['name', 'description', 'owner_id'], (new TodoList)->getFillable());
    }

    public function test_owner_relation_is_a_belongs_to_relation(): void
    {
        $this->assertInstanceOf(BelongsTo::class, (new TodoList)->owner());
    }
}
