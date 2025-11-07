<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_can_be_loaded()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login'); // Pastikan halaman login tampil
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        // Buat user dengan role default dari factory
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard'); // Sesuaikan jika route dashboard beda
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function logged_in_user_can_access_protected_route()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get('/dashboard'); // Sesuaikan rute jika beda
        $response->assertStatus(200);
        $response->assertSee('Dashboard'); // Cek konten dashboard
    }
}
