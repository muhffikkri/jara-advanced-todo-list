<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_user_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'role' => 'user',
        ]);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_invalid_login_is_rejected(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'incorrect',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);

        $this->actingAs($user)->put('/profile', [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('Updated User', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_only_admin_can_access_admin_area(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($admin)->get('/admin')->assertOk();
    }
}
