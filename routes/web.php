<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\\*;

// Platform Routes
Route::get('/', [PlatformController::class, 'index'])->name('platform.index');
Route::get('/about', [PlatformController::class, 'about'])->name('platform.about');
Route::get('/restaurants', [PlatformController::class, 'restaurants'])->name('platform.restaurants');
Route::get('/restaurants/{slug}', [MenuController::class, 'show'])->name('menu.show');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/restaurants/create', [AdminDashboardController::class, 'createForm'])->name('restaurants.create');
        Route::post('/restaurants', [AdminDashboardController::class, 'store'])->name('restaurants.store');
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

// Restaurant Routes
Route::prefix('/restaurants/{slug}')->name('restaurant.')->group(function () {
    Route::get('/admin/login', [RestaurantAuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/login', [RestaurantAuthController::class, 'login'])->name('login.store');

    Route::middleware('restaurant.auth')->group(function () {
        Route::get('/admin/dashboard', [RestaurantDashboardController::class, 'dashboard'])->name('dashboard');

        // Categories
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Dishes
        Route::get('/admin/dishes', [DishController::class, 'index'])->name('dishes.index');
        Route::get('/admin/dishes/create', [DishController::class, 'create'])->name('dishes.create');
        Route::post('/admin/dishes', [DishController::class, 'store'])->name('dishes.store');
        Route::get('/admin/dishes/{id}/edit', [DishController::class, 'edit'])->name('dishes.edit');
        Route::put('/admin/dishes/{id}', [DishController::class, 'update'])->name('dishes.update');
        Route::delete('/admin/dishes/{id}', [DishController::class, 'destroy'])->name('dishes.destroy');

        // Offers
        Route::get('/admin/offers', [OfferController::class, 'index'])->name('offers.index');
        Route::get('/admin/offers/create', [OfferController::class, 'create'])->name('offers.create');
        Route::post('/admin/offers', [OfferController::class, 'store'])->name('offers.store');
        Route::get('/admin/offers/{id}/edit', [OfferController::class, 'edit'])->name('offers.edit');
        Route::put('/admin/offers/{id}', [OfferController::class, 'update'])->name('offers.update');
        Route::delete('/admin/offers/{id}', [OfferController::class, 'destroy'])->name('offers.destroy');

        Route::get('/admin/logout', [RestaurantAuthController::class, 'logout'])->name('logout');
    });
});
