<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelConfigTest extends TestCase
{
    public function test_uses_users_table_with_expected_fillable_attributes(): void
    {
        $user = new User;

        $this->assertSame('users', $user->getTable());
        $this->assertSame(['name', 'email', 'password', 'role'], $user->getFillable());
    }

    public function test_password_and_remember_token_are_hidden_from_serialization(): void
    {
        $this->assertSame(['password', 'remember_token'], (new User)->getHidden());
    }

    public function test_password_is_cast_to_hashed(): void
    {
        $casts = (new User)->getCasts();

        $this->assertSame('hashed', $casts['password']);
    }
}
