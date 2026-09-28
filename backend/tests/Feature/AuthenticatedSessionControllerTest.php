<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_login_page_can_be_rendered(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Masuk ke Dashboard');
    }

    public function test_administrator_can_log_in(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'password' => 'secret-password']);

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'password' => 'secret-password']);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_administrator_cannot_log_in_to_admin_area(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'password' => 'secret-password']);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_administrator_can_log_out(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
