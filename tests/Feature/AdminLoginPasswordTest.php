<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_login_without_password_logs_in_farmer(): void
    {
        $user = User::factory()->create([
            'email' => 'farmer@example.com',
            'role' => 'Farmer',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'login_mode' => 'email',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_without_password_uses_the_dashboard(): void
    {
        $officer = User::factory()->create([
            'email' => 'officer-login@example.com',
            'role' => 'Admin',
        ]);

        $this->post('/login', [
            'email' => $officer->email,
            'login_mode' => 'email',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($officer);
    }

    public function test_da_admin_login_requires_a_password(): void
    {
        $officer = User::factory()->create([
            'email' => 'officer@example.com',
            'role' => 'Admin',
            'admin_type' => 'da_admin',
        ]);

        $this->post('/login', [
            'email' => $officer->email,
            'login_mode' => 'email',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_da_admin_email_login_accepts_a_valid_password(): void
    {
        $officer = User::factory()->create([
            'email' => 'officer-valid@example.com',
            'role' => 'Admin',
            'admin_type' => 'da_admin',
        ]);

        $this->post('/login', [
            'email' => $officer->email,
            'login_mode' => 'email',
            'password' => 'password',
            'remember' => 'on',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($officer);
    }

}
