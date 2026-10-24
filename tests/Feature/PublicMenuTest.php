<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesRestaurants;
use Tests\TestCase;

class PublicMenuTest extends TestCase
{
    use CreatesRestaurants, RefreshDatabase;

    public function test_platform_pages_load(): void
    {
        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
        $this->get('/restaurants')->assertOk();
        $this->get(route('admin.login'))->assertOk();
    }

    public function test_platform_lists_only_active_restaurants(): void
    {
        $this->makeRestaurant(['name' => 'Visible Place']);
        $this->makeRestaurant(['name' => 'Hidden Place', 'is_active' => false]);

        $this->get('/restaurants')->assertSee('Visible Place')->assertDontSee('Hidden Place');
    }

    public function test_menu_shows_only_available_dishes(): void
    {
        $r = $this->makeRestaurant(['name' => 'Menu House']);
        $cat = $r->categories()->create(['name' => 'Mains']);
        $r->dishes()->create(['category_id' => $cat->id, 'name' => 'Served Dish', 'price' => 10, 'is_available' => true]);
        $r->dishes()->create(['category_id' => $cat->id, 'name' => 'Sold Out Dish', 'price' => 10, 'is_available' => false]);

        $this->get(route('menu.show', $r->slug))
            ->assertOk()
            ->assertSee('Menu House')
            ->assertSee('Served Dish')
            ->assertDontSee('Sold Out Dish');
    }

    public function test_menu_of_inactive_restaurant_is_404(): void
    {
        $r = $this->makeRestaurant(['is_active' => false]);

        $this->get(route('menu.show', $r->slug))->assertNotFound();
    }

    public function test_menu_shows_only_current_offers(): void
    {
        $r = $this->makeRestaurant();
        $r->offers()->create([
            'title' => 'Live Offer', 'discount_type' => 'percentage', 'discount_value' => 10,
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $r->offers()->create([
            'title' => 'Expired Offer', 'discount_type' => 'percentage', 'discount_value' => 10,
            'start_date' => now()->subDays(10), 'end_date' => now()->subDays(5),
        ]);

        $this->get(route('menu.show', $r->slug))->assertSee('Live Offer')->assertDontSee('Expired Offer');
    }

    public function test_user_content_is_escaped(): void
    {
        $r = $this->makeRestaurant(['name' => '<script>alert(1)</script>']);

        $this->get(route('menu.show', $r->slug))->assertDontSee('<script>alert(1)</script>', false);
    }
}
