<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_access_the_dashboard(): void
    {
        $password = fake()->password(16);
        $admin = User::factory()->create([
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => $password,
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeText('Dashboard Administrator');
    }

    public function test_non_admin_cannot_log_in_to_the_admin_area(): void
    {
        $password = fake()->password(16);
        $user = User::factory()->create([
            'password' => Hash::make($password),
            'role' => 'user',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => $password,
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_non_admin_receives_forbidden_response(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_log_out(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}
