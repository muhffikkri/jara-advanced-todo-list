<?php

namespace Tests\Unit;

use App\Enums\TaskPriority;
use PHPUnit\Framework\TestCase;

class TaskPriorityTest extends TestCase
{
    public function test_priorities_match_database_enum_values(): void
    {
        $this->assertSame(['low', 'medium', 'high', 'urgent'], TaskPriority::values());
    }

    public function test_each_priority_has_a_human_readable_label(): void
    {
        $this->assertSame('Low', TaskPriority::Low->label());
        $this->assertSame('Medium', TaskPriority::Medium->label());
        $this->assertSame('High', TaskPriority::High->label());
        $this->assertSame('Urgent', TaskPriority::Urgent->label());
    }

    public function test_medium_is_the_default_priority(): void
    {
        $this->assertSame(TaskPriority::Medium, TaskPriority::from('medium'));
    }
}
