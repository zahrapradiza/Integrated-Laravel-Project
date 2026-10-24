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

        // Verify portal tabs are present
        $response->assertSee('Pelanggan');
        $response->assertSee('Staff Toko');
        $response->assertSee('Administrator');
        $response->assertSee("Masuk Pelanggan Diza's Plushie");

        // Verify NO demo credentials or passwords leaked on screen
        $response->assertDontSee('Akun Demo Siap Pakai');
        $response->assertDontSee('admin@bonekaku.test');
        $response->assertDontSee('staff@bonekaku.test');
        $response->assertDontSee('customer@bonekaku.test');
    }

    public function test_login_screen_renders_specific_portals(): void
    {
        // 1. Staff portal
        $staffResponse = $this->get('/login?portal=staff');
        $staffResponse->assertStatus(200);
        $staffResponse->assertSee('Portal Staff Operasional');
        $staffResponse->assertSee('Portal Staff Kasir');
        $staffResponse->assertDontSee('Daftar Akun Customer Sekarang');

        // 2. Admin portal
        $adminResponse = $this->get('/login?portal=admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Portal Administrator');
        $adminResponse->assertDontSee('Daftar Akun Customer Sekarang');

        // 3. Customer portal
        $customerResponse = $this->get('/login?portal=customer');
        $customerResponse->assertStatus(200);
        $customerResponse->assertSee("Masuk Pelanggan Diza's Plushie");
        $customerResponse->assertSee('Daftar Akun Customer Sekarang');
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

    public function test_login_with_mismatched_portal_role_is_rejected(): void
    {
        // Customer attempting to login via Admin Portal
        $response = $this->from('/login?portal=admin')->post('/login', [
            'email' => 'customer@bonekaku.test',
            'password' => 'password123',
            'portal_role' => 'admin',
        ]);

        $response->assertRedirect('/login?portal=admin');
        $response->assertSessionHasErrors([
            'email' => 'Akun Anda bukan akun Admin/Staff. Silakan masuk melalui tab Portal Customer.',
        ]);
        $this->assertGuest();

        // Staff attempting to login via Admin Portal
        $staffResponse = $this->from('/login?portal=admin')->post('/login', [
            'email' => 'staff@bonekaku.test',
            'password' => 'password123',
            'portal_role' => 'admin',
        ]);

        $staffResponse->assertRedirect('/login?portal=admin');
        $staffResponse->assertSessionHasErrors([
            'email' => 'Akun Anda bukan akun Administrator. Silakan masuk melalui tab Portal Staff.',
        ]);
        $this->assertGuest();
    }

    public function test_login_with_matching_portal_role_succeeds(): void
    {
        // Customer logging in through Customer Portal
        $customerResponse = $this->post('/login', [
            'email' => 'customer@bonekaku.test',
            'password' => 'password123',
            'portal_role' => 'customer',
        ]);
        $customerResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        auth()->logout();

        // Staff logging in through Staff Portal
        $staffResponse = $this->post('/login', [
            'email' => 'staff@bonekaku.test',
            'password' => 'password123',
            'portal_role' => 'staff',
        ]);
        $staffResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        auth()->logout();

        // Admin logging in through Admin Portal
        $adminResponse = $this->post('/login', [
            'email' => 'admin@bonekaku.test',
            'password' => 'password123',
            'portal_role' => 'admin',
        ]);
        $adminResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_customer_can_login_without_password(): void
    {
        // Existing customer logs in passwordless
        $response = $this->post('/login', [
            'portal_role' => 'customer',
            'name' => 'Nadia Putri',
            'email' => 'customer@bonekaku.test',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals('customer@bonekaku.test', auth()->user()->email);
        $this->assertTrue(auth()->user()->isCustomer());

        auth()->logout();

        // New customer registers and logs in automatically without password
        $newEmail = 'zahra.new@plushie.test';
        $newCustomerResponse = $this->post('/login', [
            'portal_role' => 'customer',
            'name' => 'Zahra Pradiza',
            'email' => $newEmail,
        ]);

        $newCustomerResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => $newEmail,
            'name' => 'Zahra Pradiza',
            'role' => 'customer',
        ]);
    }

    public function test_staff_and_admin_portals_still_require_password(): void
    {
        // Staff portal without password must fail
        $staffResponse = $this->post('/login', [
            'portal_role' => 'staff',
            'email' => 'staff@bonekaku.test',
            'password' => '',
        ]);
        $staffResponse->assertSessionHasErrors('password');
        $this->assertGuest();

        // Admin portal without password must fail
        $adminResponse = $this->post('/login', [
            'portal_role' => 'admin',
            'email' => 'admin@bonekaku.test',
            'password' => '',
        ]);
        $adminResponse->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}
