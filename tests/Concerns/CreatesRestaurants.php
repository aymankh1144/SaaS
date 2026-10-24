<?php

namespace Tests\Concerns;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

trait CreatesRestaurants
{
    protected function makeRestaurant(array $attributes = [], string $password = 'owner-password'): Restaurant
    {
        static $counter = 0;
        $counter++;

        $restaurant = Restaurant::create(array_merge([
            'name' => "Restaurant {$counter}",
            'slug' => "restaurant-{$counter}",
            'admin_username' => "owner{$counter}",
            'admin_password' => Hash::make($password),
            'is_active' => true,
        ], $attributes));

        $restaurant->colorScheme()->create($this->colors());

        return $restaurant;
    }

    /** يحاكي صاحب المطعم المسجّل دخوله. */
    protected function asOwnerOf(Restaurant $restaurant): static
    {
        return $this->withSession([$restaurant->sessionKey() => true]);
    }

    protected function asPlatformAdmin(): static
    {
        return $this->withSession(['admin_logged_in' => true]);
    }

    protected function colors(): array
    {
        return [
            'primary_color' => '#FF6B6B',
            'secondary_color' => '#4ECDC4',
            'accent_color' => '#FFE66D',
            'text_color' => '#333333',
            'border_color' => '#CCCCCC',
            'background_color' => '#FFFFFF',
        ];
    }
}
