<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic users for testing
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@bonekaku.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staff Test',
            'email' => 'staff@bonekaku.test',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@bonekaku.test',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('admin@bonekaku.test');
        $response->assertSee('staff@bonekaku.test');
        $response->assertSee('customer@bonekaku.test');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard_and_admin_routes(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);

        $adminRouteResponse = $this->actingAs($admin)->get('/users');
        $adminRouteResponse->assertStatus(200);
    }

    public function test_staff_cannot_access_admin_only_route(): void
    {
        $staff = User::where('role', 'staff')->first();
        $this->assertNotNull($staff);

        $response = $this->actingAs($staff)->get('/users');
        $response->assertStatus(403);
    }

    public function test_customer_cannot_access_admin_or_staff_routes(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->assertNotNull($customer);

        // Cannot access admin route
        $response = $this->actingAs($customer)->get('/users');
        $response->assertStatus(403);

        // Cannot access manage route
        $responseManage = $this->actingAs($customer)->get('/purchases');
        $responseManage->assertStatus(403);
    }

    public function test_customer_can_access_dashboard(): void
    {
        $customer = User::where('role', 'customer')->first();
        $response = $this->actingAs($customer)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Pesanan Saya');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@bonekaku.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@bonekaku.test',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
