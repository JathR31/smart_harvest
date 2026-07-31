<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_login_requires_a_password(): void
    {
        $user = User::factory()->create([
            'email' => 'farmer@example.com',
            'role' => 'Farmer',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'login_mode' => 'email',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_da_officer_login_requires_a_password(): void
    {
        $officer = User::factory()->create([
            'email' => 'officer-login@example.com',
            'role' => 'DA Admin',
        ]);

        $this->post('/login', [
            'email' => $officer->email,
            'login_mode' => 'email',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_da_officer_email_login_requires_a_password(): void
    {
        $officer = User::factory()->create([
            'email' => 'officer@example.com',
            'role' => 'DA Admin',
        ]);

        $this->post('/login', [
            'email' => $officer->email,
            'login_mode' => 'email',
        ])->assertSessionHasErrors([
            'password' => 'Password is required for DA officer accounts.',
        ]);

        $this->assertGuest();
    }

    public function test_da_officer_email_login_accepts_a_valid_password(): void
    {
        $officer = User::factory()->create([
            'email' => 'officer-valid@example.com',
            'role' => 'DA Admin',
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
