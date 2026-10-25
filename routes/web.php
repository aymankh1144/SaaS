<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\RestaurantAuthController;
use App\Http\Controllers\RestaurantDashboardController;
use Illuminate\Support\Facades\Route;

// فحص الصحة للحاوية/الموازن (لا يلمس قاعدة البيانات)
Route::get('/healthz', fn () => response('ok', 200, ['Content-Type' => 'text/plain']));

// ── صفحات المنصة العامة ─────────────────────────────────────────
Route::get('/', [PlatformController::class, 'index'])->name('platform.index');
Route::get('/about', [PlatformController::class, 'about'])->name('platform.about');
Route::get('/restaurants', [PlatformController::class, 'restaurants'])->name('platform.restaurants');
Route::get('/restaurants/{slug}', [MenuController::class, 'show'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->name('menu.show');

// ── أدمن المنصة ────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/restaurants/create', [AdminDashboardController::class, 'createForm'])->name('restaurants.create');
        Route::post('/restaurants', [AdminDashboardController::class, 'store'])->name('restaurants.store');
        Route::post('/restaurants/{restaurant}/toggle', [AdminDashboardController::class, 'toggle'])->name('restaurants.toggle');
        Route::post('/restaurants/{restaurant}/reset-password', [AdminDashboardController::class, 'resetPassword'])->name('restaurants.reset-password');
        Route::delete('/restaurants/{restaurant}', [AdminDashboardController::class, 'destroy'])->name('restaurants.destroy');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

// ── لوحة كل مطعم ───────────────────────────────────────────────
Route::prefix('restaurants/{slug}')
    ->where(['slug' => '[A-Za-z0-9_-]+'])
    ->name('restaurant.')
    ->group(function () {
        Route::get('/admin/login', [RestaurantAuthController::class, 'showLogin'])->name('login');
        Route::post('/admin/login', [RestaurantAuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');

        Route::middleware('restaurant.auth')->prefix('admin')->group(function () {
            Route::get('/dashboard', [RestaurantDashboardController::class, 'dashboard'])->name('dashboard');
            Route::post('/logout', [RestaurantAuthController::class, 'logout'])->name('logout');

            Route::resource('categories', CategoryController::class)
                ->except('show')->parameters(['categories' => 'id']);
            Route::resource('dishes', DishController::class)
                ->except('show')->parameters(['dishes' => 'id']);
            Route::resource('offers', OfferController::class)
                ->except('show')->parameters(['offers' => 'id']);
        });
    });
