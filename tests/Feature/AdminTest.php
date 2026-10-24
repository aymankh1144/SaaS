<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesRestaurants;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use CreatesRestaurants, RefreshDatabase;

    private function configureAdmin(string $password = 'admin-secret-pass'): void
    {
        config([
            'menusaas.admin_email' => 'admin@example.com',
            'menusaas.admin_password_hash' => Hash::make($password),
        ]);
    }

    public function test_admin_pages_require_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.restaurants.create'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.restaurants.store'), [])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_with_configured_credentials(): void
    {
        $this->configureAdmin();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'admin-secret-pass',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(session('admin_logged_in'));
    }

    public function test_admin_login_fails_with_wrong_password(): void
    {
        $this->configureAdmin();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'not-the-password',
        ])->assertSessionHas('error');

        $this->assertNull(session('admin_logged_in'));
    }

    public function test_admin_login_is_impossible_when_no_credentials_are_configured(): void
    {
        config(['menusaas.admin_email' => null, 'menusaas.admin_password_hash' => null]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'anything',
        ])->assertSessionHas('error');

        $this->assertNull(session('admin_logged_in'));
    }

    public function test_admin_login_survives_an_invalid_hash_in_env(): void
    {
        config(['menusaas.admin_email' => 'admin@example.com', 'menusaas.admin_password_hash' => 'plain-text-not-a-hash']);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'plain-text-not-a-hash',
        ])->assertSessionHas('error');
    }

    public function test_admin_can_log_out_via_post_only(): void
    {
        $this->asPlatformAdmin()->get('/admin/logout')->assertStatus(405);

        $this->asPlatformAdmin()->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertNull(session('admin_logged_in'));
    }

    public function test_admin_can_create_restaurant_with_arabic_name(): void
    {
        $this->asPlatformAdmin()->post(route('admin.restaurants.store'), array_merge(
            ['name' => 'مطعم الشام'],
            $this->colors()
        ))->assertRedirect(route('admin.dashboard'))->assertSessionHas('success');

        $restaurant = Restaurant::firstOrFail();
        $this->assertMatchesRegularExpression('/^[a-z0-9-]+$/', $restaurant->slug);
        $this->assertNotSame('', $restaurant->admin_username);
        $this->assertNotNull($restaurant->colorScheme);
        $this->assertTrue($restaurant->is_active);
    }

    public function test_two_restaurants_with_the_same_name_get_distinct_slug_and_username(): void
    {
        $payload = array_merge(['name' => 'مطعم الشام'], $this->colors());

        $this->asPlatformAdmin()->post(route('admin.restaurants.store'), $payload);
        $this->asPlatformAdmin()->post(route('admin.restaurants.store'), $payload);

        $this->assertSame(2, Restaurant::count());
        $this->assertSame(2, Restaurant::distinct()->count('slug'));
        $this->assertSame(2, Restaurant::distinct()->count('admin_username'));
    }

    public function test_created_restaurant_password_is_shown_once_and_works(): void
    {
        $response = $this->asPlatformAdmin()->post(route('admin.restaurants.store'), array_merge(
            ['name' => 'Cafe Test'],
            $this->colors()
        ));

        $message = session('success');
        preg_match('/كلمة المرور: (\S+)/u', $message, $m);
        $restaurant = Restaurant::firstOrFail();

        $this->assertNotEmpty($m[1] ?? null);
        $this->assertTrue(Hash::check($m[1], $restaurant->admin_password));
        $this->assertNotSame($m[1], $restaurant->admin_password); // مخزّنة مشفّرة
    }

    public function test_invalid_colors_are_rejected(): void
    {
        $this->asPlatformAdmin()->post(route('admin.restaurants.store'), array_merge(
            ['name' => 'X'],
            $this->colors(),
            ['primary_color' => 'red;}</style><script>']
        ))->assertSessionHasErrors('primary_color');

        $this->assertSame(0, Restaurant::count());
    }

    public function test_admin_can_toggle_a_restaurant(): void
    {
        $restaurant = $this->makeRestaurant();

        $this->asPlatformAdmin()->post(route('admin.restaurants.toggle', $restaurant));
        $this->assertFalse($restaurant->fresh()->is_active);

        $this->asPlatformAdmin()->post(route('admin.restaurants.toggle', $restaurant));
        $this->assertTrue($restaurant->fresh()->is_active);
    }

    public function test_admin_can_reset_a_restaurant_password(): void
    {
        $restaurant = $this->makeRestaurant([], 'old-password');

        $this->asPlatformAdmin()->post(route('admin.restaurants.reset-password', $restaurant))
            ->assertSessionHas('success');

        $this->assertFalse(Hash::check('old-password', $restaurant->fresh()->admin_password));
    }

    public function test_admin_can_delete_a_restaurant_with_its_data(): void
    {
        $restaurant = $this->makeRestaurant();
        $restaurant->categories()->create(['name' => 'Cat']);

        $this->asPlatformAdmin()->delete(route('admin.restaurants.destroy', $restaurant));

        $this->assertDatabaseMissing('restaurants', ['id' => $restaurant->id]);
        $this->assertDatabaseCount('categories', 0);
    }
}
