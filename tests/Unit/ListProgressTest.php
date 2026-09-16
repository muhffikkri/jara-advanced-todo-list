<?php

namespace Tests\Unit;

use App\Support\ListProgress;
use PHPUnit\Framework\TestCase;

class ListProgressTest extends TestCase
{
    public function test_zero_tasks_returns_zero_percent(): void
    {
        $this->assertSame(0, ListProgress::percentage(0, 0));
    }

    public function test_half_of_tasks_done_returns_fifty_percent(): void
    {
        $this->assertSame(50, ListProgress::percentage(8, 4));
    }

    public function test_completed_never_exceeds_one_hundred_percent(): void
    {
        $this->assertSame(100, ListProgress::percentage(3, 9));
    }

    public function test_negative_completed_count_is_treated_as_zero(): void
    {
        $this->assertSame(0, ListProgress::percentage(5, -2));
    }

    public function test_partial_percentages_are_rounded(): void
    {
        $this->assertSame(67, ListProgress::percentage(3, 2));
    }
}
