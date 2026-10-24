<?php

namespace Tests\Feature;

use App\Models\Dish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesRestaurants;
use Tests\TestCase;

class RestaurantPanelTest extends TestCase
{
    use CreatesRestaurants, RefreshDatabase;

    // ── المصادقة ────────────────────────────────────────────────

    public function test_panel_requires_login(): void
    {
        $restaurant = $this->makeRestaurant();

        $this->get(route('restaurant.dashboard', $restaurant->slug))
            ->assertRedirect(route('restaurant.login', $restaurant->slug));
    }

    public function test_unknown_restaurant_returns_404(): void
    {
        $this->get('/restaurants/does-not-exist/admin/dashboard')->assertNotFound();
        $this->get('/restaurants/does-not-exist/admin/login')->assertNotFound();
    }

    public function test_owner_can_log_in_and_out(): void
    {
        $restaurant = $this->makeRestaurant([], 'right-password');

        $this->post(route('restaurant.login.store', $restaurant->slug), [
            'username' => $restaurant->admin_username,
            'password' => 'right-password',
        ])->assertRedirect(route('restaurant.dashboard', $restaurant->slug));

        $this->assertTrue(session($restaurant->sessionKey()));

        $this->post(route('restaurant.logout', $restaurant->slug));
        $this->assertNull(session($restaurant->sessionKey()));
    }

    public function test_wrong_password_is_rejected(): void
    {
        $restaurant = $this->makeRestaurant([], 'right-password');

        $this->post(route('restaurant.login.store', $restaurant->slug), [
            'username' => $restaurant->admin_username,
            'password' => 'wrong',
        ])->assertSessionHas('error');

        $this->assertNull(session($restaurant->sessionKey()));
    }

    public function test_inactive_restaurant_cannot_log_in(): void
    {
        $restaurant = $this->makeRestaurant(['is_active' => false], 'right-password');

        $this->post(route('restaurant.login.store', $restaurant->slug), [
            'username' => $restaurant->admin_username,
            'password' => 'right-password',
        ])->assertSessionHas('error');

        $this->assertNull(session($restaurant->sessionKey()));
    }

    public function test_session_ends_when_restaurant_is_disabled_mid_session(): void
    {
        $restaurant = $this->makeRestaurant(['is_active' => false]);

        $this->asOwnerOf($restaurant)
            ->get(route('restaurant.dashboard', $restaurant->slug))
            ->assertRedirect(route('restaurant.login', $restaurant->slug));
    }

    public function test_logging_in_to_one_restaurant_does_not_open_another(): void
    {
        $a = $this->makeRestaurant();
        $b = $this->makeRestaurant();

        $this->asOwnerOf($a)
            ->get(route('restaurant.dashboard', $b->slug))
            ->assertRedirect(route('restaurant.login', $b->slug));
    }

    // ── العزل بين المطاعم (IDOR) ───────────────────────────────────

    public function test_owner_cannot_touch_another_restaurants_category(): void
    {
        $a = $this->makeRestaurant();
        $b = $this->makeRestaurant();
        $foreign = $b->categories()->create(['name' => 'Secret']);

        $this->asOwnerOf($a)->get(route('restaurant.categories.edit', [$a->slug, $foreign->id]))->assertNotFound();
        $this->asOwnerOf($a)->put(route('restaurant.categories.update', [$a->slug, $foreign->id]), ['name' => 'Hacked'])->assertNotFound();
        $this->asOwnerOf($a)->delete(route('restaurant.categories.destroy', [$a->slug, $foreign->id]))->assertNotFound();

        $this->assertSame('Secret', $foreign->fresh()->name);
    }

    public function test_owner_cannot_touch_another_restaurants_dish_or_offer(): void
    {
        $a = $this->makeRestaurant();
        $b = $this->makeRestaurant();
        $cat = $b->categories()->create(['name' => 'C']);
        $dish = $b->dishes()->create(['category_id' => $cat->id, 'name' => 'Secret dish', 'price' => 10]);
        $offer = $b->offers()->create([
            'title' => 'Secret offer', 'discount_type' => 'fixed', 'discount_value' => 5,
            'start_date' => now(), 'end_date' => now()->addDay(),
        ]);

        $this->asOwnerOf($a)->delete(route('restaurant.dishes.destroy', [$a->slug, $dish->id]))->assertNotFound();
        $this->asOwnerOf($a)->delete(route('restaurant.offers.destroy', [$a->slug, $offer->id]))->assertNotFound();

        $this->assertDatabaseHas('dishes', ['id' => $dish->id]);
        $this->assertDatabaseHas('offers', ['id' => $offer->id]);
    }

    public function test_dish_cannot_be_attached_to_another_restaurants_category(): void
    {
        $a = $this->makeRestaurant();
        $b = $this->makeRestaurant();
        $foreign = $b->categories()->create(['name' => 'Foreign']);

        $this->asOwnerOf($a)->post(route('restaurant.dishes.store', $a->slug), [
            'category_id' => $foreign->id, 'name' => 'Pizza', 'price' => 10,
        ])->assertSessionHasErrors('category_id');

        $this->assertSame(0, Dish::count());
    }

    public function test_mass_assignment_cannot_move_a_category_to_another_restaurant(): void
    {
        $a = $this->makeRestaurant();
        $b = $this->makeRestaurant();

        $this->asOwnerOf($a)->post(route('restaurant.categories.store', $a->slug), [
            'name' => 'Mine', 'restaurant_id' => $b->id,
        ]);

        $this->assertSame(1, $a->categories()->count());
        $this->assertSame(0, $b->categories()->count());
    }

    // ── الفئات والأطباق والعروض ────────────────────────────────────

    public function test_owner_can_manage_categories(): void
    {
        $r = $this->makeRestaurant();

        $this->asOwnerOf($r)->post(route('restaurant.categories.store', $r->slug), ['name' => 'Pizza', 'icon' => '🍕'])
            ->assertRedirect(route('restaurant.categories.index', $r->slug));

        $category = $r->categories()->firstOrFail();
        $this->assertSame(0, $category->sort_order);

        $this->asOwnerOf($r)->put(route('restaurant.categories.update', [$r->slug, $category->id]), ['name' => 'Pasta']);
        $this->assertSame('Pasta', $category->fresh()->name);

        $this->asOwnerOf($r)->get(route('restaurant.categories.index', $r->slug))->assertOk()->assertSee('Pasta');
        $this->asOwnerOf($r)->delete(route('restaurant.categories.destroy', [$r->slug, $category->id]));
        $this->assertSame(0, $r->categories()->count());
    }

    public function test_dish_checkboxes_default_to_false_when_absent(): void
    {
        $r = $this->makeRestaurant();
        $cat = $r->categories()->create(['name' => 'C']);

        $this->asOwnerOf($r)->post(route('restaurant.dishes.store', $r->slug), [
            'category_id' => $cat->id, 'name' => 'Plain', 'price' => 12.5,
        ]);

        $dish = $r->dishes()->firstOrFail();
        $this->assertFalse($dish->is_available);
        $this->assertFalse($dish->is_vegan);
        $this->assertFalse($dish->is_spicy);
    }

    public function test_dish_image_is_stored_replaced_and_deleted(): void
    {
        Storage::fake('public');
        $r = $this->makeRestaurant();
        $cat = $r->categories()->create(['name' => 'C']);

        $this->asOwnerOf($r)->post(route('restaurant.dishes.store', $r->slug), [
            'category_id' => $cat->id, 'name' => 'Pic', 'price' => 5,
            'image' => UploadedFile::fake()->image('one.jpg'),
        ]);
        $dish = $r->dishes()->firstOrFail();
        $first = $dish->image_path;
        Storage::disk('public')->assertExists($first);

        $this->asOwnerOf($r)->put(route('restaurant.dishes.update', [$r->slug, $dish->id]), [
            'category_id' => $cat->id, 'name' => 'Pic', 'price' => 5,
            'image' => UploadedFile::fake()->image('two.jpg'),
        ]);
        $second = $dish->fresh()->image_path;
        Storage::disk('public')->assertMissing($first);   // القديمة حُذفت
        Storage::disk('public')->assertExists($second);

        $this->asOwnerOf($r)->delete(route('restaurant.dishes.destroy', [$r->slug, $dish->id]));
        Storage::disk('public')->assertMissing($second);
    }

    public function test_non_image_upload_is_rejected(): void
    {
        Storage::fake('public');
        $r = $this->makeRestaurant();
        $cat = $r->categories()->create(['name' => 'C']);

        $this->asOwnerOf($r)->post(route('restaurant.dishes.store', $r->slug), [
            'category_id' => $cat->id, 'name' => 'Bad', 'price' => 5,
            'image' => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('image');
    }

    public function test_offer_validation(): void
    {
        $r = $this->makeRestaurant();
        $base = [
            'title' => 'Deal', 'discount_type' => 'percentage', 'discount_value' => 20,
            'start_date' => now()->toDateTimeString(), 'end_date' => now()->addDay()->toDateTimeString(),
        ];

        $this->asOwnerOf($r)->post(route('restaurant.offers.store', $r->slug), array_merge($base, ['discount_value' => 150]))
            ->assertSessionHasErrors('discount_value');

        $this->asOwnerOf($r)->post(route('restaurant.offers.store', $r->slug), array_merge($base, ['end_date' => now()->subDay()->toDateTimeString()]))
            ->assertSessionHasErrors('end_date');

        $this->asOwnerOf($r)->post(route('restaurant.offers.store', $r->slug), $base)
            ->assertRedirect(route('restaurant.offers.index', $r->slug));
        $this->assertSame(1, $r->offers()->count());
    }

    public function test_fixed_discount_may_exceed_one_hundred(): void
    {
        $r = $this->makeRestaurant();

        $this->asOwnerOf($r)->post(route('restaurant.offers.store', $r->slug), [
            'title' => 'Big', 'discount_type' => 'fixed', 'discount_value' => 500,
            'start_date' => now()->toDateTimeString(), 'end_date' => now()->addDay()->toDateTimeString(),
        ])->assertSessionHasNoErrors();
    }
}
